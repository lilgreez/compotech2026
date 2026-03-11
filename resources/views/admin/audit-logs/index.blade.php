<x-app-layout>
    <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center space-x-3">
            <div class="p-2.5 bg-indigo-600 rounded-lg text-white shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">System Audit Logs</h2>
                <p class="text-sm text-gray-500 mt-0.5">Pantau seluruh riwayat aktivitas dan perubahan data di dalam sistem</p>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-[95rem] mx-auto">
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            
            <div class="p-5 border-b border-gray-200 bg-gray-50/80">
                <form method="GET" action="{{ route('audit-logs.index') }}" class="flex flex-col lg:flex-row flex-wrap items-end gap-4">
                    
                    <div class="w-full sm:w-auto flex-1 min-w-[180px]">
                        <label for="user_id" class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Filter User</label>
                        <select name="user_id" id="user_id" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                            <option value="">Semua User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="w-full sm:w-auto flex-1 min-w-[180px]">
                        <label for="table_name" class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Tabel (Modul)</label>
                        <select name="table_name" id="table_name" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                            <option value="">Semua Tabel</option>
                            @foreach($tableNames as $table)
                                <option value="{{ $table }}" {{ request('table_name') == $table ? 'selected' : '' }}>
                                    {{ $table }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full sm:w-auto min-w-[150px]">
                        <label for="start_date" class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white text-gray-700" value="{{ request('start_date') }}">
                    </div>

                    <div class="w-full sm:w-auto min-w-[150px]">
                        <label for="end_date" class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white text-gray-700" value="{{ request('end_date') }}">
                    </div>

                    <div class="w-full lg:w-auto flex items-center space-x-3 pt-2">
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all shadow-sm flex justify-center items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter
                        </button>
                        <a href="{{ route('audit-logs.index') }}" class="w-full sm:w-auto px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition-all shadow-sm flex justify-center items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left whitespace-nowrap">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200 min-w-[130px]">Date / Time</th>
                            <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">User Operator</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200 w-32">Action</th>
                            <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Table Module</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200 w-28">Record ID</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-32">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($auditLogs as $log)
                            <tr class="bg-white hover:bg-indigo-50/30 transition-colors group">
                                <td class="px-6 py-3 border-r border-gray-100 text-gray-700">
                                    <div class="font-medium text-gray-900">{{ $log->created_at->format('Y-m-d') }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>
                                
                                <td class="px-6 py-3 border-r border-gray-100">
                                    <div class="flex items-center">
                                        @if($log->user)
                                            <div class="h-7 w-7 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-[10px] mr-2.5">
                                                {{ substr($log->user->name, 0, 2) }}
                                            </div>
                                            <span class="text-gray-800 font-medium">{{ $log->user->name }}</span>
                                        @else
                                            <div class="h-7 w-7 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center mr-2.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            </div>
                                            <span class="text-gray-500 font-medium italic">System / Auto</span>
                                        @endif
                                    </div>
                                </td>
                                
                                <td class="px-6 py-3 text-center border-r border-gray-100">
                                    @php
                                        $badgeStyle = match(true) {
                                            str_contains($log->action, 'CREATE') => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            str_contains($log->action, 'UPDATE') => 'bg-amber-50 text-amber-700 border-amber-200',
                                            str_contains($log->action, 'DELETE') => 'bg-red-50 text-red-700 border-red-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-[10px] font-bold border tracking-wider {{ $badgeStyle }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-3 text-gray-700 font-medium border-r border-gray-100">
                                    {{ $log->table_name }}
                                </td>
                                
                                <td class="px-6 py-3 text-center border-r border-gray-100">
                                    <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded border border-gray-200">
                                        #{{ $log->record_id }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-3 text-center">
                                    <a href="{{ route('audit-logs.show', $log) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-500 text-white border border-transparent rounded-md text-xs font-medium hover:bg-indigo-600 shadow-sm transition-colors opacity-90 group-hover:opacity-100">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> 
                                        View Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium text-base">Tidak ada riwayat log yang ditemukan.</p>
                                        <p class="text-gray-400 text-sm mt-1">Coba sesuaikan filter pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($auditLogs->hasPages() || $auditLogs->total() > 0)
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-500 gap-4">
                    <div>
                        Showing <span class="font-medium text-gray-900">{{ $auditLogs->firstItem() ?? 0 }}</span> to <span class="font-medium text-gray-900">{{ $auditLogs->lastItem() ?? 0 }}</span> of <span class="font-medium text-gray-900">{{ $auditLogs->total() }}</span> entries
                    </div>
                    <div>
                        {{ $auditLogs->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif
            
        </div>
    </div>
</x-app-layout>