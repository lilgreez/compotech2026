<x-app-layout>
    <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center space-x-3">
            <div class="p-2.5 bg-indigo-600 rounded-lg text-white shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Monitoring Asset</h2>
                <p class="text-sm text-gray-500 mt-0.5">Pantau dan analisis frekuensi inspeksi dieset</p>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            
            <div class="p-5 border-b border-gray-200 bg-gray-50/50">
                <form method="GET" action="{{ route('monitoring') }}" id="filterForm" class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 w-full md:w-auto">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Tanggal Awal</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full sm:w-40 border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-shadow bg-white text-gray-700">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full sm:w-40 border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-shadow bg-white text-gray-700">
                        </div>

                        <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition shadow-sm flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter Data
                        </button>
                    </div>

                    <div class="w-full md:w-auto mt-2 md:mt-0">
                        <a href="{{ route('export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="w-full md:w-auto px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium flex items-center justify-center hover:bg-gray-50 hover:text-green-600 focus:ring-4 focus:ring-gray-100 transition shadow-sm" title="Export to Excel">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export Excel
                        </a>
                    </div>
                </form>
            </div>

            <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4 border-b border-gray-100 bg-white">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600 whitespace-nowrap">Show</span>
                    <select name="per_page" class="w-20 border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 bg-white shadow-sm" onchange="window.location.href='?per_page='+this.value+'&start_date={{ $startDate }}&end_date={{ $endDate }}'">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span class="text-sm text-gray-600 whitespace-nowrap">entries</span>
                </div>
                
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" class="bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-lg block w-full pl-10 py-2 pr-3 cursor-not-allowed italic" disabled placeholder="Search dinonaktifkan...">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left whitespace-nowrap">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-16 border-r border-gray-200">No.</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left border-r border-gray-200">Dieset Name</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200">Product Code</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left border-r border-gray-200">Product Name</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-40">Inspection Count</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($diesets as $index => $dieset)
                            <tr class="bg-white hover:bg-blue-50/50 transition-colors group">
                                <td class="px-6 py-4 text-center text-gray-500 border-r border-gray-100">{{ $diesets->firstItem() + $index }}</td>
                                
                                <td class="px-6 py-4 border-r border-gray-100">
                                    <a href="{{ route('monitoring.show',['id' => $dieset->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="font-bold text-indigo-600 hover:text-indigo-800 hover:underline flex items-center">
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
                                
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[3rem] px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $dieset->inspection_count }}x
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium text-base">Belum ada data inspeksi pada rentang tanggal ini.</p>
                                        <p class="text-gray-400 text-sm mt-1">Silakan sesuaikan filter Tanggal Awal dan Akhir.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($diesets->hasPages() || $diesets->total() > 0)
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-500 gap-4">
                    <div>
                        Showing <span class="font-medium text-gray-900">{{ $diesets->firstItem() ?? 0 }}</span> to <span class="font-medium text-gray-900">{{ $diesets->lastItem() ?? 0 }}</span> of <span class="font-medium text-gray-900">{{ $diesets->total() }}</span> entries
                    </div>
                    <div>
                        {{ $diesets->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif
            
        </div>
    </div>
</x-app-layout>