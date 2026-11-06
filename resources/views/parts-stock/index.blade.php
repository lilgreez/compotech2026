<x-app-layout>
    <x-slot name="title">DAS - Stock Parts</x-slot>

    <div x-data="{ 
        showMailModal: false,
        showAdjustModal: false,
        adjustPartId: null,
        adjustPartCode: '',
        adjustPartName: '',
        adjustCurrentStock: 0,
        adjustNewStock: 0,
        openAdjustModal(part) {
            this.adjustPartId = part.id;
            this.adjustPartCode = part.part_code || '-';
            this.adjustPartName = part.name;
            this.adjustCurrentStock = part.current_stock;
            this.adjustNewStock = part.current_stock;
            this.showAdjustModal = true;
        }
    }">
        <!-- Page Header (Mission Control Aesthetic) -->
        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center space-x-3.5">
                <div class="p-2.5 bg-slate-900 rounded-lg text-white shadow-sm flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center space-x-2.5">
                        <h2 class="font-bold text-xl text-gray-900 leading-tight">Stock Spare Part Mold</h2>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Inventaris Aktif • Threshold Buffer ≤ 2 Pcs
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Monitoring ketersediaan suku cadang mold, kontrol buffer stock, dan integrasi reorder otomatis ke Supervisor</p>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
                <!-- Export Excel Button -->
                <a href="{{ route('parts-stock.export') }}" class="w-full sm:w-auto px-3.5 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition shadow-sm inline-flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Export Excel</span>
                </a>

                <!-- Request Order SPV Button -->
                <button type="button" @click="showMailModal = true" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold flex items-center justify-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition shadow-sm gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg> 
                    <span>Request Order SPV</span>
                    @if($lowStockCount > 0)
                        <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] font-mono bg-rose-500 text-white font-bold">
                            {{ $lowStockCount }}
                        </span>
                    @endif
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
                <!-- Card 1: Total Item Parts -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-gray-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Item Parts (SKU)</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1.5 font-mono tabular-nums">{{ number_format($totalParts) }}</h3>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Seluruh katalog suku cadang
                            </p>
                        </div>
                        <div class="p-3 bg-slate-100 rounded-lg text-slate-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Low Stock (Kritis) -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-rose-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Low Stock (Kritis)</p>
                            <h3 class="text-2xl font-bold text-rose-600 mt-1.5 font-mono tabular-nums">{{ number_format($lowStockCount) }}</h3>
                            <p class="text-xs text-rose-700 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                Stok ≤ 2 Pcs (Perlu Reorder Segera)
                            </p>
                        </div>
                        <div class="p-3 bg-rose-50 rounded-lg text-rose-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Safe Stock (Aman) -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-emerald-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Safe Stock (Aman)</p>
                            <h3 class="text-2xl font-bold text-emerald-600 mt-1.5 font-mono tabular-nums">{{ number_format($safeStockCount) }}</h3>
                            <p class="text-xs text-emerald-700 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Di atas batas buffer minimum
                            </p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total Unit Fisik -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-blue-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Unit di Rak Gudang</p>
                            <h3 class="text-2xl font-bold text-blue-600 mt-1.5 font-mono tabular-nums">{{ number_format($totalUnits) }}</h3>
                            <p class="text-xs text-blue-700 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Akumulasi kuantitas fisik suku cadang
                            </p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                
                <!-- Quick Status Filter Tabs -->
                <div class="border-b border-gray-200 bg-gray-50/50 px-5 pt-3 flex flex-wrap items-center gap-2">
                    <a href="{{ route('parts-stock.index', array_merge(request()->query(), ['tab' => 'all', 'page' => 1])) }}"
                       class="px-4 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($tab ?? 'all') == 'all' ? 'border-blue-600 text-blue-600 bg-white rounded-t-lg shadow-sm' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span>Semua Stok (All Stock)</span>
                        <span class="px-2 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($tab ?? 'all') == 'all' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700' }}">
                            {{ number_format($totalParts) }}
                        </span>
                    </a>

                    <a href="{{ route('parts-stock.index', array_merge(request()->query(), ['tab' => 'low', 'page' => 1])) }}"
                       class="px-4 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($tab ?? '') == 'low' ? 'border-rose-600 text-rose-700 bg-white rounded-t-lg shadow-sm' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        <span>Low Stock (Kritis ≤ 2 Pcs)</span>
                        <span class="px-2 py-0.5 rounded text-[11px] font-mono tabular-nums font-bold {{ ($tab ?? '') == 'low' ? 'bg-rose-100 text-rose-800' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                            {{ number_format($lowStockCount) }}
                        </span>
                    </a>

                    <a href="{{ route('parts-stock.index', array_merge(request()->query(), ['tab' => 'safe', 'page' => 1])) }}"
                       class="px-4 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($tab ?? '') == 'safe' ? 'border-emerald-600 text-emerald-700 bg-white rounded-t-lg shadow-sm' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Safe Stock (Stok Aman)</span>
                        <span class="px-2 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($tab ?? '') == 'safe' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-700' }}">
                            {{ number_format($safeStockCount) }}
                        </span>
                    </a>
                </div>

                <!-- Secondary Filter Bar -->
                <div class="p-4 border-b border-gray-200 bg-white flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3">
                    <form method="GET" action="{{ route('parts-stock.index') }}" class="w-full flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3">
                        <input type="hidden" name="tab" value="{{ $tab }}">

                        <div class="flex flex-wrap items-center gap-2.5">
                            <!-- Filter Kategori -->
                            <div class="flex items-center space-x-1.5">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori:</span>
                                <select name="category" class="border-gray-300 rounded-lg py-1.5 px-3 text-xs focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm cursor-pointer font-medium" onchange="this.form.submit()">
                                    <option value="all" {{ ($category ?? 'all') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                                    <option value="general" {{ ($category ?? '') == 'general' ? 'selected' : '' }}>Katalog Gudang (Umum)</option>
                                    <option value="Cooling System" {{ ($category ?? '') == 'Cooling System' ? 'selected' : '' }}>Cooling System</option>
                                    <option value="Ejection System" {{ ($category ?? '') == 'Ejection System' ? 'selected' : '' }}>Ejection System</option>
                                    <option value="Mechanical & Fasteners" {{ ($category ?? '') == 'Mechanical & Fasteners' ? 'selected' : '' }}>Mechanical & Fasteners</option>
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

                            @if(($tab ?? 'all') !== 'all' || ($category ?? 'all') !== 'all' || !empty($search))
                                <a href="{{ route('parts-stock.index') }}" class="px-2.5 py-1.5 text-xs text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg font-semibold inline-flex items-center gap-1 transition">
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
                            <input type="text" name="search" value="{{ $search }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:bg-white block w-full pl-9 pr-8 py-2 shadow-sm transition placeholder:text-gray-400 font-medium" placeholder="Cari Kode Part, Nama, Spesifikasi..." onkeydown="if(event.key === 'Enter') this.form.submit()">
                            @if($search)
                                <a href="{{ route('parts-stock.index', ['tab' => $tab, 'category' => $category]) }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
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
                                <th scope="col" class="px-4 py-3.5 font-semibold border-r border-gray-200">Kode Part</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">Nama Suku Cadang</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">Deskripsi & Spesifikasi</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold text-center border-r border-gray-200">Status Stok</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold text-center border-r border-gray-200 w-32">Stok Aktif</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($parts as $index => $part)
                                @php
                                    $isLowStock = $part->current_stock <= 2;
                                    $borderClass = $isLowStock ? 'border-l-4 border-l-rose-500 bg-rose-50/20' : 'border-l-4 border-l-slate-200 bg-white';
                                @endphp
                                <tr class="{{ $borderClass }} hover:bg-slate-50/90 transition-colors">
                                    <!-- No -->
                                    <td class="px-4 py-3 text-center text-gray-500 border-r border-gray-100 font-mono tabular-nums text-xs">
                                        {{ $parts->firstItem() + $index }}
                                    </td>

                                    <!-- Kode Part -->
                                    <td class="px-4 py-3 border-r border-gray-100 font-mono font-bold text-xs text-gray-900">
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200 inline-block">
                                            {{ $part->part_code ?: 'NO-CODE' }}
                                        </span>
                                    </td>

                                    <!-- Nama Suku Cadang -->
                                    <td class="px-5 py-3 border-r border-gray-100">
                                        <div class="font-bold text-gray-900 text-xs">
                                            {{ $part->name }}
                                        </div>
                                        <div class="flex items-center space-x-2 mt-0.5">
                                            @if($part->category)
                                                <span class="px-1.5 py-0.2 bg-blue-50 text-blue-700 rounded text-[10px] font-semibold border border-blue-100">
                                                    {{ $part->category }}
                                                </span>
                                            @else
                                                <span class="px-1.5 py-0.2 bg-gray-100 text-gray-600 rounded text-[10px] font-medium border border-gray-200">
                                                    Katalog Umum
                                                </span>
                                            @endif

                                            @if($part->dieset)
                                                <span class="text-gray-300">•</span>
                                                <span class="text-[10px] text-gray-500 font-medium">
                                                    Mold: {{ $part->dieset->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Deskripsi & Spesifikasi -->
                                    <td class="px-5 py-3 border-r border-gray-100 text-xs text-gray-600">
                                        <div class="truncate max-w-xs font-medium" title="{{ $part->description }}">
                                            {{ $part->description ?: '-' }}
                                        </div>
                                        @if($part->item_notes)
                                            <div class="text-[10px] text-gray-400 truncate max-w-xs mt-0.5 italic" title="{{ $part->item_notes }}">
                                                {{ $part->item_notes }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Status Stok -->
                                    <td class="px-4 py-3 text-center border-r border-gray-100">
                                        @if($part->current_stock <= 0)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                                Habis (0 Pcs)
                                            </span>
                                        @elseif($part->current_stock <= 2)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-ping"></span>
                                                Kritis (≤ 2 Pcs)
                                            </span>
                                        @elseif($part->current_stock <= 5)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Buffer Menipis
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Aman (Safe)
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Stok Aktif -->
                                    <td class="px-4 py-3 text-center border-r border-gray-100">
                                        <div class="inline-flex items-center justify-center min-w-[3.5rem] px-3 py-1 rounded-lg text-xs font-mono font-bold tabular-nums {{ $isLowStock ? 'bg-rose-100 text-rose-800 border border-rose-300' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}">
                                            {{ number_format($part->current_stock) }} Pcs
                                        </div>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" 
                                                @click="openAdjustModal({ 
                                                    id: {{ $part->id }}, 
                                                    part_code: '{{ addslashes($part->part_code ?? '') }}', 
                                                    name: '{{ addslashes($part->name ?? '') }}', 
                                                    current_stock: {{ $part->current_stock }} 
                                                })"
                                                class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold inline-flex items-center gap-1 transition shadow-xs border border-slate-300"
                                                title="Sesuaikan Jumlah Stok">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Adjust
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                            <p class="font-bold text-gray-800 text-sm">Tidak ada data spare part ditemukan</p>
                                            <p class="text-xs text-gray-400 mt-1">Coba ganti tab kategori atau ubah kata kunci pencarian Anda.</p>
                                            @if(($tab ?? 'all') !== 'all' || ($category ?? 'all') !== 'all' || !empty($search))
                                                <a href="{{ route('parts-stock.index') }}" class="mt-4 px-3.5 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-100 transition inline-flex items-center gap-1.5">
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
                        Menampilkan <span class="font-bold text-gray-900 font-mono tabular-nums">{{ $parts->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-gray-900 font-mono tabular-nums">{{ $parts->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-gray-900 font-mono tabular-nums">{{ number_format($parts->total()) }}</span> item spare part
                    </div>
                    <div>
                        {{ $parts->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: REQUEST ORDER SPV (KIRIM EMAIL) -->
        <div x-show="showMailModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-200" 
                 @click.away="showMailModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="p-1.5 bg-blue-100 text-blue-700 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-bold text-sm">Kirim Laporan Low Stock ke SPV</h3>
                            <p class="text-[11px] text-gray-500">Notifikasi pengadaan dan reorder suku cadang mold kritis</p>
                        </div>
                    </div>
                    <button @click="showMailModal = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('parts-stock.mail') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    
                    <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-start gap-3">
                        <div class="p-1 bg-rose-100 text-rose-700 rounded shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div class="text-xs text-rose-900">
                            <p class="font-bold">Item Kritis yang Perlu Ditindaklanjuti:</p>
                            <p class="mt-0.5">Terdapat <span class="font-bold font-mono">{{ number_format($lowStockCount) }} item</span> spare part yang saat ini memiliki stok ≤ 2 Pcs di gudang.</p>
                        </div>
                    </div>

                    <div class="space-y-2 text-xs">
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider text-[11px]">Tujuan Pengiriman Email Supervisor:</label>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 space-y-1">
                            @foreach($spvEmails as $email)
                                <div class="flex items-center text-gray-700 font-mono text-[11px] gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                    <span>{{ $email }}</span>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-gray-400">Sistem akan menyertakan lampiran file Excel otomatis yang memuat detail seluruh item Low Stock.</p>
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-4 border-t border-gray-100">
                        <button type="button" @click="showMailModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition shadow-sm inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Kirim Email Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: ADJUST STOK PART -->
        <div x-show="showAdjustModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-200" 
                 @click.away="showAdjustModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-2">
                        <div class="p-1.5 bg-slate-800 text-white rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="text-gray-900 font-bold text-sm">Penyesuaian Stok Fisik Part</h3>
                    </div>
                    <button @click="showAdjustModal = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="'{{ url('/parts-stock') }}/' + adjustPartId + '/adjust'" method="POST" class="p-6 space-y-4">
                    @csrf
                    
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-xs">
                        <span class="text-gray-400 uppercase text-[10px] font-semibold block">Informasi Komponen:</span>
                        <div class="font-bold text-gray-900 font-mono mt-0.5" x-text="adjustPartCode"></div>
                        <div class="text-gray-700 font-semibold mt-0.5" x-text="adjustPartName"></div>
                        <div class="mt-2 text-gray-500 flex items-center gap-1.5">
                            <span>Stok Terdaftar Saat Ini:</span>
                            <span class="font-mono font-bold text-gray-900 tabular-nums" x-text="adjustCurrentStock + ' Pcs'"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Jumlah Stok Fisik Baru (Pcs) <span class="text-red-500">*</span></label>
                        <input type="number" min="0" name="current_stock" x-model="adjustNewStock" required class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 font-mono tabular-nums font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Alasan Penyesuaian (Opsional)</label>
                        <input type="text" name="adjustment_reason" placeholder="Contoh: Hasil Stock Opname Shift 1 / Koreksi Pengambilan" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400">
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-3 border-t border-gray-100">
                        <button type="button" @click="showAdjustModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition shadow-sm inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>