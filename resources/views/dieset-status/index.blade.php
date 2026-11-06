<x-app-layout>
    {{-- Header Page --}}
    <div class="px-6 py-5 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-slate-900 flex items-center justify-center text-white shadow-sm ring-1 ring-slate-800">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Mold Status & Monitoring Part</h2>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                            Live Monitoring
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">Pemantauan kesiapan operasional mold dan ketersediaan stok spare part kritis</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('dieset-status.export') }}" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:text-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                    <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('master-parts.index') }}" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-white bg-slate-900 rounded-lg shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-colors">
                    <svg class="w-4 h-4 mr-1.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Katalog Part
                </a>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        {{-- 4 KPI Metric Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Metric 1: Total Mold --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Mold Terpantau</span>
                    <span class="p-2 rounded-lg bg-blue-50 text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono tabular-nums text-gray-900">{{ number_format($totalMolds) }}</span>
                    <span class="text-xs text-gray-500 font-medium">Unit Mold</span>
                </div>
                <div class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                    <span>100% mold terdaftar di database</span>
                </div>
            </div>

            {{-- Metric 2: Mold Aman --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Kondisi Aman</span>
                    <span class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono tabular-nums text-emerald-600">{{ number_format($safeMoldsCount) }}</span>
                    <span class="text-xs text-gray-500 font-medium">Mold Siap</span>
                </div>
                <div class="mt-2 text-xs text-emerald-700 flex items-center gap-1 font-medium">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Stok spare part terpenuhi (> 2 unit)</span>
                </div>
            </div>

            {{-- Metric 3: Warning / Low Stock --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Warning Part Kritis</span>
                    <span class="p-2 rounded-lg bg-rose-50 text-rose-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono tabular-nums {{ $warningMoldsCount > 0 ? 'text-rose-600' : 'text-gray-900' }}">
                        {{ number_format($warningMoldsCount) }}
                    </span>
                    <span class="text-xs text-gray-500 font-medium">Perlu Penggantian</span>
                </div>
                <div class="mt-2 text-xs text-rose-600 flex items-center gap-1 font-medium">
                    <span class="inline-block w-2 h-2 rounded-full bg-rose-500"></span>
                    <span>Memiliki part dengan stok kritis (≤ 2 unit)</span>
                </div>
            </div>

            {{-- Metric 4: Komponen Terpasang --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Komponen Terpasang</span>
                    <span class="p-2 rounded-lg bg-amber-50 text-amber-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono tabular-nums text-gray-900">{{ number_format($totalInstalledParts) }}</span>
                    <span class="text-xs text-gray-500 font-medium">Part Terhubung</span>
                </div>
                <div class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                    <span class="font-mono font-semibold text-rose-600">{{ $criticalPartsCount }}</span>
                    <span>part butuh restock segera</span>
                </div>
            </div>
        </div>

        {{-- Main Table Container --}}
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            
            {{-- Filter & Control Bar --}}
            <div class="p-5 border-b border-gray-200 bg-gray-50/50 space-y-4">
                
                {{-- Quick Status Filter Tabs --}}
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="inline-flex p-1 bg-gray-200/70 rounded-lg text-xs font-semibold">
                        <a href="{{ route('dieset-status.index', array_merge(request()->query(), ['status' => 'all'])) }}"
                           class="px-3.5 py-1.5 rounded-md transition-all {{ $statusFilter === 'all' ? 'bg-white text-gray-900 shadow-sm font-bold' : 'text-gray-600 hover:text-gray-900' }}">
                            Semua Mold ({{ number_format($totalMolds) }})
                        </a>
                        <a href="{{ route('dieset-status.index', array_merge(request()->query(), ['status' => 'safe'])) }}"
                           class="px-3.5 py-1.5 rounded-md transition-all flex items-center gap-1.5 {{ $statusFilter === 'safe' ? 'bg-white text-emerald-700 shadow-sm font-bold' : 'text-gray-600 hover:text-emerald-700' }}">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Aman ({{ number_format($safeMoldsCount) }})
                        </a>
                        <a href="{{ route('dieset-status.index', array_merge(request()->query(), ['status' => 'warning'])) }}"
                           class="px-3.5 py-1.5 rounded-md transition-all flex items-center gap-1.5 {{ $statusFilter === 'warning' ? 'bg-white text-rose-700 shadow-sm font-bold' : 'text-gray-600 hover:text-rose-700' }}">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            Warning ({{ number_format($warningMoldsCount) }})
                        </a>
                    </div>

                    {{-- Legend Info --}}
                    <div class="hidden lg:flex items-center space-x-4 text-xs text-gray-600 bg-white px-3 py-1.5 rounded-lg border border-gray-200">
                        <span class="font-semibold text-gray-500 uppercase tracking-wider text-[10px]">Indikator:</span>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span>
                            <span>Aman (Stok Terpenuhi)</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-rose-500 mr-1.5"></span>
                            <span>Warning (Part Butuh Penggantian)</span>
                        </div>
                    </div>
                </div>

                {{-- Inputs & Filters Row --}}
                <form method="GET" action="{{ route('dieset-status.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3" x-data>
                    {{-- Hidden status preservation --}}
                    <input type="hidden" name="status" value="{{ $statusFilter }}">

                    {{-- Entries per page --}}
                    <div class="sm:col-span-2 flex items-center space-x-2">
                        <span class="text-xs text-gray-500 font-medium whitespace-nowrap">Baris:</span>
                        <select name="per_page" class="w-full border-gray-300 rounded-lg py-1.5 px-2.5 text-xs focus:ring-slate-900 focus:border-slate-900 text-gray-700 bg-white shadow-sm" x-on:change="$el.closest('form').submit()">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 / hal</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 / hal</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 / hal</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 / hal</option>
                        </select>
                    </div>

                    {{-- Ownership Filter Dropdown --}}
                    <div class="sm:col-span-4">
                        <select name="ownership" class="w-full border-gray-300 rounded-lg py-1.5 px-3 text-xs focus:ring-slate-900 focus:border-slate-900 text-gray-700 bg-white shadow-sm truncate" x-on:change="$el.closest('form').submit()">
                            <option value="all">-- Semua Ownership / Customer --</option>
                            @foreach($ownershipList as $owner)
                                <option value="{{ $owner }}" {{ $ownershipFilter === $owner ? 'selected' : '' }}>
                                    {{ $owner }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Search Input --}}
                    <div class="sm:col-span-6 relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               class="bg-white border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-slate-900 focus:border-slate-900 block w-full pl-9 py-2 pr-9 shadow-sm" 
                               placeholder="Cari kode mold, nama mold, cavity, atau ownership..." 
                               x-on:input.debounce.500ms="$el.closest('form').submit()">
                        @if($search)
                            <a href="{{ route('dieset-status.index', ['status' => $statusFilter, 'ownership' => $ownershipFilter, 'per_page' => $perPage]) }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        @endif
                    </div>
                </form>
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
                            <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-gray-200 w-24">Tahun</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-gray-200 w-28">Komponen</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-gray-200 w-44">Status Kesiapan</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($diesets as $index => $dieset)
                            @php
                                $isWarning = isset($dieset->low_stock_count) && $dieset->low_stock_count > 0;
                                $partsCount = $dieset->total_parts_count ?? 0;
                                $lowCount = $dieset->low_stock_count ?? 0;
                            @endphp

                            <tr class="transition-colors group {{ $isWarning ? 'bg-rose-50/30 hover:bg-rose-50/70 border-l-4 border-l-rose-500' : 'bg-white hover:bg-slate-50 border-l-4 border-l-emerald-500' }}">
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
                                    <a href="{{ route('dieset-status.show', $dieset->id) }}" class="font-bold text-slate-900 hover:text-blue-600 transition-colors flex items-center group-hover:underline">
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

                                {{-- Production Year --}}
                                <td class="px-4 py-3 text-center font-mono text-gray-600 border-r border-gray-100">
                                    {{ $dieset->production_year ?: '-' }}
                                </td>

                                {{-- Komponen Part --}}
                                <td class="px-4 py-3 text-center border-r border-gray-100">
                                    @if($partsCount > 0)
                                        <div class="inline-flex items-center gap-1.5">
                                            <span class="font-mono font-bold text-gray-800">{{ $partsCount }}</span>
                                            <span class="text-[10px] text-gray-500">part</span>
                                            @if($lowCount > 0)
                                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-rose-100 text-rose-700">
                                                    {{ $lowCount }} Kritis
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-[11px] italic">0 Part</span>
                                    @endif
                                </td>

                                {{-- Status Kesiapan Mold --}}
                                <td class="px-4 py-3 text-center border-r border-gray-100">
                                    @if($isWarning)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 shadow-sm">
                                            <span class="w-2 h-2 mr-1.5 rounded-full bg-rose-600 animate-ping"></span>
                                            Warning ({{ $lowCount }} Part Kritis)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                                            <span class="w-2 h-2 mr-1.5 rounded-full bg-emerald-600"></span>
                                            Aman (Stok Terpenuhi)
                                        </span>
                                    @endif
                                </td>

                                {{-- Action Button --}}
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('dieset-status.show', $dieset->id) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-gray-300 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center bg-white">
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mb-3 text-slate-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">Tidak ada data mold ditemukan</p>
                                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                            Kriteria pencarian atau filter status tidak menemukan data yang cocok. Silakan atur ulang kata kunci atau filter.
                                        </p>
                                        <a href="{{ route('dieset-status.index') }}" class="mt-4 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
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