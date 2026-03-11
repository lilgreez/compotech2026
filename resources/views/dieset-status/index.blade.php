<x-app-layout>
    <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center space-x-3">
            <div class="p-2.5 bg-indigo-600 rounded-lg text-white shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Dieset Status</h2>
                <p class="text-sm text-gray-500 mt-0.5">Monitoring kondisi dan status komponen dieset</p>
            </div>
        </div>
        
        <div class="flex items-center">
            <a href="{{ route('dieset-status.export') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium flex items-center hover:bg-gray-50 hover:text-green-600 focus:ring-4 focus:ring-gray-100 transition shadow-sm" title="Export to Excel">
                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </a>
        </div>
    </div>

    <div class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            
            <div class="p-5 border-b border-gray-200 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                <form method="GET" action="{{ route('dieset-status.index') }}" class="w-full flex flex-col sm:flex-row justify-between items-center gap-4" x-data>
                    
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600 whitespace-nowrap">Show</span>
                        <select name="per_page" class="w-20 border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 bg-white shadow-sm" x-on:change="$el.closest('form').submit()">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                        <span class="text-sm text-gray-600 whitespace-nowrap">entries</span>
                    </div>

                    <div class="relative w-full sm:w-72">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 py-2 pr-3 shadow-sm transition-shadow" placeholder="Cari Dieset atau Product..." x-on:input.debounce.500ms="$el.closest('form').submit()">
                    </div>
                </form>
            </div>

            <div class="px-6 py-3 border-b border-gray-100 flex items-center space-x-6 text-xs bg-white">
                <span class="font-semibold text-gray-500 uppercase tracking-wider">Indikator Status:</span>
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full bg-green-500 mr-2 shadow-sm"></span>
                    <span class="text-gray-600">Aman (Stok Terpenuhi)</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full bg-red-500 mr-2 shadow-sm"></span>
                    <span class="text-gray-600">Warning (Ada part butuh penggantian)</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left whitespace-nowrap">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-16 border-r border-gray-200">No.</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left border-r border-gray-200">Dieset Name</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200">Product Code</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left border-r border-gray-200">Product Name / Desc</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200">Production Year</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-32">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($diesets as $index => $dieset)
                            @php
                                // Penentuan warna background baris agar lebih kalem dan profesional
                                $isLowStock = isset($dieset->low_stock_count) && $dieset->low_stock_count > 0;
                                $rowBgClass = $isLowStock ? 'bg-red-50/50 hover:bg-red-50' : 'bg-white hover:bg-green-50/40';
                                $statusBadgeClass = $isLowStock ? 'bg-red-100 text-red-800 border-red-200' : 'bg-green-100 text-green-800 border-green-200';
                                $statusIconClass = $isLowStock ? 'text-red-500' : 'text-green-500';
                                $statusText = $isLowStock ? 'Warning' : 'Aman';
                            @endphp

                            <tr class="{{ $rowBgClass }} transition-colors group cursor-pointer" data-url="{{ route('dieset-status.show', $dieset->id) }}" onclick="window.location.href=this.dataset.url">
                                <td class="px-6 py-4 text-center text-gray-500 border-r border-gray-100">{{ $diesets->firstItem() + $index }}</td>
                                
                                <td class="px-6 py-4 border-r border-gray-100">
                                    <a href="{{ route('dieset-status.show', $dieset->id) }}" class="font-bold text-indigo-600 hover:text-indigo-800 hover:underline flex items-center">
                                        {{ $dieset->name }}
                                        <svg class="w-3.5 h-3.5 ml-1.5 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </td>
                                
                                <td class="px-6 py-4 text-center border-r border-gray-100">
                                    <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md text-xs font-medium border border-gray-200">{{ $dieset->product_code ?? '-' }}</span>
                                </td>
                                
                                <td class="px-6 py-4 text-gray-600 truncate max-w-xs border-r border-gray-100" title="{{ $dieset->description }}">
                                    {{ $dieset->description ?? '-' }}
                                </td>
                                
                                <td class="px-6 py-4 text-center text-gray-600 font-medium border-r border-gray-100">
                                    {{ $dieset->production_year ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusBadgeClass }}">
                                        <svg class="w-3 h-3 mr-1 {{ $statusIconClass }}" fill="currentColor" viewBox="0 0 20 20">
                                            <circle cx="10" cy="10" r="8"></circle>
                                        </svg>
                                        {{ $statusText }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium text-base">Tidak ada data dieset ditemukan.</p>
                                        <p class="text-gray-400 text-sm mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-500 gap-4">
                <div>
                    Showing <span class="font-medium text-gray-900">{{ $diesets->firstItem() ?? 0 }}</span> to <span class="font-medium text-gray-900">{{ $diesets->lastItem() ?? 0 }}</span> of <span class="font-medium text-gray-900">{{ $diesets->total() }}</span> entries
                </div>
                <div>
                    {{ $diesets->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>