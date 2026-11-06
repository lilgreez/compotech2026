<x-app-layout>
    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        showImportModal: false,
        formData: { id: '', code: '', name: '', desc: '', stock: 0, max_shoot: 3000000, notes: '' },
        imagePreview: null,
        
        fileChosen(event) {
            this.fileToDataUrl(event, src => this.imagePreview = src)
        },
        fileToDataUrl(event, callback) {
            if (!event.target.files.length) return
            let file = event.target.files[0], reader = new FileReader()
            reader.readAsDataURL(file)
            reader.onload = e => callback(e.target.result)
        },
        openEdit(part) {
            this.formData = { 
                id: part.id, 
                code: part.part_code || '', 
                name: part.name || '', 
                desc: part.description || part.name || '', 
                stock: part.current_stock || 0,
                max_shoot: part.max_shoot || 3000000, 
                notes: part.item_notes || '' 
            };
            this.imagePreview = part.image_path ? '/storage/' + part.image_path : null;
            this.showEditModal = true;
        },
        openDelete(part) {
            this.formData = { 
                id: part.id, 
                code: part.part_code || '', 
                name: part.name || '' 
            };
            this.showDeleteModal = true;
        }
    }">

        <!-- Mission Control Page Header -->
        <div class="px-6 py-5 bg-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="flex items-center space-x-3.5 min-w-0">
                    <div class="p-2.5 bg-slate-900 rounded-xl text-white shadow-xs ring-1 ring-slate-800 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h2 class="font-extrabold text-xl text-slate-900 tracking-tight leading-tight">Master Parts</h2>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                </span>
                                Katalog Terintegrasi : <span class="font-mono tabular-nums font-bold">{{ number_format($totalParts) }}</span> Parts
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1 max-w-xl leading-relaxed">Database master suku cadang mold, kontrol kuantitas stok gudang, dan parameter lifecycle max shoot</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2 sm:gap-2.5 w-full sm:w-auto justify-start sm:justify-end shrink-0 flex-nowrap overflow-x-auto pb-1 sm:pb-0">
                    <button type="button" @click="showImportModal = true" class="h-9 px-3.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg text-xs font-semibold inline-flex items-center justify-center gap-2 shadow-2xs hover:border-slate-400 focus:ring-4 focus:ring-slate-100 transition-all cursor-pointer whitespace-nowrap shrink-0">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        <span>Upload Excel</span>
                    </button>

                    <form action="{{ route('master-parts.sync') }}" method="POST" class="m-0 p-0 flex shrink-0">
                        @csrf
                        <button type="submit" class="h-9 px-3.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-semibold inline-flex items-center justify-center gap-2 shadow-2xs focus:ring-4 focus:ring-amber-100 transition-all cursor-pointer whitespace-nowrap">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> 
                            <span>Sync from Wings</span>
                        </button>
                    </form>
                    
                    <button @click="imagePreview = null; formData = { id: '', code: '', name: '', desc: '', stock: 0, max_shoot: 3000000, notes: '' }; showAddModal = true" class="h-9 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold inline-flex items-center justify-center gap-2 shadow-xs focus:ring-4 focus:ring-blue-100 transition-all cursor-pointer whitespace-nowrap shrink-0">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
                        <span>Tambah Part</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6 max-w-7xl mx-auto space-y-6">
            
            <!-- Alert Notifications -->
            @if (session('success'))
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl text-xs font-semibold border border-emerald-200 flex items-center shadow-2xs">
                    <svg class="w-5 h-5 mr-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 text-rose-800 p-4 rounded-xl text-xs border border-rose-200 shadow-2xs">
                    <div class="font-bold mb-1 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Terdapat kesalahan input:
                    </div>
                    <ul class="list-disc pl-9 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- KPI Metric Cards (Slate & Steel) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Total Master Items -->
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs hover:border-slate-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Master Parts</p>
                        <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 font-mono tabular-nums tracking-tight">{{ number_format($totalParts) }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Item katalog aktif</p>
                    </div>
                </div>

                <!-- Card 2: Total Stock Qty -->
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs hover:border-blue-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Kuantitas Stok</p>
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-2xl sm:text-3xl font-black text-blue-600 font-mono tabular-nums tracking-tight">{{ number_format($totalStock) }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Akumulasi seluruh part</p>
                    </div>
                </div>

                <!-- Card 3: Safe Stock (> 2) -->
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs hover:border-emerald-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Stok Aman (> 2)</p>
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-2xl sm:text-3xl font-black text-emerald-600 font-mono tabular-nums tracking-tight">{{ number_format($safeStock) }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Level cadangan aman</p>
                    </div>
                </div>

                <!-- Card 4: Critical Stock (<= 2) -->
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs hover:border-rose-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Stok Kritis (<= 2)</p>
                        <div class="w-10 h-10 rounded-lg {{ $criticalStock > 0 ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-2xl sm:text-3xl font-black {{ $criticalStock > 0 ? 'text-rose-600' : 'text-slate-900' }} font-mono tabular-nums tracking-tight">{{ number_format($criticalStock) }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Perlu restock segera</p>
                    </div>
                </div>
            </div>

            <!-- Table Container (Slate & Steel) -->
            <div class="bg-white shadow-2xs rounded-xl border border-slate-200 overflow-hidden">
                
                <!-- Quick Status Filter Pills Header -->
                <div class="px-5 pt-4 pb-3 border-b border-slate-200 bg-slate-50/60 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center space-x-1.5 overflow-x-auto py-0.5">
                        <a href="{{ route('master-parts.index', array_merge(request()->except(['stock_filter', 'page']), ['stock_filter' => 'all'])) }}" 
                           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 {{ ($stockFilter ?? 'all') === 'all' ? 'bg-slate-900 text-white shadow-2xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                            Semua Part
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono {{ ($stockFilter ?? 'all') === 'all' ? 'bg-slate-800 text-slate-200' : 'bg-slate-100 text-slate-600' }}">{{ number_format($totalParts) }}</span>
                        </a>
                        <a href="{{ route('master-parts.index', array_merge(request()->except(['stock_filter', 'page']), ['stock_filter' => 'safe'])) }}" 
                           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 {{ ($stockFilter ?? '') === 'safe' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-white text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200' }}">
                            Stok Aman (> 2)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono {{ ($stockFilter ?? '') === 'safe' ? 'bg-emerald-700 text-emerald-100' : 'bg-emerald-50 text-emerald-700' }}">{{ number_format($safeStock) }}</span>
                        </a>
                        <a href="{{ route('master-parts.index', array_merge(request()->except(['stock_filter', 'page']), ['stock_filter' => 'critical'])) }}" 
                           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 {{ ($stockFilter ?? '') === 'critical' ? 'bg-rose-600 text-white shadow-2xs' : 'bg-white text-slate-600 hover:bg-rose-50 hover:text-rose-700 border border-slate-200' }}">
                            Stok Kritis (<= 2)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono {{ ($stockFilter ?? '') === 'critical' ? 'bg-rose-700 text-rose-100' : 'bg-rose-50 text-rose-700' }}">{{ number_format($criticalStock) }}</span>
                        </a>
                    </div>

                    @if($search || ($stockFilter && $stockFilter !== 'all'))
                        <a href="{{ route('master-parts.index') }}" class="px-2.5 py-1.5 text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors border border-rose-200 inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Reset Filter
                        </a>
                    @endif
                </div>

                <!-- Table Controls & Search -->
                <div class="p-4 border-b border-slate-200 bg-white">
                    <form method="GET" action="{{ route('master-parts.index') }}" class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3">
                        @if($stockFilter && $stockFilter !== 'all')
                            <input type="hidden" name="stock_filter" value="{{ $stockFilter }}">
                        @endif

                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-bold uppercase text-slate-500">Show:</span>
                            <select name="per_page" class="border-slate-300 rounded-lg py-1.5 px-3 text-xs font-semibold focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-white shadow-2xs cursor-pointer" onchange="this.form.submit()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        
                        <!-- Search Box -->
                        <div class="relative w-full md:w-80">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}" class="bg-white border border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 pr-8 py-2 shadow-2xs transition" placeholder="Cari Item No. atau Deskripsi..." onkeydown="if(event.key === 'Enter') this.form.submit()">
                            @if($search)
                                <a href="{{ route('master-parts.index', array_merge(request()->except('search'))) }}" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 hover:text-slate-600">
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
                                <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-slate-200 w-14">No.</th>
                                <th scope="col" class="px-4 py-3.5 font-bold border-r border-slate-200">Item No. / Part Code</th>
                                <th scope="col" class="px-4 py-3.5 font-bold border-r border-slate-200">Description / Part Name</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-slate-200">Cumulative Stock</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-right border-r border-slate-200">Max Shoot</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-slate-200">Foto</th>
                                <th scope="col" class="px-4 py-3.5 font-bold border-r border-slate-200">Notes</th>
                                <th scope="col" class="px-4 py-3.5 font-bold text-center min-w-[120px]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($parts as $index => $part)
                                <tr class="bg-white hover:bg-slate-50/80 transition-colors {{ $part->current_stock <= 2 ? 'bg-rose-50/15' : '' }}">
                                    <!-- No. -->
                                    <td class="px-4 py-3 text-center text-slate-400 border-r border-slate-100 font-mono tabular-nums">
                                        {{ $parts->firstItem() + $index }}
                                    </td>
                                    
                                    <!-- Item No / Code -->
                                    <td class="px-4 py-3 border-r border-slate-100">
                                        <span class="font-mono font-bold text-slate-800 tracking-tight text-xs bg-slate-100 border border-slate-200 px-2 py-0.5 rounded shadow-2xs inline-block">
                                            {{ $part->part_code }}
                                        </span>
                                    </td>

                                    <!-- Description / Name -->
                                    <td class="px-4 py-3 border-r border-slate-100">
                                        <div class="font-bold text-slate-900">{{ $part->name }}</div>
                                        @if($part->description && $part->description !== $part->name)
                                            <div class="text-[11px] text-slate-500 mt-0.5 truncate max-w-xs" title="{{ $part->description }}">{{ $part->description }}</div>
                                        @endif
                                    </td>

                                    <!-- Cumulative Stock -->
                                    <td class="px-4 py-3 text-center border-r border-slate-100">
                                        @if($part->current_stock <= 2)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold font-mono tabular-nums bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                                {{ number_format($part->current_stock) }} pcs
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold font-mono tabular-nums bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                                {{ number_format($part->current_stock) }} pcs
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Max Shoot -->
                                    <td class="px-4 py-3 text-right border-r border-slate-100 font-mono font-bold text-slate-700 tabular-nums">
                                        {{ number_format($part->max_shoot) }}
                                    </td>

                                    <!-- Foto -->
                                    <td class="px-4 py-2.5 text-center border-r border-slate-100">
                                        @if($part->image_path)
                                            <div class="flex justify-center items-center">
                                                <img src="{{ asset('storage/'.$part->image_path) }}" alt="{{ $part->part_code }}" class="h-8 w-11 object-cover rounded border border-slate-200 shadow-2xs hover:scale-110 transition-transform cursor-pointer">
                                            </div>
                                        @else
                                            <span class="text-[10px] text-slate-400 italic">No image</span>
                                        @endif
                                    </td>

                                    <!-- Notes -->
                                    <td class="px-4 py-3 text-slate-500 border-r border-slate-100 text-xs truncate max-w-[180px]" title="{{ $part->item_notes }}">
                                        {{ $part->item_notes ?: '-' }}
                                    </td>
                                    
                                    <!-- Action -->
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center items-center space-x-1.5">
                                            <button type="button" @click="openEdit({{ $part->toJson() }})" class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors border border-transparent hover:border-amber-200" title="Edit Part">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                            <button type="button" @click="openDelete({{ $part->toJson() }})" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors border border-transparent hover:border-rose-200" title="Hapus Part">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-14 text-center">
                                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                            <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-3 border border-slate-200">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            </div>
                                            <h4 class="text-sm font-bold text-slate-900">Tidak ada data Master Parts</h4>
                                            <p class="text-xs text-slate-500 mt-1 mb-3">
                                                @if($search || ($stockFilter && $stockFilter !== 'all'))
                                                    Tidak ditemukan part dengan kata kunci atau filter yang Anda tentukan.
                                                @else
                                                    Belum ada katalog spare part yang tersimpan di sistem.
                                                @endif
                                            </p>
                                            @if($search || ($stockFilter && $stockFilter !== 'all'))
                                                <a href="{{ route('master-parts.index') }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                                                    Reset Filter
                                                </a>
                                            @else
                                                <button type="button" @click="showImportModal = true" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                                                    Upload File Excel
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/70 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                    <div>
                        Menampilkan <span class="font-bold text-slate-900 font-mono tabular-nums">{{ $parts->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-900 font-mono tabular-nums">{{ $parts->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-slate-900 font-mono tabular-nums">{{ number_format($parts->total()) }}</span> parts
                    </div>
                    <div>
                        {{ $parts->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 1: TAMBAH PART BARU                                -->
        <!-- ======================================================== -->
        <div x-show="showAddModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]" 
                 @click.away="showAddModal = false"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-gray-900 font-bold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Master Part Baru
                    </h3>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('master-parts.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Item No. / Part Code <span class="text-red-500">*</span></label>
                            <input type="text" name="part_code" required placeholder="Contoh: AM01.0001.00" class="w-full font-mono border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Part / Description <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="Contoh: Neeple 1/2''" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                        </div>
                        
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Kuantitas Stok (Cumulative Qty) <span class="text-red-500">*</span></label>
                            <input type="number" name="current_stock" required min="0" value="0" class="w-full tabular-nums border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                        </div>

                        <div class="sm:col-span-1">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Max Shoot <span class="text-red-500">*</span></label>
                            <input type="number" name="max_shoot" required min="0" value="3000000" class="w-full tabular-nums border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Deskripsi Lengkap (Opsional)</label>
                            <input type="text" name="description" placeholder="Rincian spesifikasi..." class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Item Notes / Keterangan (Opsional)</label>
                            <textarea name="item_notes" rows="2" placeholder="Catatan tambahan spare part..." class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition"></textarea>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Foto Part (Opsional)</label>
                            <div class="flex items-start space-x-4">
                                <div class="flex-1">
                                    <input type="file" name="image" id="fileUploadAdd" class="hidden" accept="image/*" @change="fileChosen">
                                    <div class="relative rounded-lg shadow-sm">
                                        <input type="text" readonly placeholder="Pilih foto part..." class="w-full border-gray-300 bg-gray-50 rounded-lg text-sm py-2 pl-3 pr-24 text-gray-500 focus:ring-0 cursor-not-allowed" :value="imagePreview ? 'Foto telah dipilih' : ''">
                                        <button type="button" onclick="document.getElementById('fileUploadAdd').click()" class="absolute inset-y-0 right-0 px-4 text-sm font-medium text-white bg-slate-700 rounded-r-lg hover:bg-slate-800 focus:ring-4 focus:ring-slate-300">
                                            Pilih File
                                        </button>
                                    </div>
                                    <p class="mt-1 text-[11px] text-gray-500">Maks. 2MB. Format: JPG, PNG, WEBP.</p>
                                </div>
                                <div x-show="imagePreview" style="display: none;" class="shrink-0">
                                    <img :src="imagePreview" class="h-16 w-20 object-cover rounded-lg border border-gray-200 shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-4 border-t border-gray-100 flex justify-end space-x-3">
                        <button type="button" @click="showAddModal = false" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-bold flex items-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Part
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 2: EDIT PART                                       -->
        <!-- ======================================================== -->
        <div x-show="showEditModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]" 
                 @click.away="showEditModal = false"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-gray-900 font-bold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Data Master Part
                    </h3>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="`/master-parts/${formData.id}`" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Item No. / Part Code <span class="text-red-500">*</span></label>
                            <input type="text" name="part_code" x-model="formData.code" required class="w-full font-mono border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Part / Description <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="formData.name" required class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                        </div>
                        
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Kuantitas Stok (Cumulative Qty) <span class="text-red-500">*</span></label>
                            <input type="number" name="current_stock" x-model="formData.stock" required min="0" class="w-full tabular-nums border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                        </div>

                        <div class="sm:col-span-1">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Max Shoot <span class="text-red-500">*</span></label>
                            <input type="number" name="max_shoot" x-model="formData.max_shoot" required min="0" class="w-full tabular-nums border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Deskripsi Lengkap</label>
                            <input type="text" name="description" x-model="formData.desc" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Item Notes / Keterangan</label>
                            <textarea name="item_notes" x-model="formData.notes" rows="2" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition"></textarea>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Update Foto Part</label>
                            <div class="flex items-start space-x-4">
                                <div class="flex-1">
                                    <input type="file" name="image" id="fileUploadEdit" class="hidden" accept="image/*" @change="fileChosen">
                                    <div class="relative rounded-lg shadow-sm">
                                        <input type="text" readonly placeholder="Pilih foto baru..." class="w-full border-gray-300 bg-gray-50 rounded-lg text-sm py-2 pl-3 pr-24 text-gray-500 focus:ring-0 cursor-not-allowed" :value="imagePreview ? 'Foto baru siap diupload' : ''">
                                        <button type="button" onclick="document.getElementById('fileUploadEdit').click()" class="absolute inset-y-0 right-0 px-4 text-sm font-medium text-white bg-slate-700 rounded-r-lg hover:bg-slate-800 focus:ring-4 focus:ring-slate-300">
                                            Pilih File
                                        </button>
                                    </div>
                                    <p class="mt-1 text-[11px] text-gray-500">Abaikan jika tidak ingin mengganti foto part saat ini.</p>
                                </div>
                                <div x-show="imagePreview" style="display: none;" class="shrink-0">
                                    <img :src="imagePreview" class="h-16 w-20 object-cover rounded-lg border border-gray-200 shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-4 border-t border-gray-100 flex justify-end space-x-3">
                        <button type="button" @click="showEditModal = false" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-bold flex items-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 3: HAPUS PART                                      -->
        <!-- ======================================================== -->
        <div x-show="showDeleteModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-sm overflow-hidden transform transition-all" 
                 @click.away="showDeleteModal = false">
                
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center w-14 h-14 rounded-full bg-red-100 mb-4">
                        <svg class="text-red-600 w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1.5">Hapus Master Part?</h3>
                    <p class="mb-5 text-sm text-gray-500">
                        Anda yakin ingin menghapus part <span class="font-mono font-bold text-gray-900" x-text="formData.code"></span> (<span x-text="formData.name"></span>)? Data yang telah dihapus tidak dapat dipulihkan.
                    </p>
                    
                    <div class="flex justify-center space-x-3">
                        <button type="button" @click="showDeleteModal = false" class="text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-sm font-medium px-4 py-2 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                        <form :action="`/master-parts/${formData.id}`" method="POST" class="inline-flex">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-white bg-red-600 hover:bg-red-700 rounded-lg text-sm font-bold px-4 py-2 focus:ring-4 focus:ring-red-200 transition shadow-sm">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 4: UPLOAD EXCEL DATA                               -->
        <!-- ======================================================== -->
        <div x-show="showImportModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all" 
                 @click.away="showImportModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-gray-900 font-bold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Upload & Import Berkas Excel
                    </h3>
                    <button @click="showImportModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('master-parts.import-excel') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    
                    <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-3.5 text-xs text-blue-800">
                        <div class="font-bold flex items-center mb-1 text-blue-900">
                            <svg class="w-4 h-4 mr-1.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Format Berkas Yang Didukung
                        </div>
                        <p class="leading-relaxed">Sesuai format Excel <span class="font-semibold">Stock Spare Part (Master)</span>: Kolom 1 (No), Kolom 2 (Item No.), Kolom 3 (Description), Kolom 4 (Cumulative Qty).</p>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Pilih File Excel (.xlsx, .xls, .csv)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition bg-gray-50/50">
                            <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <input type="file" name="excel_file" id="excel_file" required accept=".xlsx,.xls,.csv" class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <p class="text-[11px] text-gray-400 mt-2">Maksimal ukuran file: 20MB</p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="showImportModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold flex items-center hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-200 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Mulai Import
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>