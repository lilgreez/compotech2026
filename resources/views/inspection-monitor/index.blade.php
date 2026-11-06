<x-app-layout>
    {{-- Header Page --}}
    <div class="px-6 py-5 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-slate-900 flex items-center justify-center text-white shadow-sm ring-1 ring-slate-800">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Inspection Monitor</h2>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 animate-pulse"></span>
                            Audit Log Aktif
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">Monitoring frekuensi inspeksi mold, analisa kondisi komponen, dan riwayat tembakan (shoot cycle)</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:text-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                    <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('repair-tickets.index') }}" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-white bg-slate-900 rounded-lg shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-colors">
                    <svg class="w-4 h-4 mr-1.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Tiket Perbaikan
                </a>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        {{-- 4 KPI Metric Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Metric 1: Total Inspeksi --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Log Inspeksi</span>
                    <span class="p-2 rounded-lg bg-blue-50 text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono tabular-nums text-gray-900">{{ number_format($totalInspections) }}</span>
                    <span class="text-xs text-gray-500 font-medium">Inspeksi</span>
                </div>
                <div class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                    <span>Tersebar di {{ $inspectedMoldsCount }} dari {{ $totalMolds }} mold</span>
                </div>
            </div>

            {{-- Metric 2: Kondisi OK --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Kondisi Prima (OK)</span>
                    <span class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono tabular-nums text-emerald-600">{{ number_format($okCount) }}</span>
                    <span class="text-xs text-gray-500 font-medium">Pemeriksaan</span>
                </div>
                <div class="mt-2 text-xs text-emerald-700 flex items-center gap-1 font-medium">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Hasil inspeksi lolos standar toleransi</span>
                </div>
            </div>

            {{-- Metric 3: Kondisi Repair --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Butuh Perbaikan (Repair)</span>
                    <span class="p-2 rounded-lg bg-amber-50 text-amber-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono tabular-nums text-amber-600">{{ number_format($repairCount) }}</span>
                    <span class="text-xs text-gray-500 font-medium">Temuan Aus</span>
                </div>
                <div class="mt-2 text-xs text-amber-700 flex items-center gap-1 font-medium">
                    <span class="inline-block w-2 h-2 rounded-full bg-amber-500"></span>
                    <span>Tindakan pelumasan & penyesuaian part</span>
                </div>
            </div>

            {{-- Metric 4: Kondisi Replace --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Pergantian Part (Replace)</span>
                    <span class="p-2 rounded-lg bg-rose-50 text-rose-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono tabular-nums text-rose-600">{{ number_format($replaceCount) }}</span>
                    <span class="text-xs text-gray-500 font-medium">Part Diganti</span>
                </div>
                <div class="mt-2 text-xs text-rose-700 flex items-center gap-1 font-medium">
                    <span class="inline-block w-2 h-2 rounded-full bg-rose-500"></span>
                    <span>Komponen kritis diganti baru</span>
                </div>
            </div>
        </div>

        {{-- Main Table Container --}}
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            
            {{-- Filter & Control Bar --}}
            <div class="p-5 border-b border-gray-200 bg-gray-50/50 space-y-4">
                
                {{-- Quick Condition Filter Tabs --}}
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="inline-flex p-1 bg-gray-200/70 rounded-lg text-xs font-semibold">
                        <a href="{{ route('monitoring', array_merge(request()->query(), ['condition' => 'all'])) }}"
                           class="px-3.5 py-1.5 rounded-md transition-all {{ $conditionFilter === 'all' ? 'bg-white text-gray-900 shadow-sm font-bold' : 'text-gray-600 hover:text-gray-900' }}">
                            Semua Status ({{ number_format($totalInspections) }})
                        </a>
                        <a href="{{ route('monitoring', array_merge(request()->query(), ['condition' => 'OK'])) }}"
                           class="px-3.5 py-1.5 rounded-md transition-all flex items-center gap-1.5 {{ $conditionFilter === 'OK' ? 'bg-white text-emerald-700 shadow-sm font-bold' : 'text-gray-600 hover:text-emerald-700' }}">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            OK ({{ number_format($okCount) }})
                        </a>
                        <a href="{{ route('monitoring', array_merge(request()->query(), ['condition' => 'Repair'])) }}"
                           class="px-3.5 py-1.5 rounded-md transition-all flex items-center gap-1.5 {{ $conditionFilter === 'Repair' ? 'bg-white text-amber-700 shadow-sm font-bold' : 'text-gray-600 hover:text-amber-700' }}">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Repair ({{ number_format($repairCount) }})
                        </a>
                        <a href="{{ route('monitoring', array_merge(request()->query(), ['condition' => 'Replace'])) }}"
                           class="px-3.5 py-1.5 rounded-md transition-all flex items-center gap-1.5 {{ $conditionFilter === 'Replace' ? 'bg-white text-rose-700 shadow-sm font-bold' : 'text-gray-600 hover:text-rose-700' }}">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            Replace ({{ number_format($replaceCount) }})
                        </a>
                    </div>

                    {{-- Active filter indicators --}}
                    @if($startDate || $endDate)
                        <div class="flex items-center gap-2 text-xs bg-blue-50 text-blue-800 px-3 py-1.5 rounded-lg border border-blue-200">
                            <span class="font-semibold">Rentang Aktif:</span>
                            <span class="font-mono">{{ $startDate ?: 'Awal' }} s/d {{ $endDate ?: 'Sekarang' }}</span>
                            <a href="{{ route('monitoring', ['condition' => $conditionFilter, 'search' => $search]) }}" class="text-blue-600 hover:text-blue-900 font-bold ml-1" title="Hapus Filter Tanggal">✕</a>
                        </div>
                    @endif
                </div>

                {{-- Date Range & Search Form --}}
                <form method="GET" action="{{ route('monitoring') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3" x-data>
                    <input type="hidden" name="condition" value="{{ $conditionFilter }}">

                    {{-- Tanggal Awal --}}
                    <div class="md:col-span-3">
                        <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase tracking-wider">Tanggal Awal</label>
                        <input type="date" 
                               name="start_date" 
                               value="{{ $startDate }}" 
                               class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-slate-900 focus:border-slate-900 shadow-sm bg-white text-gray-800">
                    </div>

                    {{-- Tanggal Akhir --}}
                    <div class="md:col-span-3">
                        <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase tracking-wider">Tanggal Akhir</label>
                        <input type="date" 
                               name="end_date" 
                               value="{{ $endDate }}" 
                               class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-slate-900 focus:border-slate-900 shadow-sm bg-white text-gray-800">
                    </div>

                    {{-- Action Filter Button --}}
                    <div class="md:col-span-2 flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-slate-900 text-white text-xs font-semibold rounded-lg hover:bg-slate-800 focus:ring-2 focus:ring-slate-900 transition shadow-sm flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Terapkan
                        </button>
                    </div>

                    {{-- Search Input (Live & Functional) --}}
                    <div class="md:col-span-4 flex items-end">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   value="{{ $search }}" 
                                   class="bg-white border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-slate-900 focus:border-slate-900 block w-full pl-9 py-2 pr-8 shadow-sm" 
                                   placeholder="Cari kode mold, nama mold, atau customer..." 
                                   x-on:input.debounce.500ms="$el.closest('form').submit()">
                            @if($search)
                                <a href="{{ route('monitoring', ['start_date' => $startDate, 'end_date' => $endDate, 'condition' => $conditionFilter, 'per_page' => $perPage]) }}" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                {{-- Secondary Controls Row (Per page & Quick Presets) --}}
                <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-gray-200/70 text-xs text-gray-500">
                    <div class="flex items-center space-x-2">
                        <span>Tampilkan:</span>
                        <select name="per_page" class="border-gray-300 rounded-lg py-1 px-2 text-xs bg-white focus:ring-slate-900 focus:border-slate-900" onchange="window.location.href='?per_page='+this.value+'&start_date={{ $startDate }}&end_date={{ $endDate }}&condition={{ $conditionFilter }}&search={{ $search }}'">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 / hal</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 / hal</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 / hal</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 / hal</option>
                        </select>
                    </div>

                    {{-- Quick Date Presets --}}
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-gray-500">Preset:</span>
                        <a href="{{ route('monitoring', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d'), 'condition' => $conditionFilter, 'search' => $search]) }}" 
                           class="px-2 py-0.5 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition-colors">
                            Bulan Ini
                        </a>
                        <a href="{{ route('monitoring', ['start_date' => now()->subDays(30)->format('Y-m-d'), 'end_date' => now()->format('Y-m-d'), 'condition' => $conditionFilter, 'search' => $search]) }}" 
                           class="px-2 py-0.5 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition-colors">
                            30 Hari Terakhir
                        </a>
                        <a href="{{ route('monitoring', ['start_date' => now()->subDays(7)->format('Y-m-d'), 'end_date' => now()->format('Y-m-d'), 'condition' => $conditionFilter, 'search' => $search]) }}" 
                           class="px-2 py-0.5 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition-colors">
                            7 Hari Terakhir
                        </a>
                        <a href="{{ route('monitoring', ['condition' => $conditionFilter, 'search' => $search]) }}" 
                           class="px-2 py-0.5 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition-colors">
                            Semua Waktu
                        </a>
                    </div>
                </div>
            </div>

            {{-- Table View --}}
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left whitespace-nowrap">
                    <thead class="text-[11px] text-gray-600 uppercase tracking-wider bg-slate-100/75 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-4 py-3.5 font-bold text-center w-12 border-r border-gray-200 text-gray-500">No.</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-left border-r border-gray-200 w-36">Kode Mold</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-left border-r border-gray-200 min-w-[200px]">Nama Mold</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-gray-200 w-28">Cavity</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-left border-r border-gray-200 max-w-[220px]">Ownership</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-gray-200 w-40">Inspeksi Terakhir</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-gray-200 w-32">Kondisi Terakhir</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-gray-200 w-32">Frekuensi</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($diesets as $index => $dieset)
                            @php
                                $count = $dieset->inspection_count ?? 0;
                                $latestHistory = $dieset->inspectionHistories->first();

                                // Border styling based on latest condition
                                if ($latestHistory) {
                                    if ($latestHistory->condition === 'Replace') {
                                        $rowClass = 'bg-rose-50/30 hover:bg-rose-50/60 border-l-4 border-l-rose-500';
                                        $condBadge = 'bg-rose-100 text-rose-800 border-rose-200';
                                    } elseif ($latestHistory->condition === 'Repair') {
                                        $rowClass = 'bg-amber-50/30 hover:bg-amber-50/60 border-l-4 border-l-amber-500';
                                        $condBadge = 'bg-amber-100 text-amber-800 border-amber-200';
                                    } else {
                                        $rowClass = 'bg-white hover:bg-slate-50 border-l-4 border-l-emerald-500';
                                        $condBadge = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                    }
                                } else {
                                    $rowClass = 'bg-white hover:bg-slate-50 border-l-4 border-l-slate-200';
                                    $condBadge = 'bg-gray-100 text-gray-600 border-gray-200';
                                }
                            @endphp

                            <tr class="transition-colors group {{ $rowClass }}">
                                {{-- No --}}
                                <td class="px-4 py-3 text-center text-gray-500 font-mono border-r border-gray-100">
                                    {{ $diesets->firstItem() + $index }}
                                </td>

                                {{-- Kode Mold --}}
                                <td class="px-4 py-3 border-r border-gray-100">
                                    <span class="inline-flex items-center font-mono font-bold text-xs text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                        {{ $dieset->kode_mold ?? 'M-'.str_pad($dieset->id, 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                {{-- Nama Mold --}}
                                <td class="px-4 py-3 border-r border-gray-100">
                                    <a href="{{ route('monitoring.show', ['id' => $dieset->id, 'start_date' => $startDate, 'end_date' => $endDate, 'condition' => $conditionFilter]) }}" class="font-bold text-slate-900 hover:text-blue-600 transition-colors flex items-center group-hover:underline">
                                        <span>{{ $dieset->name }}</span>
                                        <svg class="w-3.5 h-3.5 ml-1 text-slate-400 group-hover:text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>

                                {{-- Cavity & Type --}}
                                <td class="px-4 py-3 text-center border-r border-gray-100">
                                    <div class="inline-flex items-center gap-1">
                                        <span class="font-mono font-semibold px-2 py-0.5 rounded text-xs {{ $dieset->cavity ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-gray-400' }}">
                                            {{ $dieset->cavity ? $dieset->cavity . ' C' : '-' }}
                                        </span>
                                        @if($dieset->type_cavity)
                                            <span class="text-[10px] text-gray-500 font-medium">({{ $dieset->type_cavity }})</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Ownership --}}
                                <td class="px-4 py-3 border-r border-gray-100 max-w-[220px] truncate" title="{{ $dieset->ownership }}">
                                    @if($dieset->ownership)
                                        <span class="text-xs font-medium text-gray-700">
                                            {{ $dieset->ownership }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- Inspeksi Terakhir --}}
                                <td class="px-4 py-3 text-center border-r border-gray-100">
                                    @if($latestHistory)
                                        <div class="flex flex-col items-center">
                                            <span class="font-mono font-semibold text-gray-800">{{ $latestHistory->inspection_date->format('Y-m-d') }}</span>
                                            <span class="text-[10px] text-gray-400 font-mono">{{ $latestHistory->inspection_date->format('H:i') }} • {{ $latestHistory->inspection_date->diffForHumans() }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-[11px] italic">Belum Ada</span>
                                    @endif
                                </td>

                                {{-- Kondisi Terakhir --}}
                                <td class="px-4 py-3 text-center border-r border-gray-100">
                                    @if($latestHistory)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold border {{ $condBadge }}">
                                            @if($latestHistory->condition === 'OK')
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                                OK
                                            @elseif($latestHistory->condition === 'Repair')
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                                Repair
                                            @else
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                                Replace
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-[11px]">-</span>
                                    @endif
                                </td>

                                {{-- Frekuensi Inspeksi --}}
                                <td class="px-4 py-3 text-center border-r border-gray-100">
                                    @if($count > 0)
                                        <span class="inline-flex items-center font-mono font-bold text-xs bg-slate-100 text-slate-800 px-2.5 py-1 rounded-md border border-slate-200">
                                            {{ $count }}x Log
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-[11px] italic">0x</span>
                                    @endif
                                </td>

                                {{-- Action Button --}}
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('monitoring.show', ['id' => $dieset->id, 'start_date' => $startDate, 'end_date' => $endDate, 'condition' => $conditionFilter]) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-gray-300 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat Log
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center bg-white">
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mb-3 text-slate-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">Tidak ada data inspeksi ditemukan</p>
                                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                            Kriteria pencarian, filter kondisi, atau rentang tanggal yang dipilih tidak menemukan catatan riwayat inspeksi.
                                        </p>
                                        <a href="{{ route('monitoring') }}" class="mt-4 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                            Reset Filter
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination footer --}}
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/75 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-3">
                <div class="font-medium">
                    Menampilkan <span class="font-mono font-bold text-gray-900">{{ $diesets->firstItem() ?? 0 }}</span> sampai <span class="font-mono font-bold text-gray-900">{{ $diesets->lastItem() ?? 0 }}</span> dari <span class="font-mono font-bold text-gray-900">{{ number_format($diesets->total()) }}</span> total mold
                </div>
                <div>
                    {{ $diesets->links('pagination::tailwind') }}
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>