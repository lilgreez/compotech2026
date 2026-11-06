<x-app-layout>
    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        activeTab: 'identitas',
        editTab: 'identitas',
        editData: {
            id: '',
            kode_mold: '',
            name: '',
            cavity: '',
            status: 'Active',
            ownership: '',
            type_cavity: '',
            production_year: '',
            dimensi_mold: '',
            diameter_lokating_ring: '',
            type_cooling: '',
            type_eject: '',
            type_gate: '',
            type_mold: '',
            ukuran_naple: '',
            qty_naple: '',
            naple_std_long: '',
            jumlah_cavity_std: '',
            jumlah_piston_slider: '',
            jumlah_gasvent: '',
            safety_mold: '',
            qty_temp_channel_hr: '',
            total_shoot: 0,
            product_code: '',
            description: ''
        },
        deleteData: { id: '', name: '', kode: '' }
    }">
    
        <!-- Mission Control Header -->
        <div class="px-6 py-5 bg-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center space-x-3.5">
                    <div class="p-2.5 bg-slate-900 rounded-xl text-white shadow-sm ring-1 ring-slate-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h2 class="font-extrabold text-xl text-slate-900 tracking-tight leading-tight">Master Mold</h2>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Master Database Aktif : {{ number_format($stats['total']) }} Cetakan
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Database spesifikasi teknis mold, ownership customer, parameter mekanikal, dan konfigurasi diecast</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2.5 w-full md:w-auto">
                    <button @click="activeTab = 'identitas'; showAddModal = true" class="w-full md:w-auto px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold inline-flex items-center justify-center gap-2 shadow-xs transition-colors focus:ring-4 focus:ring-blue-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
                        Tambah Mold Baru
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6 max-w-7xl mx-auto space-y-6">
            
            <!-- Quick Summary KPI Cards (Slate & Steel) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Total Mold -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Mold</p>
                        <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs font-mono">
                            MS
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-2xl sm:text-3xl font-black text-slate-900 font-mono tabular-nums tracking-tight">{{ number_format($stats['total']) }}</p>
                        <p class="text-xs text-slate-400 mt-1">Total unit cetakan diecast</p>
                    </div>
                </div>

                <!-- Card 2: Mold Active -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs hover:border-emerald-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Mold Active</p>
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-2xl sm:text-3xl font-black text-emerald-600 font-mono tabular-nums tracking-tight">{{ number_format($stats['active']) }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $stats['total'] > 0 ? number_format(($stats['active'] / $stats['total']) * 100, 1) : 0 }}% armada siap operasi</p>
                    </div>
                </div>

                <!-- Card 3: Mold NA / Non-Active -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs hover:border-amber-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Mold NA / Non-Active</p>
                        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-2xl sm:text-3xl font-black text-amber-600 font-mono tabular-nums tracking-tight">{{ number_format($stats['na']) }}</p>
                        <p class="text-xs text-slate-400 mt-1">Standby, perbaikan, atau overhaul</p>
                    </div>
                </div>

                <!-- Card 4: Total Ownership -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs hover:border-indigo-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Ownership</p>
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-2xl sm:text-3xl font-black text-indigo-600 font-mono tabular-nums tracking-tight">{{ number_format($stats['customers']) }}</p>
                        <p class="text-xs text-slate-400 mt-1">Customer & mitra manufaktur</p>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            @if (session('success'))
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl text-xs font-semibold border border-emerald-200 flex items-center shadow-2xs">
                    <svg class="w-5 h-5 mr-3 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-rose-50 text-rose-800 p-4 rounded-xl text-xs font-semibold border border-rose-200 flex items-center shadow-2xs">
                    <svg class="w-5 h-5 mr-3 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if (isset($errors) && $errors->any())
                <div class="bg-rose-50 text-rose-800 p-4 rounded-xl text-xs border border-rose-200 shadow-2xs">
                    <div class="font-bold mb-1 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Terdapat kesalahan input:
                    </div>
                    <ul class="list-disc pl-9 space-y-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <!-- Main Table Card -->
            <div class="bg-white shadow-2xs rounded-xl border border-slate-200 overflow-hidden">
                
                <!-- Quick Status Filter Pills Header -->
                <div class="px-5 pt-4 pb-3 border-b border-slate-200 bg-slate-50/60 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center space-x-1.5 overflow-x-auto py-0.5">
                        <a href="{{ route('master-diesets.index', array_merge(request()->except(['status', 'page']))) }}" 
                           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 {{ empty($status) ? 'bg-slate-900 text-white shadow-2xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                            Semua Mold
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono {{ empty($status) ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-600' }}">{{ number_format($stats['total']) }}</span>
                        </a>
                        <a href="{{ route('master-diesets.index', array_merge(request()->except(['page']), ['status' => 'Active'])) }}" 
                           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 {{ $status === 'Active' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-white text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200' }}">
                            Active
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono {{ $status === 'Active' ? 'bg-emerald-700 text-emerald-100' : 'bg-emerald-50 text-emerald-700' }}">{{ number_format($stats['active']) }}</span>
                        </a>
                        <a href="{{ route('master-diesets.index', array_merge(request()->except(['page']), ['status' => 'NA'])) }}" 
                           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 {{ $status === 'NA' ? 'bg-amber-600 text-white shadow-2xs' : 'bg-white text-slate-600 hover:bg-amber-50 hover:text-amber-700 border border-slate-200' }}">
                            Non-Active (NA)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono {{ $status === 'NA' ? 'bg-amber-700 text-amber-100' : 'bg-amber-50 text-amber-700' }}">{{ number_format($stats['na']) }}</span>
                        </a>
                    </div>

                    @if($search || $status || $ownership)
                        <a href="{{ route('master-diesets.index') }}" class="px-2.5 py-1.5 text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors border border-rose-200 inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Reset Semua Filter
                        </a>
                    @endif
                </div>

                <!-- Table Controls / Filters -->
                <div class="p-4 border-b border-slate-200 bg-white">
                    <form method="GET" action="{{ route('master-diesets.index') }}" class="flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-3">
                        @if($status)
                            <input type="hidden" name="status" value="{{ $status }}">
                        @endif

                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Entries -->
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Show:</span>
                                <select name="per_page" class="border-slate-300 rounded-lg py-1.5 px-3 text-xs font-semibold focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-white shadow-2xs" onchange="this.form.submit()">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>

                            <!-- Filter Ownership -->
                            <div class="flex items-center space-x-2 max-w-xs">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ownership:</span>
                                <select name="ownership" class="border-slate-300 rounded-lg py-1.5 px-3 text-xs font-semibold focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-white shadow-2xs truncate" onchange="this.form.submit()">
                                    <option value="">Semua Ownership</option>
                                    @foreach($ownershipList as $own)
                                        <option value="{{ $own }}" {{ $ownership == $own ? 'selected' : '' }}>{{ $own }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Search Box -->
                        <div class="relative w-full lg:w-80">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}" class="bg-white border border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 py-2 pr-8 shadow-2xs" placeholder="Cari Kode Mold, Nama, Ownership, Dimensi...">
                            @if($search)
                                <a href="{{ route('master-diesets.index', array_merge(request()->except('search'))) }}" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 hover:text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Table Content (Slate & Steel) -->
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left whitespace-nowrap">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th scope="col" class="px-4 py-3.5 font-bold text-center w-12 border-r border-slate-200">No.</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-left border-r border-slate-200">Kode Mold</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-left border-r border-slate-200">Nama Mold</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-slate-200">Cavity</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-slate-200">Status</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-left border-r border-slate-200">Ownership</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-slate-200">Type Cavity</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-slate-200">Thn Mold</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-left border-r border-slate-200">Dimensi Mold</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-left border-r border-slate-200">Cooling / Gate / Mold</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-center min-w-[130px]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($diesets as $index => $dieset)
                                <tr class="bg-white hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-4 py-3 text-center text-slate-400 border-r border-slate-100 font-mono tabular-nums">
                                        {{ $diesets->firstItem() + $index }}
                                    </td>
                                    
                                    <!-- Kode Mold -->
                                    <td class="px-4 py-3 border-r border-slate-100 font-mono">
                                        @if($dieset->kode_mold)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200 shadow-2xs">
                                                {{ $dieset->kode_mold }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic">- (Pending)</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Nama Mold -->
                                    <td class="px-4 py-3 font-bold text-slate-900 border-r border-slate-100 max-w-xs truncate" title="{{ $dieset->name }}">
                                        <a href="{{ route('master-diesets.show', $dieset->id) }}" class="text-slate-900 hover:text-blue-600 inline-flex items-center group-hover:translate-x-0.5 transition-all">
                                            <span>{{ $dieset->name }}</span>
                                            <svg class="w-3.5 h-3.5 ml-1 opacity-0 group-hover:opacity-100 text-blue-600 transition-opacity flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                    </td>

                                    <!-- Cavity -->
                                    <td class="px-4 py-3 text-center border-r border-slate-100 font-bold text-slate-800 font-mono tabular-nums">
                                        {{ $dieset->cavity ?? '-' }}
                                    </td>

                                    <!-- Status -->
                                    <td class="px-4 py-3 text-center border-r border-slate-100">
                                        @if($dieset->status === 'Active')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span> Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500"></span> NA
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Ownership -->
                                    <td class="px-4 py-3 text-slate-700 border-r border-slate-100 font-medium max-w-xs truncate" title="{{ $dieset->ownership }}">
                                        {{ $dieset->ownership ?? '-' }}
                                    </td>

                                    <!-- Type Cavity -->
                                    <td class="px-4 py-3 text-center border-r border-slate-100">
                                        @if($dieset->type_cavity)
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $dieset->type_cavity }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>

                                    <!-- Thn Mold -->
                                    <td class="px-4 py-3 text-center text-slate-700 border-r border-slate-100 font-mono tabular-nums">
                                        {{ $dieset->production_year ?? '-' }}
                                    </td>

                                    <!-- Dimensi Mold -->
                                    <td class="px-4 py-3 text-slate-700 border-r border-slate-100 font-mono text-[11px] tabular-nums">
                                        {{ $dieset->dimensi_mold ?? '-' }}
                                    </td>

                                    <!-- Cooling / Gate / Mold Specs -->
                                    <td class="px-4 py-3 text-slate-600 border-r border-slate-100">
                                        <div class="flex items-center gap-1">
                                            @if($dieset->type_cooling)
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-sky-50 text-sky-700 border border-sky-200" title="Cooling: {{ $dieset->type_cooling }}">
                                                    {{ $dieset->type_cooling }}
                                                </span>
                                            @endif
                                            @if($dieset->type_gate)
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200" title="Gate: {{ $dieset->type_gate }}">
                                                    {{ $dieset->type_gate }}
                                                </span>
                                            @endif
                                            @if($dieset->type_mold)
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200" title="Mold Type: {{ $dieset->type_mold }}">
                                                    {{ $dieset->type_mold }}
                                                </span>
                                            @endif
                                            @if(!$dieset->type_cooling && !$dieset->type_gate && !$dieset->type_mold)
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <a href="{{ route('master-diesets.show', $dieset->id) }}" 
                                               class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-200" 
                                               title="Detail Spesifikasi Mold & Parts">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            <button @click="editData = {
                                                id: {{ $dieset->id }},
                                                kode_mold: '{{ addslashes($dieset->kode_mold ?? '') }}',
                                                name: '{{ addslashes($dieset->name ?? '') }}',
                                                cavity: '{{ addslashes($dieset->cavity ?? '') }}',
                                                status: '{{ addslashes($dieset->status ?? 'Active') }}',
                                                ownership: '{{ addslashes($dieset->ownership ?? '') }}',
                                                type_cavity: '{{ addslashes($dieset->type_cavity ?? '') }}',
                                                production_year: '{{ addslashes($dieset->production_year ?? '') }}',
                                                dimensi_mold: '{{ addslashes($dieset->dimensi_mold ?? '') }}',
                                                diameter_lokating_ring: '{{ addslashes($dieset->diameter_lokating_ring ?? '') }}',
                                                type_cooling: '{{ addslashes($dieset->type_cooling ?? '') }}',
                                                type_eject: '{{ addslashes($dieset->type_eject ?? '') }}',
                                                type_gate: '{{ addslashes($dieset->type_gate ?? '') }}',
                                                type_mold: '{{ addslashes($dieset->type_mold ?? '') }}',
                                                ukuran_naple: '{{ addslashes($dieset->ukuran_naple ?? '') }}',
                                                qty_naple: '{{ addslashes($dieset->qty_naple ?? '') }}',
                                                naple_std_long: '{{ addslashes($dieset->naple_std_long ?? '') }}',
                                                jumlah_cavity_std: '{{ addslashes($dieset->jumlah_cavity_std ?? '') }}',
                                                jumlah_piston_slider: '{{ addslashes($dieset->jumlah_piston_slider ?? '') }}',
                                                jumlah_gasvent: '{{ addslashes($dieset->jumlah_gasvent ?? '') }}',
                                                safety_mold: '{{ addslashes($dieset->safety_mold ?? '') }}',
                                                qty_temp_channel_hr: '{{ addslashes($dieset->qty_temp_channel_hr ?? '') }}',
                                                total_shoot: '{{ $dieset->total_shoot ?? 0 }}',
                                                product_code: '{{ addslashes($dieset->product_code ?? '') }}',
                                                description: '{{ addslashes($dieset->description ?? '') }}'
                                            }; editTab = 'identitas'; showEditModal = true" 
                                                    class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors border border-transparent hover:border-amber-200"
                                                    title="Edit Data Mold">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                            
                                            <button @click="deleteData = { id: {{ $dieset->id }}, name: '{{ addslashes($dieset->name) }}', kode: '{{ addslashes($dieset->kode_mold ?? '-') }}' }; showDeleteModal = true" 
                                                    class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors border border-transparent hover:border-rose-200"
                                                    title="Hapus Mold">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-6 py-14 text-center">
                                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                            <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-3 border border-slate-200">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            </div>
                                            <p class="text-slate-900 font-bold text-sm">Tidak ditemukan data Master Mold</p>
                                            <p class="text-slate-500 text-xs mt-1 mb-3">Coba sesuaikan kata kunci pencarian atau reset filter yang sedang aktif.</p>
                                            <a href="{{ route('master-diesets.index') }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                                                Reset Filter
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/70 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                    <div>
                        Menampilkan <span class="font-bold text-slate-900 font-mono tabular-nums">{{ $diesets->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-900 font-mono tabular-nums">{{ $diesets->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-900 font-mono tabular-nums">{{ number_format($diesets->total()) }}</span> total cetakan
                    </div>
                    <div>{{ $diesets->appends(request()->query())->links('pagination::tailwind') }}</div>
                </div>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- MODAL TAMBAH DATA MOLD (Lengkap 21 Kolom Sesuai Excel Database) -->
        <!-- =================================================================== -->
        <div x-show="showAddModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 overflow-y-auto" 
             style="display: none;" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden transform transition-all my-8" 
                 @click.away="showAddModal = false">
                
                <!-- Modal Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-slate-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-bold text-base leading-tight">Tambah Master Mold Baru</h3>
                            <p class="text-xs text-gray-500">Input spesifikasi lengkap sesuai database Mold MS</p>
                        </div>
                    </div>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('master-diesets.store') }}" method="POST">
                    @csrf
                    
                    <!-- Modal Navigation Tabs -->
                    <div class="px-6 pt-3 bg-white border-b border-gray-200 flex space-x-6 text-xs font-semibold">
                        <button type="button" @click="activeTab = 'identitas'" :class="activeTab === 'identitas' ? 'border-b-2 border-blue-600 text-blue-600 pb-2.5' : 'text-gray-500 hover:text-gray-800 pb-2.5'">
                            1. Identitas & Kepemilikan
                        </button>
                        <button type="button" @click="activeTab = 'mekanikal'" :class="activeTab === 'mekanikal' ? 'border-b-2 border-blue-600 text-blue-600 pb-2.5' : 'text-gray-500 hover:text-gray-800 pb-2.5'">
                            2. Spesifikasi Mekanikal & Dimensi
                        </button>
                        <button type="button" @click="activeTab = 'cooling'" :class="activeTab === 'cooling' ? 'border-b-2 border-blue-600 text-blue-600 pb-2.5' : 'text-gray-500 hover:text-gray-800 pb-2.5'">
                            3. Cooling, Naple & Hardware
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 max-h-[70vh] overflow-y-auto space-y-5">
                        
                        <!-- TAB 1: IDENTITAS -->
                        <div x-show="activeTab === 'identitas'" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Kode Mold</label>
                                    <input type="text" name="kode_mold" placeholder="Contoh: M000.01.2022" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 font-mono">
                                    <span class="text-[10px] text-gray-400">Format: M-kode cust (3 digit) - No. Urut (2 digit).thn (4 digit)</span>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Mold (New) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" required placeholder="Contoh: Mold Cap Hole" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Status <span class="text-rose-500">*</span></label>
                                    <select name="status" required class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="Active">Active</option>
                                        <option value="NA">NA (Non-Active)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Cavity</label>
                                    <input type="text" name="cavity" placeholder="Contoh: 4, 8, 16, 1+1" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Type Cavity</label>
                                    <select name="type_cavity" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">Pilih Type Cavity</option>
                                        <option value="Single">Single</option>
                                        <option value="Family">Family</option>
                                        <option value="Interchange">Interchange</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Ownership</label>
                                    <input type="text" name="ownership" list="customer-list" placeholder="Contoh: Compotec International, PT" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Tahun Mold (Thn Mold)</label>
                                    <input type="text" name="production_year" placeholder="Contoh: 2022" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 font-mono">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Cavity STD</label>
                                    <input type="text" name="jumlah_cavity_std" placeholder="Contoh: 4, 8, 16, 32" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Product Code</label>
                                    <input type="text" name="product_code" placeholder="Contoh: PRD-001" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Total Shoot</label>
                                    <input type="number" name="total_shoot" value="0" min="0" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: MEKANIKAL & DIMENSI -->
                        <div x-show="activeTab === 'mekanikal'" class="space-y-4" style="display: none;">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Dimensi Mold (P x L x T)</label>
                                    <input type="text" name="dimensi_mold" placeholder="Contoh: 250 x 300 x 270" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 font-mono">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Diameter Lokating Ring</label>
                                    <input type="text" name="diameter_lokating_ring" placeholder="Contoh: 100, 120, 100 - 120" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Type Mold</label>
                                    <select name="type_mold" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">Pilih Type Mold</option>
                                        <option value="Two Plate">Two Plate</option>
                                        <option value="Three Plate">Three Plate</option>
                                        <option value="Triple Plate">Triple Plate</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Type Eject</label>
                                    <select name="type_eject" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">Pilih Type Eject</option>
                                        <option value="STD">STD</option>
                                        <option value="OnFly">OnFly</option>
                                        <option value="Hydraulic">Hydraulic</option>
                                        <option value="Direct Mc">Direct Mc</option>
                                        <option value="STD & OnFly">STD & OnFly</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Type Gate</label>
                                    <select name="type_gate" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">Pilih Type Gate</option>
                                        <option value="Side Gate">Side Gate</option>
                                        <option value="Pin Point">Pin Point</option>
                                        <option value="Sub Marine">Sub Marine</option>
                                        <option value="Sprue">Sprue</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Safety Mold</label>
                                    <input type="text" name="safety_mold" placeholder="Sensor / Limit Switch" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Piston Slider</label>
                                    <input type="text" name="jumlah_piston_slider" placeholder="Contoh: - atau 2" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Gasvent</label>
                                    <input type="text" name="jumlah_gasvent" placeholder="Contoh: - atau 4" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Qty Temperature Controller HR</label>
                                    <input type="text" name="qty_temp_channel_hr" placeholder="Contoh: HR 10 Zone, HR 6 Zone" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: COOLING & NAPLE -->
                        <div x-show="activeTab === 'cooling'" class="space-y-4" style="display: none;">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Type Cooling</label>
                                    <select name="type_cooling" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">Pilih Type Cooling</option>
                                        <option value="Chiller">Chiller</option>
                                        <option value="MTC">MTC</option>
                                        <option value="Cooling Tower">Cooling Tower</option>
                                        <option value="Cool Tower & Chiller">Cool Tower & Chiller</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Ukuran Naple Cooling</label>
                                    <input type="text" name="ukuran_naple" placeholder="Contoh: 1/4, 1/8, 1/2, 3/4" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Qty Naple</label>
                                    <input type="text" name="qty_naple" placeholder="Contoh: 8, 16, 28, 32, 48" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Naple STD / Long</label>
                                    <select name="naple_std_long" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">Pilih Jenis Naple</option>
                                        <option value="STD">STD</option>
                                        <option value="Long">Long</option>
                                        <option value="STD / Long">STD / Long</option>
                                        <option value="Coupler">Coupler</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan / Deskripsi Produk</label>
                                    <input type="text" name="description" placeholder="Catatan tambahan spesifikasi produk..." class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="text-xs text-gray-400">
                            Pastikan data spesifikasi sesuai dokumen teknis
                        </div>
                        <div class="flex space-x-2">
                            <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-medium hover:bg-gray-50 transition shadow-sm">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-medium flex items-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Data Mold
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- MODAL EDIT DATA MOLD (Lengkap 21 Kolom Sesuai Excel Database) -->
        <!-- =================================================================== -->
        <div x-show="showEditModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 overflow-y-auto" 
             style="display: none;" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden transform transition-all my-8" 
                 @click.away="showEditModal = false">
                
                <!-- Modal Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-slate-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-bold text-base leading-tight">Edit Data Master Mold</h3>
                            <p class="text-xs text-gray-500">Perbarui informasi teknis mold: <span class="font-semibold text-gray-800" x-text="editData.name"></span></p>
                        </div>
                    </div>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="`/master-diesets/${editData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Navigation Tabs -->
                    <div class="px-6 pt-3 bg-white border-b border-gray-200 flex space-x-6 text-xs font-semibold">
                        <button type="button" @click="editTab = 'identitas'" :class="editTab === 'identitas' ? 'border-b-2 border-blue-600 text-blue-600 pb-2.5' : 'text-gray-500 hover:text-gray-800 pb-2.5'">
                            1. Identitas & Kepemilikan
                        </button>
                        <button type="button" @click="editTab = 'mekanikal'" :class="editTab === 'mekanikal' ? 'border-b-2 border-blue-600 text-blue-600 pb-2.5' : 'text-gray-500 hover:text-gray-800 pb-2.5'">
                            2. Spesifikasi Mekanikal & Dimensi
                        </button>
                        <button type="button" @click="editTab = 'cooling'" :class="editTab === 'cooling' ? 'border-b-2 border-blue-600 text-blue-600 pb-2.5' : 'text-gray-500 hover:text-gray-800 pb-2.5'">
                            3. Cooling, Naple & Hardware
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 max-h-[70vh] overflow-y-auto space-y-5">
                        
                        <!-- TAB 1: IDENTITAS -->
                        <div x-show="editTab === 'identitas'" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Kode Mold</label>
                                    <input type="text" name="kode_mold" x-model="editData.kode_mold" placeholder="Contoh: M000.01.2022" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 font-mono">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Mold (New) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" x-model="editData.name" required class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Status <span class="text-rose-500">*</span></label>
                                    <select name="status" x-model="editData.status" required class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="Active">Active</option>
                                        <option value="NA">NA (Non-Active)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Cavity</label>
                                    <input type="text" name="cavity" x-model="editData.cavity" placeholder="Contoh: 4, 8, 16, 1+1" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Type Cavity</label>
                                    <select name="type_cavity" x-model="editData.type_cavity" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">Pilih Type Cavity</option>
                                        <option value="Single">Single</option>
                                        <option value="Family">Family</option>
                                        <option value="Interchange">Interchange</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Ownership</label>
                                    <input type="text" name="ownership" x-model="editData.ownership" list="customer-list" placeholder="Contoh: Compotec International, PT" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Tahun Mold (Thn Mold)</label>
                                    <input type="text" name="production_year" x-model="editData.production_year" placeholder="Contoh: 2022" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 font-mono">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Cavity STD</label>
                                    <input type="text" name="jumlah_cavity_std" x-model="editData.jumlah_cavity_std" placeholder="Contoh: 4, 8, 16, 32" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Product Code</label>
                                    <input type="text" name="product_code" x-model="editData.product_code" placeholder="Contoh: PRD-001" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Total Shoot</label>
                                    <input type="number" name="total_shoot" x-model="editData.total_shoot" min="0" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: MEKANIKAL & DIMENSI -->
                        <div x-show="editTab === 'mekanikal'" class="space-y-4" style="display: none;">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Dimensi Mold (P x L x T)</label>
                                    <input type="text" name="dimensi_mold" x-model="editData.dimensi_mold" placeholder="Contoh: 250 x 300 x 270" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 font-mono">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Diameter Lokating Ring</label>
                                    <input type="text" name="diameter_lokating_ring" x-model="editData.diameter_lokating_ring" placeholder="Contoh: 100, 120, 100 - 120" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Type Mold</label>
                                    <select name="type_mold" x-model="editData.type_mold" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">Pilih Type Mold</option>
                                        <option value="Two Plate">Two Plate</option>
                                        <option value="Three Plate">Three Plate</option>
                                        <option value="Triple Plate">Triple Plate</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Type Eject</label>
                                    <select name="type_eject" x-model="editData.type_eject" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">Pilih Type Eject</option>
                                        <option value="STD">STD</option>
                                        <option value="OnFly">OnFly</option>
                                        <option value="Hydraulic">Hydraulic</option>
                                        <option value="Direct Mc">Direct Mc</option>
                                        <option value="STD & OnFly">STD & OnFly</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Type Gate</label>
                                    <select name="type_gate" x-model="editData.type_gate" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">Pilih Type Gate</option>
                                        <option value="Side Gate">Side Gate</option>
                                        <option value="Pin Point">Pin Point</option>
                                        <option value="Sub Marine">Sub Marine</option>
                                        <option value="Sprue">Sprue</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Safety Mold</label>
                                    <input type="text" name="safety_mold" x-model="editData.safety_mold" placeholder="Sensor / Limit Switch" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Piston Slider</label>
                                    <input type="text" name="jumlah_piston_slider" x-model="editData.jumlah_piston_slider" placeholder="Contoh: - atau 2" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Gasvent</label>
                                    <input type="text" name="jumlah_gasvent" x-model="editData.jumlah_gasvent" placeholder="Contoh: - atau 4" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Qty Temperature Controller HR</label>
                                    <input type="text" name="qty_temp_channel_hr" x-model="editData.qty_temp_channel_hr" placeholder="Contoh: HR 10 Zone, HR 6 Zone" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: COOLING & NAPLE -->
                        <div x-show="editTab === 'cooling'" class="space-y-4" style="display: none;">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Type Cooling</label>
                                    <select name="type_cooling" x-model="editData.type_cooling" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">Pilih Type Cooling</option>
                                        <option value="Chiller">Chiller</option>
                                        <option value="MTC">MTC</option>
                                        <option value="Cooling Tower">Cooling Tower</option>
                                        <option value="Cool Tower & Chiller">Cool Tower & Chiller</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Ukuran Naple Cooling</label>
                                    <input type="text" name="ukuran_naple" x-model="editData.ukuran_naple" placeholder="Contoh: 1/4, 1/8, 1/2, 3/4" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Qty Naple</label>
                                    <input type="text" name="qty_naple" x-model="editData.qty_naple" placeholder="Contoh: 8, 16, 28, 32, 48" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Naple STD / Long</label>
                                    <select name="naple_std_long" x-model="editData.naple_std_long" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">Pilih Jenis Naple</option>
                                        <option value="STD">STD</option>
                                        <option value="Long">Long</option>
                                        <option value="STD / Long">STD / Long</option>
                                        <option value="Coupler">Coupler</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan / Deskripsi Produk</label>
                                    <input type="text" name="description" x-model="editData.description" placeholder="Catatan tambahan spesifikasi produk..." class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="text-xs text-gray-400">
                            Perubahan akan langsung tercatat pada audit trail
                        </div>
                        <div class="flex space-x-2">
                            <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-medium hover:bg-gray-50 transition shadow-sm">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg text-xs font-medium flex items-center hover:bg-amber-700 focus:ring-4 focus:ring-amber-100 transition shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- MODAL HAPUS DATA MOLD -->
        <!-- =================================================================== -->
        <div x-show="showDeleteModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden transform transition-all" 
                 @click.away="showDeleteModal = false">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center w-14 h-14 rounded-full bg-rose-100 mb-4">
                        <svg class="text-rose-600 w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5">Hapus Master Mold?</h3>
                    <p class="mb-5 text-xs text-gray-500 leading-relaxed">
                        Anda yakin ingin menghapus <span class="font-bold text-gray-800" x-text="deleteData.name"></span> (<span class="font-mono text-gray-700" x-text="deleteData.kode"></span>)?<br>
                        Data mold yang memiliki item parts tidak dapat dihapus.
                    </p>
                    
                    <div class="flex justify-center space-x-2">
                        <button @click="showDeleteModal = false" class="text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-xs font-medium px-4 py-2 transition shadow-sm">Batal</button>
                        <form :action="`/master-diesets/${deleteData.id}`" method="POST" class="inline-flex">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white bg-rose-600 hover:bg-rose-700 rounded-lg text-xs font-medium px-4 py-2 transition shadow-sm">
                                Ya, Hapus Mold
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datalist untuk Autocomplete Ownership -->
        <datalist id="customer-list">
            @foreach($ownershipList as $own)
                <option value="{{ $own }}">
            @endforeach
        </datalist>

    </div>
</x-app-layout>