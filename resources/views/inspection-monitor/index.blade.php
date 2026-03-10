<x-app-layout>
    <div class="px-6 py-4 bg-white border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-500 rounded text-white shadow">
                <!-- Icon Monitoring -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Monitoring Asset</h2>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="bg-white shadow-sm rounded border border-gray-200 p-6">
            
            <!-- Toolbar Filter Sesuai PDF Halaman 12 -->
            <form method="GET" action="{{ route('monitoring') }}" class="mb-6 border-b border-gray-100 pb-6 flex flex-wrap items-end gap-4">
                
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal Awal</label>
                    <div class="relative">
                        <input type="date" name="start_date" value="{{ $startDate }}" class="pl-3 pr-10 py-2 border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal Akhir</label>
                    <div class="relative">
                        <input type="date" name="end_date" value="{{ $endDate }}" class="pl-3 pr-10 py-2 border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <button type="submit" class="px-5 py-2 bg-blue-500 text-white text-sm font-semibold rounded hover:bg-blue-600 transition shadow-sm">
                    Load Data
                </button>

                <!-- Export Excel Button Icon -->
                <a href="{{ route('export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="ml-4 p-1 hover:bg-gray-100 rounded transition border border-dashed border-yellow-400" title="Export to Excel">
                    <svg class="w-10 h-10 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 14h-2v2h-4v-2H8v-2h2v-2H8v-2h2v-2h4v2h2v2h-2v2h2v2zm-3-9V3.5L18.5 9H13z"/>
                    </svg>
                </a>
            </form>

            <!-- Table Header Tools -->
            <div class="flex justify-between items-center mb-4 text-sm text-gray-600" x-data>
                <div class="flex items-center">
                    <span>Show</span>
                    <select name="per_page" form="filterForm" class="mx-2 border-gray-300 rounded py-1 px-2 focus:ring-blue-500" onchange="window.location.href='?per_page='+this.value+'&start_date={{ $startDate }}&end_date={{ $endDate }}'">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    </select>
                    <span>entries</span>
                </div>
                <div>
                    <label class="mr-2">Search:</label>
                    <input type="text" class="border-gray-300 rounded py-1 px-3 w-48 focus:ring-blue-500 bg-gray-50 cursor-not-allowed" disabled placeholder="(Gunakan fitur search Dieset)">
                </div>
            </div>

            <!-- Table Data Sesuai PDF -->
            <div class="overflow-x-auto border border-gray-200 rounded">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 font-semibold text-center w-10 border-r">No.</th>
                            <th class="px-6 py-3 font-semibold text-center border-r">Dieset Name</th>
                            <th class="px-6 py-3 font-semibold text-center border-r">Product Code</th>
                            <th class="px-6 py-3 font-semibold text-center border-r">Product Name</th>
                            <th class="px-6 py-3 font-semibold text-center">Inspection Count</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($diesets as $index => $dieset)
                            <tr class="bg-white hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-center text-gray-700 border-r">{{ $diesets->firstItem() + $index }}</td>
                                <td class="px-6 py-4 text-center border-r">
                                    <!-- Link ke detail (Sesuai Kotak Kuning di PDF Halaman 12) -->
                                    <a href="{{ route('monitoring.show',['id' => $dieset->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="text-blue-500 font-medium hover:underline border border-dashed border-yellow-400 p-1">
                                        {{ $dieset->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-800 border-r">{{ $dieset->product_code ?? '-' }}</td>
                                <td class="px-6 py-4 text-center text-gray-800 border-r">{{ $dieset->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-center font-bold text-gray-800">{{ $dieset->inspection_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada data inspeksi pada rentang tanggal ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($diesets->hasPages())
                <div class="mt-4 flex items-center justify-between text-sm">
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