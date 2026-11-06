<x-app-layout>
    <x-slot name="title">DAS - Tiket Perbaikan Mold</x-slot>

    <div x-data="{ 
        showCreateModal: false,
        selectedMoldId: '',
        previewUrl: null,
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.previewUrl = URL.createObjectURL(file);
            } else {
                this.previewUrl = null;
            }
        }
    }">
        <!-- Page Header (Mission Control Aesthetic) -->
        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center space-x-3.5">
                <div class="p-2.5 bg-slate-900 rounded-lg text-white shadow-sm flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center space-x-2.5">
                        <h2 class="font-bold text-xl text-gray-900 leading-tight">Tiket Perbaikan Mold</h2>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Sistem Tiket Aktif • SLA Maintenance
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Pengajuan komplain kerusakan mold antar departemen, penugasan teknisi maintenance, dan pemantauan perbaikan real-time</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2.5 w-full md:w-auto">
                <button type="button" @click="showCreateModal = true" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold flex items-center justify-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg> 
                    Ajukan Tiket Perbaikan
                </button>
            </div>
        </div>

        <div class="p-6 max-w-7xl mx-auto space-y-6">
            
            <!-- Alert Notifications -->
            @if (session('success'))
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl text-sm font-medium border border-emerald-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- KPI Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Total Tiket Masuk -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-gray-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Tiket Masuk</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1.5 font-mono tabular-nums">{{ number_format($totalTickets) }}</h3>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Seluruh pengajuan perbaikan
                            </p>
                        </div>
                        <div class="p-3 bg-slate-100 rounded-lg text-slate-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Menunggu Penugasan -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-amber-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Menunggu Penugasan</p>
                            <h3 class="text-2xl font-bold text-amber-600 mt-1.5 font-mono tabular-nums">{{ number_format($openTickets) }}</h3>
                            <p class="text-xs text-amber-700 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Antrian status Open
                            </p>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-lg text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Sedang Dikerjakan -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-blue-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Sedang Dikerjakan</p>
                            <h3 class="text-2xl font-bold text-blue-600 mt-1.5 font-mono tabular-nums">{{ number_format($inProgressTickets + $pendingPartTickets) }}</h3>
                            <p class="text-xs text-blue-700 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                {{ $inProgressTickets }} In Progress • {{ $pendingPartTickets }} Menunggu Part
                            </p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Selesai -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-emerald-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Perbaikan Selesai</p>
                            <h3 class="text-2xl font-bold text-emerald-600 mt-1.5 font-mono tabular-nums">{{ number_format($resolvedTickets) }}</h3>
                            <p class="text-xs text-emerald-700 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Siap operasional produksi
                            </p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                
                <!-- Quick Status Filter Tabs -->
                <div class="border-b border-gray-200 bg-gray-50/50 px-5 pt-3 flex flex-wrap items-center gap-2">
                    <a href="{{ route('repair-tickets.index', array_merge(request()->query(), ['status' => 'all', 'page' => 1])) }}"
                       class="px-3.5 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($statusFilter ?? 'all') == 'all' ? 'border-blue-600 text-blue-600 bg-white rounded-t-lg' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span>Semua Tiket</span>
                        <span class="px-1.5 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($statusFilter ?? 'all') == 'all' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700' }}">
                            {{ $totalTickets }}
                        </span>
                    </a>

                    <a href="{{ route('repair-tickets.index', array_merge(request()->query(), ['status' => 'Open', 'page' => 1])) }}"
                       class="px-3.5 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($statusFilter ?? '') == 'Open' ? 'border-amber-600 text-amber-700 bg-white rounded-t-lg' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span>Open (Menunggu)</span>
                        <span class="px-1.5 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($statusFilter ?? '') == 'Open' ? 'bg-amber-100 text-amber-800' : 'bg-gray-200 text-gray-700' }}">
                            {{ $openTickets }}
                        </span>
                    </a>

                    <a href="{{ route('repair-tickets.index', array_merge(request()->query(), ['status' => 'In Progress', 'page' => 1])) }}"
                       class="px-3.5 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($statusFilter ?? '') == 'In Progress' ? 'border-blue-600 text-blue-600 bg-white rounded-t-lg' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>In Progress</span>
                        <span class="px-1.5 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($statusFilter ?? '') == 'In Progress' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700' }}">
                            {{ $inProgressTickets }}
                        </span>
                    </a>

                    <a href="{{ route('repair-tickets.index', array_merge(request()->query(), ['status' => 'Pending Part', 'page' => 1])) }}"
                       class="px-3.5 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($statusFilter ?? '') == 'Pending Part' ? 'border-purple-600 text-purple-700 bg-white rounded-t-lg' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                        <span>Menunggu Part</span>
                        <span class="px-1.5 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($statusFilter ?? '') == 'Pending Part' ? 'bg-purple-100 text-purple-800' : 'bg-gray-200 text-gray-700' }}">
                            {{ $pendingPartTickets }}
                        </span>
                    </a>

                    <a href="{{ route('repair-tickets.index', array_merge(request()->query(), ['status' => 'Resolved', 'page' => 1])) }}"
                       class="px-3.5 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($statusFilter ?? '') == 'Resolved' ? 'border-emerald-600 text-emerald-700 bg-white rounded-t-lg' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Selesai</span>
                        <span class="px-1.5 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($statusFilter ?? '') == 'Resolved' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-700' }}">
                            {{ $resolvedTickets }}
                        </span>
                    </a>
                </div>

                <!-- Secondary Filter Bar -->
                <div class="p-4 border-b border-gray-200 bg-white flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3">
                    <form method="GET" action="{{ route('repair-tickets.index') }}" class="w-full flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3">
                        <input type="hidden" name="status" value="{{ $statusFilter }}">

                        <div class="flex flex-wrap items-center gap-2.5">
                            <!-- Filter Prioritas -->
                            <div class="flex items-center space-x-1.5">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Prioritas:</span>
                                <select name="priority" class="border-gray-300 rounded-lg py-1.5 px-3 text-xs focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm cursor-pointer font-medium" onchange="this.form.submit()">
                                    <option value="all" {{ ($priorityFilter ?? 'all') == 'all' ? 'selected' : '' }}>Semua Prioritas</option>
                                    <option value="Urgent" {{ ($priorityFilter ?? '') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                                    <option value="High" {{ ($priorityFilter ?? '') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ ($priorityFilter ?? '') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ ($priorityFilter ?? '') == 'Low' ? 'selected' : '' }}>Low</option>
                                </select>
                            </div>

                            <!-- Filter Per Page -->
                            <div class="flex items-center space-x-1.5">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tampil:</span>
                                <select name="per_page" class="border-gray-300 rounded-lg py-1.5 px-2.5 text-xs focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm cursor-pointer font-medium tabular-nums" onchange="this.form.submit()">
                                    <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>

                            @if(($statusFilter ?? 'all') !== 'all' || ($priorityFilter ?? 'all') !== 'all' || !empty($search))
                                <a href="{{ route('repair-tickets.index') }}" class="px-2.5 py-1.5 text-xs text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg font-semibold inline-flex items-center gap-1 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Reset Filter
                                </a>
                            @endif
                        </div>
                        
                        <!-- Search Box -->
                        <div class="relative w-full md:w-80">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:bg-white block w-full pl-9 pr-8 py-2 shadow-sm transition placeholder:text-gray-400 font-medium" placeholder="Cari No. Tiket, Kode Mold, Masalah..." onkeydown="if(event.key === 'Enter') this.form.submit()">
                            @if($search)
                                <a href="{{ route('repair-tickets.index', ['status' => $statusFilter, 'priority' => $priorityFilter]) }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-[11px] text-gray-500 uppercase tracking-wider bg-gray-50/80 border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-4 py-3.5 font-semibold text-center border-r border-gray-200 w-12">No.</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold border-r border-gray-200">No. Tiket</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">Mold Bermasalah</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold text-center border-r border-gray-200">Prioritas</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold border-r border-gray-200">Pelapor / Tanggal</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold border-r border-gray-200">Teknisi PIC</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold border-r border-gray-200 w-40">Progress Penanganan</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold text-center border-r border-gray-200">Status</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($tickets as $index => $ticket)
                                @php
                                    $borderClass = 'border-l-4 border-l-slate-200';
                                    if ($ticket->priority === 'Urgent' && !in_array($ticket->status, ['Resolved', 'Closed'])) {
                                        $borderClass = 'border-l-4 border-l-rose-500 bg-rose-50/20';
                                    } elseif ($ticket->priority === 'High' && !in_array($ticket->status, ['Resolved', 'Closed'])) {
                                        $borderClass = 'border-l-4 border-l-amber-500 bg-amber-50/15';
                                    } elseif (in_array($ticket->status, ['Resolved', 'Closed'])) {
                                        $borderClass = 'border-l-4 border-l-emerald-500 bg-white';
                                    }
                                @endphp
                                <tr class="{{ $borderClass }} hover:bg-slate-50/90 transition-colors">
                                    <!-- No -->
                                    <td class="px-4 py-3 text-center text-gray-500 border-r border-gray-100 font-mono tabular-nums text-xs">
                                        {{ $tickets->firstItem() + $index }}
                                    </td>

                                    <!-- No Tiket -->
                                    <td class="px-4 py-3 border-r border-gray-100 font-mono font-bold text-xs">
                                        <a href="{{ route('repair-tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                            <span>{{ $ticket->ticket_number }}</span>
                                        </a>
                                        <span class="text-[10px] text-gray-400 font-normal block mt-0.5">
                                            ID: #{{ $ticket->id }}
                                        </span>
                                    </td>

                                    <!-- Mold Bermasalah -->
                                    <td class="px-5 py-3 border-r border-gray-100">
                                        <div class="flex items-center space-x-2">
                                            @if($ticket->dieset && $ticket->dieset->kode_mold)
                                                <span class="px-1.5 py-0.5 bg-slate-100 text-slate-800 rounded font-mono font-bold text-[11px] border border-slate-200 shrink-0">
                                                    {{ $ticket->dieset->kode_mold }}
                                                </span>
                                            @endif
                                            <span class="font-bold text-gray-900 text-xs truncate max-w-xs" title="{{ $ticket->dieset ? $ticket->dieset->name : 'N/A' }}">
                                                {{ $ticket->dieset ? $ticket->dieset->name : 'N/A' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2 mt-1">
                                            @if($ticket->dieset && $ticket->dieset->ownership)
                                                <span class="text-[10px] text-gray-500 uppercase font-semibold">
                                                    {{ $ticket->dieset->ownership }}
                                                </span>
                                                <span class="text-gray-300">•</span>
                                            @endif
                                            <span class="text-xs text-gray-600 truncate max-w-xs" title="{{ $ticket->issue_description }}">
                                                {{ Str::limit($ticket->issue_description, 50) }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Prioritas -->
                                    <td class="px-4 py-3 text-center border-r border-gray-100">
                                        @if($ticket->priority === 'Urgent')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-ping"></span>
                                                Urgent
                                            </span>
                                        @elseif($ticket->priority === 'High')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                High
                                            </span>
                                        @elseif($ticket->priority === 'Medium')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                Medium
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                Low
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Pelapor / Tanggal -->
                                    <td class="px-4 py-3 border-r border-gray-100 text-xs">
                                        <div class="font-semibold text-gray-800 flex items-center space-x-1.5">
                                            <div class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-[10px] shrink-0">
                                                {{ substr($ticket->reporter ? $ticket->reporter->name : 'O', 0, 1) }}
                                            </div>
                                            <span class="truncate max-w-[130px]">{{ $ticket->reporter ? $ticket->reporter->name : 'Operator' }}</span>
                                        </div>
                                        <div class="text-gray-400 font-mono text-[11px] tabular-nums mt-0.5 ml-6.5">
                                            {{ $ticket->created_at->format('d M Y, H:i') }} WIB
                                        </div>
                                    </td>

                                    <!-- Teknisi PIC -->
                                    <td class="px-4 py-3 border-r border-gray-100 text-xs">
                                        @if($ticket->technician)
                                            <div class="font-semibold text-slate-900 flex items-center space-x-1.5">
                                                <div class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-[10px] shrink-0">
                                                    {{ substr($ticket->technician->name, 0, 1) }}
                                                </div>
                                                <span class="truncate max-w-[130px]">{{ $ticket->technician->name }}</span>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                Belum Ditugaskan
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Progress Penanganan -->
                                    <td class="px-4 py-3 border-r border-gray-100">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div class="h-2 rounded-full transition-all duration-300 {{ $ticket->progress_percent >= 100 ? 'bg-emerald-500' : ($ticket->progress_percent >= 50 ? 'bg-blue-600' : 'bg-amber-500') }}" 
                                                     style="width: {{ $ticket->progress_percent }}%"></div>
                                            </div>
                                            <span class="text-xs font-mono font-bold text-gray-800 tabular-nums w-9 text-right">{{ $ticket->progress_percent }}%</span>
                                        </div>
                                        @if($ticket->technician_notes)
                                            <div class="text-[10px] text-gray-400 truncate max-w-xs mt-0.5 italic" title="{{ $ticket->technician_notes }}">
                                                Note: {{ Str::limit($ticket->technician_notes, 30) }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="px-4 py-3 text-center border-r border-gray-100">
                                        @if($ticket->status === 'Resolved' || $ticket->status === 'Closed')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Selesai
                                            </span>
                                        @elseif($ticket->status === 'In Progress')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                                In Progress
                                            </span>
                                        @elseif($ticket->status === 'Pending Part')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800">
                                                <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                                Menunggu Part
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                                <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Open
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('repair-tickets.show', $ticket->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-medium inline-flex items-center gap-1 shadow-sm transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <p class="font-bold text-gray-800 text-sm">Tidak ada tiket perbaikan ditemukan</p>
                                            <p class="text-xs text-gray-400 mt-1">Tidak ada data yang cocok dengan kriteria filter atau pencarian Anda.</p>
                                            @if(($statusFilter ?? 'all') !== 'all' || ($priorityFilter ?? 'all') !== 'all' || !empty($search))
                                                <a href="{{ route('repair-tickets.index') }}" class="mt-4 px-3.5 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-100 transition inline-flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                    Reset Semua Filter
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-4">
                    <div>
                        Menampilkan <span class="font-bold text-gray-900 font-mono tabular-nums">{{ $tickets->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-gray-900 font-mono tabular-nums">{{ $tickets->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-gray-900 font-mono tabular-nums">{{ number_format($tickets->total()) }}</span> tiket perbaikan
                    </div>
                    <div>
                        {{ $tickets->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: AJUKAN TIKET PERBAIKAN -->
        <div x-show="showCreateModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-xl overflow-hidden border border-gray-200" 
                 @click.away="showCreateModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="p-1.5 bg-blue-100 text-blue-700 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-bold text-sm">Pengajuan Tiket Perbaikan Mold</h3>
                            <p class="text-[11px] text-gray-500">Laporkan kerusakan mold untuk ditangani oleh tim maintenance</p>
                        </div>
                    </div>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('repair-tickets.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Pilih Mold Bermasalah <span class="text-red-500">*</span></label>
                        <select name="dieset_id" required class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 font-medium">
                            <option value="">-- Pilih Mold yang Mengalami Kendala --</option>
                            @foreach($molds as $mold)
                                <option value="{{ $mold->id }}">
                                    [{{ $mold->kode_mold ?: 'NO-CODE' }}] {{ $mold->name }} ({{ $mold->ownership ?: 'Internal' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Tingkat Prioritas Perbaikan <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-4">
                            <label class="cursor-pointer border border-gray-200 rounded-lg p-2.5 hover:bg-slate-50 flex flex-col items-center text-center transition">
                                <input type="radio" name="priority" value="Low" class="text-blue-600 focus:ring-blue-500 mb-1">
                                <span class="text-xs font-bold text-slate-700">Low</span>
                                <span class="text-[10px] text-gray-400">Dapat dijadwalkan</span>
                            </label>
                            <label class="cursor-pointer border border-blue-200 bg-blue-50/40 rounded-lg p-2.5 hover:bg-blue-50 flex flex-col items-center text-center transition">
                                <input type="radio" name="priority" value="Medium" checked class="text-blue-600 focus:ring-blue-500 mb-1">
                                <span class="text-xs font-bold text-blue-700">Medium</span>
                                <span class="text-[10px] text-gray-500">Standar shift</span>
                            </label>
                            <label class="cursor-pointer border border-amber-200 rounded-lg p-2.5 hover:bg-amber-50/50 flex flex-col items-center text-center transition">
                                <input type="radio" name="priority" value="High" class="text-amber-600 focus:ring-amber-500 mb-1">
                                <span class="text-xs font-bold text-amber-700">High</span>
                                <span class="text-[10px] text-amber-600">Perlu segera</span>
                            </label>
                            <label class="cursor-pointer border border-rose-200 rounded-lg p-2.5 hover:bg-rose-50/50 flex flex-col items-center text-center transition">
                                <input type="radio" name="priority" value="Urgent" class="text-rose-600 focus:ring-rose-500 mb-1">
                                <span class="text-xs font-bold text-rose-700">Urgent</span>
                                <span class="text-[10px] text-rose-600">Mesin stop / kritis</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Deskripsi Gejala / Kerusakan Mold <span class="text-red-500">*</span></label>
                        <textarea name="issue_description" required rows="3" placeholder="Jelaskan secara spesifik kerusakan, komponen yang patah/aus (misal: Pin Ejector bengkok, naple bocor, baut slider longgar), dan kondisi mesin..." class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Upload Foto Bukti Kerusakan (Opsional)</label>
                        <input type="file" name="evidence_photo" accept="image/*" @change="handleFileChange" class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maksimal ukuran 3MB.</p>
                        
                        <template x-if="previewUrl">
                            <div class="mt-2.5 p-2 bg-gray-50 rounded-lg border border-gray-200 inline-block">
                                <p class="text-[10px] font-semibold text-gray-500 mb-1">Preview Foto Bukti:</p>
                                <img :src="previewUrl" class="h-28 w-auto rounded object-cover border border-gray-300">
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-4 border-t border-gray-100">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition shadow-sm inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Kirim Tiket Perbaikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
