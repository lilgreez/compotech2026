<x-app-layout>
    <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('audit-logs.index') }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors border border-transparent hover:border-indigo-200 tooltip" title="Kembali ke log sistem">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            
            <div class="p-2.5 bg-indigo-600 rounded-lg text-white shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Audit Log Details</h2>
                <p class="text-sm text-gray-500 mt-0.5">Rincian perubahan data pada sistem</p>
            </div>
        </div>

        <div>
            @php
                $badgeStyle = match(true) {
                    str_contains($auditLog->action, 'CREATE') => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    str_contains($auditLog->action, 'UPDATE') => 'bg-amber-50 text-amber-700 border-amber-200',
                    str_contains($auditLog->action, 'DELETE') => 'bg-red-50 text-red-700 border-red-200',
                    default => 'bg-gray-50 text-gray-700 border-gray-200'
                };
            @endphp
            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold border shadow-sm tracking-wide {{ $badgeStyle }}">
                <span class="w-2 h-2 rounded-full mr-2 {{ str_contains($auditLog->action, 'DELETE') ? 'bg-red-500' : (str_contains($auditLog->action, 'CREATE') ? 'bg-emerald-500' : 'bg-amber-500') }}"></span>
                ACTION: {{ $auditLog->action }}
            </span>
        </div>
    </div>

    <div class="p-6 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-1">
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden sticky top-6">
                    <div class="bg-gray-50/80 border-b border-gray-200 px-6 py-4">
                        <h3 class="font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Record Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Date & Time
                            </p>
                            <p class="font-medium text-gray-900 text-sm">{{ $auditLog->created_at->format('d F Y, H:i:s') }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Executed By User
                            </p>
                            <div class="flex items-center bg-gray-50 p-3 rounded-lg border border-gray-100">
                                @if($auditLog->user)
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm mr-3 shadow-sm">
                                        {{ substr($auditLog->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-indigo-700 text-sm">{{ $auditLog->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $auditLog->user->email ?? 'No email provided' }}</p>
                                    </div>
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-700 text-sm italic">System / Automation</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                Affected Table
                            </p>
                            <p class="font-semibold text-indigo-700 font-mono bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 inline-block text-sm">{{ $auditLog->table_name }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                Record ID
                            </p>
                            <p class="font-bold text-gray-900 text-lg">#{{ $auditLog->record_id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 flex flex-col space-y-6">
                
                @if($auditLog->old_values)
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden flex flex-col">
                    <div class="bg-red-50 border-b border-red-100 px-5 py-3 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="text-sm font-bold text-red-800">Data Sebelumnya (Old Values)</h3>
                        </div>
                        <div class="flex space-x-1.5 opacity-50">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                        </div>
                    </div>
                    <div class="p-0 overflow-x-auto bg-gray-900">
                        <pre class="text-[13px] text-emerald-400 font-mono p-5 m-0 leading-relaxed">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
                @endif

                @if($auditLog->new_values)
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden flex flex-col">
                    <div class="bg-green-50 border-b border-green-200 px-5 py-3 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="text-sm font-bold text-green-800">Data Baru (New Values)</h3>
                        </div>
                        <div class="flex space-x-1.5 opacity-50">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                        </div>
                    </div>
                    <div class="p-0 overflow-x-auto bg-gray-900">
                        <pre class="text-[13px] text-cyan-400 font-mono p-5 m-0 leading-relaxed">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
                @endif

                @if(!$auditLog->old_values && !$auditLog->new_values)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden h-full min-h-[300px] flex items-center justify-center">
                    <div class="text-center p-10">
                        <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-gray-900 font-bold text-lg">Payload Kosong</h3>
                        <p class="text-gray-500 mt-1 max-w-sm mx-auto">Tidak ada detail perubahan data (payload JSON) yang direkam oleh sistem untuk aksi log ini.</p>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>