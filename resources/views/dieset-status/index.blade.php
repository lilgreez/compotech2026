<x-app-layout>
    <div class="px-6 py-4 bg-white border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-500 rounded text-white shadow">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Dieset Status</h2>
                <p class="text-xs text-gray-500">Welcome To Diecast Dieset Parts Monitor</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="bg-white shadow-sm rounded border border-gray-200">
            <!-- Header Card Dieset Status -->
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700">Dieset Status</span>
            </div>

            <!-- Toolbar Sesuai PDF Halaman 9 -->
            <div class="p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                
                <!-- Kiri: Export Button -->
                <div class="flex items-center space-x-4 w-full sm:w-auto">
                    <a href="{{ route('dieset-status.export') }}" class="p-1 hover:bg-gray-100 rounded transition" title="Export to Excel">
                        <!-- Icon Excel Custom -->
                        <svg class="w-10 h-10 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 14h-2v2h-4v-2H8v-2h2v-2H8v-2h2v-2h4v2h2v2h-2v2h2v2zm-3-9V3.5L18.5 9H13z"/>
                        </svg>
                    </a>
                </div>

                <!-- Kanan: Show Entries & Search -->
                <form method="GET" action="{{ route('dieset-status.index') }}" class="flex items-center space-x-6 w-full sm:w-auto" x-data>
                    
                    <div class="flex items-center text-sm text-gray-600">
                        <span>Show</span>
                        <select name="per_page" class="mx-2 border-gray-300 rounded text-sm py-1 px-2 focus:ring-blue-500 focus:border-blue-500" x-on:change="$el.closest('form').submit()">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                        <span>entries</span>
                    </div>

                    <div class="flex items-center text-sm">
                        <label for="search" class="mr-2 text-gray-600">Search:</label>
                        <input type="text" name="search" id="search" value="{{ $search }}" 
                               class="border-gray-300 rounded text-sm py-1 px-3 focus:ring-blue-500 focus:border-blue-500 w-48"
                               x-on:input.debounce.500ms="$el.closest('form').submit()">
                    </div>
                </form>
            </div>

            <!-- Table Data Sesuai PDF -->
            <div class="overflow-x-auto border-t border-gray-100">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 font-semibold text-center w-10">No.</th>
                            <th class="px-6 py-3 font-semibold text-center">Dieset Name</th>
                            <th class="px-6 py-3 font-semibold text-center">Product Code</th>
                            <th class="px-6 py-3 font-semibold text-center">Product Name</th>
                            <th class="px-6 py-3 font-semibold text-center">Tahun</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($diesets as $index => $dieset)
                            @php
                                // Warnai Merah/Pink jika ada stok parts yang kurang, Hijau jika aman
                                $rowClass = $dieset->low_stock_count > 0 ? 'bg-[#ffccdd] hover:bg-[#ffb3cc]' : 'bg-[#ccffcc] hover:bg-[#b3ffb3]';
                            @endphp

                            <tr class="{{ $rowClass }} transition-colors">
                                <td class="px-6 py-3 text-center text-gray-700">{{ $diesets->firstItem() + $index }}</td>
                                <td class="px-6 py-3 text-center">
                                    <a href="{{ route('dieset-status.show', $dieset->id) }}" class="text-blue-600 font-medium hover:underline">
                                        {{ $dieset->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-3 text-center text-gray-800">{{ $dieset->product_code ?? '-' }}</td>
                                <td class="px-6 py-3 text-center text-gray-800">{{ $dieset->description ?? '-' }}</td>
                                <td class="px-6 py-3 text-center text-gray-800">{{ $dieset->production_year ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada data ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Custom Pagination Footer -->
            @if($diesets->hasPages())
                <div class="p-4 border-t border-gray-200 flex items-center justify-between text-sm">
                    <div class="text-gray-600">
                        Showing {{ $diesets->firstItem() ?? 0 }} to {{ $diesets->lastItem() ?? 0 }} of {{ $diesets->total() }} entries
                    </div>
                    <div>
                        {{ $diesets->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>