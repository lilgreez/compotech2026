<x-app-layout>
    <x-slot name="title">DAS - SPK Mold Baru</x-slot>

    <div x-data="{ 
        showCreateModal: false,
        spkNumber: 'SPK-{{ date('Ym') }}-{{ str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT) }}'
    }">
        <!-- Page Header (Mission Control Aesthetic) -->
        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center space-x-3.5">
                <div class="p-2.5 bg-slate-900 rounded-lg text-white shadow-sm flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center space-x-2.5">
                        <h2 class="font-bold text-xl text-gray-900 leading-tight">SPK Mold Baru & Progress Tracking</h2>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Sistem SPK Aktif • 5 Tahapan Standar Manufaktur
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Penerbitan Surat Perintah Kerja pembuatan mold baru, kontrol tahapan manufaktur, dan checklist kebutuhan material</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2.5 w-full md:w-auto">
                <button type="button" @click="showCreateModal = true" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold flex items-center justify-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg> 
                    Terbitkan SPK Baru
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
                <!-- Card 1: Total SPK -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-gray-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total SPK Terbit</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1.5 font-mono tabular-nums">{{ number_format($totalSpk) }}</h3>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Akumulasi pembuatan mold
                            </p>
                        </div>
                        <div class="p-3 bg-slate-100 rounded-lg text-slate-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Sedang Dikerjakan -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-blue-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Sedang Dikerjakan</p>
                            <h3 class="text-2xl font-bold text-blue-600 mt-1.5 font-mono tabular-nums">{{ number_format($inProgressSpk) }}</h3>
                            <p class="text-xs text-blue-700 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Dalam proses manufaktur aktif
                            </p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 3: SPK Selesai -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-emerald-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">SPK Selesai</p>
                            <h3 class="text-2xl font-bold text-emerald-600 mt-1.5 font-mono tabular-nums">{{ number_format($completedSpk) }}</h3>
                            <p class="text-xs text-emerald-700 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                100% tahapan rampung & trial lulus
                            </p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Rata-rata Progress -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-purple-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Rata-rata Progress</p>
                            <h3 class="text-2xl font-bold text-purple-600 mt-1.5 font-mono tabular-nums">{{ $avgProgress }}%</h3>
                            <p class="text-xs text-purple-700 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                Tingkat penyelesaian kumulatif
                            </p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                
                <!-- Quick Status Filter Tabs -->
                <div class="border-b border-gray-200 bg-gray-50/50 px-5 pt-3 flex flex-wrap items-center gap-2">
                    <a href="{{ route('mold-requests.index', array_merge(request()->query(), ['status' => 'all', 'page' => 1])) }}"
                       class="px-3.5 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($statusFilter ?? 'all') == 'all' ? 'border-blue-600 text-blue-600 bg-white rounded-t-lg' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span>Semua SPK</span>
                        <span class="px-1.5 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($statusFilter ?? 'all') == 'all' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700' }}">
                            {{ $totalSpk }}
                        </span>
                    </a>

                    <a href="{{ route('mold-requests.index', array_merge(request()->query(), ['status' => 'In Progress', 'page' => 1])) }}"
                       class="px-3.5 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($statusFilter ?? '') == 'In Progress' ? 'border-blue-600 text-blue-600 bg-white rounded-t-lg' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>In Progress (Aktif)</span>
                        <span class="px-1.5 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($statusFilter ?? '') == 'In Progress' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700' }}">
                            {{ $inProgressSpk }}
                        </span>
                    </a>

                    <a href="{{ route('mold-requests.index', array_merge(request()->query(), ['status' => 'Completed', 'page' => 1])) }}"
                       class="px-3.5 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($statusFilter ?? '') == 'Completed' ? 'border-emerald-600 text-emerald-700 bg-white rounded-t-lg' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Selesai (Completed)</span>
                        <span class="px-1.5 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($statusFilter ?? '') == 'Completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-700' }}">
                            {{ $completedSpk }}
                        </span>
                    </a>

                    <a href="{{ route('mold-requests.index', array_merge(request()->query(), ['status' => 'Draft', 'page' => 1])) }}"
                       class="px-3.5 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($statusFilter ?? '') == 'Draft' ? 'border-slate-600 text-slate-800 bg-white rounded-t-lg' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        <span>Draft</span>
                        <span class="px-1.5 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($statusFilter ?? '') == 'Draft' ? 'bg-slate-200 text-slate-800' : 'bg-gray-200 text-gray-700' }}">
                            {{ $draftSpk }}
                        </span>
                    </a>
                </div>

                <!-- Secondary Filter Bar -->
                <div class="p-4 border-b border-gray-200 bg-white flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3">
                    <form method="GET" action="{{ route('mold-requests.index') }}" class="w-full flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3">
                        <input type="hidden" name="status" value="{{ $statusFilter }}">

                        <div class="flex flex-wrap items-center gap-2.5">
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

                            @if(($statusFilter ?? 'all') !== 'all' || !empty($search))
                                <a href="{{ route('mold-requests.index') }}" class="px-2.5 py-1.5 text-xs text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg font-semibold inline-flex items-center gap-1 transition">
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
                            <input type="text" name="search" value="{{ $search }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:bg-white block w-full pl-9 pr-8 py-2 shadow-sm transition placeholder:text-gray-400 font-medium" placeholder="Cari No. SPK, Nama Mold, Ownership, PIC..." onkeydown="if(event.key === 'Enter') this.form.submit()">
                            @if($search)
                                <a href="{{ route('mold-requests.index', ['status' => $statusFilter]) }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
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
                                <th scope="col" class="px-4 py-3.5 font-semibold border-r border-gray-200">No. SPK</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">Nama Mold & Ownership</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold border-r border-gray-200">PIC Pelaksana</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold border-r border-gray-200">Timeline Target</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold border-r border-gray-200 w-44">Progress Pengerjaan</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold text-center border-r border-gray-200">Status</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($moldRequests as $index => $spk)
                                @php
                                    $borderClass = 'border-l-4 border-l-slate-200';
                                    if ($spk->status === 'Completed') {
                                        $borderClass = 'border-l-4 border-l-emerald-500 bg-white';
                                    } elseif ($spk->status === 'In Progress') {
                                        $borderClass = 'border-l-4 border-l-blue-500 bg-white';
                                    }
                                @endphp
                                <tr class="{{ $borderClass }} hover:bg-slate-50/90 transition-colors">
                                    <!-- No -->
                                    <td class="px-4 py-3 text-center text-gray-500 border-r border-gray-100 font-mono tabular-nums text-xs">
                                        {{ $moldRequests->firstItem() + $index }}
                                    </td>

                                    <!-- No. SPK -->
                                    <td class="px-4 py-3 border-r border-gray-100 font-mono font-bold text-xs">
                                        <a href="{{ route('mold-requests.show', $spk->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                            <span>{{ $spk->spk_number }}</span>
                                        </a>
                                        <span class="text-[10px] text-gray-400 font-normal block mt-0.5">
                                            ID: #{{ $spk->id }}
                                        </span>
                                    </td>

                                    <!-- Nama Mold & Ownership -->
                                    <td class="px-5 py-3 border-r border-gray-100">
                                        <div class="font-bold text-gray-900 text-xs">
                                            {{ $spk->mold_name }}
                                        </div>
                                        <div class="flex items-center space-x-2 mt-0.5">
                                            <span class="px-1.5 py-0.2 bg-slate-100 text-slate-800 rounded font-semibold text-[10px] uppercase border border-slate-200">
                                                {{ $spk->customer }}
                                            </span>
                                            @if($spk->notes)
                                                <span class="text-gray-300">•</span>
                                                <span class="text-xs text-gray-500 truncate max-w-xs" title="{{ $spk->notes }}">
                                                    {{ Str::limit($spk->notes, 35) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- PIC Pelaksana -->
                                    <td class="px-4 py-3 border-r border-gray-100 text-xs">
                                        @if($spk->pic_name)
                                            <div class="font-semibold text-gray-800 flex items-center space-x-1.5">
                                                <div class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-[10px] shrink-0">
                                                    {{ substr($spk->pic_name, 0, 1) }}
                                                </div>
                                                <span class="truncate max-w-[130px]">{{ $spk->pic_name }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic text-[11px]">Belum Ditentukan</span>
                                        @endif
                                    </td>

                                    <!-- Timeline Target -->
                                    <td class="px-4 py-3 border-r border-gray-100 text-xs">
                                        <div class="font-medium text-gray-700 font-mono tabular-nums">
                                            Target: {{ $spk->target_date ? $spk->target_date->format('d M Y') : '-' }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 font-mono tabular-nums mt-0.5">
                                            Mulai: {{ $spk->start_date ? $spk->start_date->format('d M Y') : '-' }}
                                        </div>
                                    </td>

                                    <!-- Progress Pengerjaan -->
                                    <td class="px-4 py-3 border-r border-gray-100">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div class="h-2 rounded-full transition-all duration-300 {{ $spk->total_progress >= 100 ? 'bg-emerald-500' : ($spk->total_progress >= 50 ? 'bg-blue-600' : 'bg-amber-500') }}" 
                                                     style="width: {{ $spk->total_progress }}%"></div>
                                            </div>
                                            <span class="text-xs font-mono font-bold text-gray-800 tabular-nums w-9 text-right">{{ $spk->total_progress }}%</span>
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-4 py-3 text-center border-r border-gray-100">
                                        @if($spk->status === 'Completed')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Selesai
                                            </span>
                                        @elseif($spk->status === 'In Progress')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                                In Progress
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                                {{ $spk->status }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('mold-requests.show', $spk->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-medium inline-flex items-center gap-1 shadow-sm transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            Tracker
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-16 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center max-w-md mx-auto">
                                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500 mb-3.5 border border-slate-200 shadow-xs">
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-gray-900 text-base">Belum Ada Data SPK Pembuatan Mold</h4>
                                            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                                                Sistem siap menerbitkan dokumen SPK baru untuk memonitor 5 tahapan fabrikasi mold: Desain (20%), Machining (30%), Assembly (20%), Trial (15%), dan Finishing (15%).
                                            </p>
                                            <button type="button" @click="showCreateModal = true" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition shadow-sm inline-flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                Terbitkan SPK Baru Sekarang
                                            </button>
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
                        Menampilkan <span class="font-bold text-gray-900 font-mono tabular-nums">{{ $moldRequests->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-gray-900 font-mono tabular-nums">{{ $moldRequests->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-gray-900 font-mono tabular-nums">{{ number_format($moldRequests->total()) }}</span> dokumen SPK
                    </div>
                    <div>
                        {{ $moldRequests->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: TERBITKAN SPK BARU -->
        <div x-show="showCreateModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh] border border-gray-200" 
                 @click.away="showCreateModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="p-1.5 bg-blue-100 text-blue-700 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-bold text-sm">Penerbitan SPK Pembuatan Mold Baru</h3>
                            <p class="text-[11px] text-gray-500">Form Surat Perintah Kerja fabrikasi & progress tracking mold</p>
                        </div>
                    </div>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('mold-requests.store') }}" method="POST" class="flex-1 overflow-y-auto p-6 space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nomor SPK <span class="text-red-500">*</span></label>
                            <input type="text" name="spk_number" required x-model="spkNumber" placeholder="Contoh: SPK-2026-0001" class="w-full font-mono font-bold border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            <p class="text-[10px] text-gray-400 mt-1">Dapat disesuaikan dengan format penomoran SPK internal.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Mold / Part Mold <span class="text-red-500">*</span></label>
                            <input type="text" name="mold_name" required placeholder="Contoh: MOLD CRANKCASE LH K56" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Ownership (Pemilik Mold) <span class="text-red-500">*</span></label>
                            <input type="text" name="customer" required placeholder="Contoh: PT. Astra Honda Motor / PT. Yamaha" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">PIC Pelaksana / Tooling</label>
                            <input type="text" name="pic_name" placeholder="Nama teknisi / tim tooling penanggung jawab..." class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Tanggal Mulai Pengerjaan <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" required value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Target Selesai (Delivery) <span class="text-red-500">*</span></label>
                            <input type="date" name="target_date" required value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Spesifikasi Tambahan</label>
                        <textarea name="notes" rows="2" placeholder="Catatan material baja (SKD61), dimensi cavity, atau standar toleransi khusus..." class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm"></textarea>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-3.5 text-xs text-blue-900 leading-relaxed">
                        <span class="font-bold block mb-1">Struktur 5 Tahapan Standar Manufaktur (Otomatis):</span>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 mt-1.5 text-center font-semibold text-[11px]">
                            <div class="bg-white/80 p-1.5 rounded border border-blue-200">1. Desain (20%)</div>
                            <div class="bg-white/80 p-1.5 rounded border border-blue-200">2. Machining (30%)</div>
                            <div class="bg-white/80 p-1.5 rounded border border-blue-200">3. Assembly (20%)</div>
                            <div class="bg-white/80 p-1.5 rounded border border-blue-200">4. Trial (15%)</div>
                            <div class="bg-white/80 p-1.5 rounded border border-blue-200">5. Finishing (15%)</div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-3 border-t border-gray-100">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold flex items-center hover:bg-blue-700 transition shadow-sm gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Terbitkan Dokumen SPK
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
