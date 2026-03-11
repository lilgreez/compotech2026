<x-app-layout>
    <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center space-x-3">
            <div class="p-2.5 bg-indigo-600 rounded-lg text-white shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Stock Parts</h2>
                <p class="text-sm text-gray-500 mt-0.5">Monitoring inventaris dan ketersediaan komponen</p>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-7xl mx-auto">
        
        @if (session('success'))
            <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-lg text-sm font-medium border border-green-200 flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            
            <div class="bg-gray-50/80 border-b border-gray-200 px-6 pt-4">
                <nav class="flex space-x-8" aria-label="Tabs">
                    <a href="{{ route('parts-stock.index', ['tab' => 'all']) }}" 
                       class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors
                              {{ $tab === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        All Stock
                    </a>
                    <a href="{{ route('parts-stock.index', ['tab' => 'low']) }}" 
                       class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm flex items-center transition-colors
                              {{ $tab === 'low' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Low Stock
                        @if($tab !== 'low' && \App\Models\MasterPart::where('current_stock', '<=', 2)->count() > 0)
                            <span class="ml-2 bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-[10px] font-bold">
                                {{ \App\Models\MasterPart::where('current_stock', '<=', 2)->count() }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('parts-stock.index', ['tab' => 'safe']) }}" 
                       class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors
                              {{ $tab === 'safe' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Safe Stock
                    </a>
                </nav>
            </div>

            <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white">
                <form method="GET" action="{{ route('parts-stock.index') }}" class="w-full flex flex-col sm:flex-row justify-between items-center gap-4" x-data>
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    
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
                        <input type="text" name="search" value="{{ $search }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 py-2 pr-3 shadow-sm transition-shadow" placeholder="Cari Kode atau Nama Parts..." x-on:input.debounce.500ms="$el.closest('form').submit()">
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left whitespace-nowrap">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-16 border-r border-gray-200">No.</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left border-r border-gray-200">Parts Code</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left border-r border-gray-200">Parts Name</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left border-r border-gray-200">Parts Desc</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-32">Current Stock</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($parts as $index => $part)
                            @php
                                $isLowStock = $part->current_stock <= 2;
                                $rowBgClass = $isLowStock ? 'bg-red-50/30 hover:bg-red-50' : 'bg-white hover:bg-indigo-50/50';
                                $stockBadgeClass = $isLowStock ? 'bg-red-100 text-red-700 border-red-200' : 'bg-green-100 text-green-700 border-green-200';
                            @endphp
                            
                            <tr class="{{ $rowBgClass }} transition-colors">
                                <td class="px-6 py-4 text-center text-gray-500 border-r border-gray-100">{{ $parts->firstItem() + $index }}</td>
                                
                                <td class="px-6 py-4 border-r border-gray-100">
                                    <span class="font-bold text-gray-900">{{ $part->part_code ?? '-' }}</span>
                                </td>
                                
                                <td class="px-6 py-4 border-r border-gray-100 text-gray-800 font-medium">
                                    {{ $part->name }}
                                </td>
                                
                                <td class="px-6 py-4 text-gray-600 border-r border-gray-100 truncate max-w-xs" title="{{ $part->description }}">
                                    {{ $part->description ?? '-' }}
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[3rem] px-2.5 py-1 rounded-full text-xs font-bold border {{ $stockBadgeClass }}">
                                        {{ $part->current_stock }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium text-base">Tidak ada data parts di kategori ini.</p>
                                        <p class="text-gray-400 text-sm mt-1">Coba ganti tab kategori atau ubah kata kunci pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col md:flex-row items-center justify-between text-sm text-gray-500 gap-4">
                
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                    @if($tab === 'low' && $parts->count() > 0)
                        <form method="POST" action="{{ route('parts-stock.mail') }}" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 shadow-sm transition-all flex items-center justify-center" title="Kirim notifikasi re-order ke Supervisor">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Request Order SPV
                            </button>
                        </form>
                    @endif
                    
                    <div class="text-gray-600 font-medium text-center sm:text-left">
                        Showing <span class="text-gray-900">{{ $parts->firstItem() ?? 0 }}</span> to <span class="text-gray-900">{{ $parts->lastItem() ?? 0 }}</span> of <span class="text-gray-900">{{ $parts->total() }}</span> entries
                    </div>
                </div>

                <div class="w-full md:w-auto flex justify-center md:justify-end overflow-x-auto pb-1 md:pb-0">
                    {{ $parts->appends(request()->query())->links('pagination::tailwind') }}
                </div>
                
            </div>

        </div>
    </div>
</x-app-layout>