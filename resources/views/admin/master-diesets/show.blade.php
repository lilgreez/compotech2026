<x-app-layout>
    <div x-data="{
        showGenerateModal: false,
        showEditPartModal: false,
        showDeletePartModal: false,
        
        // --- State untuk Modal Generate Parts (Step 1 & 2) ---
        step: 1,
        categoryName: '',
        maxCavity: 2,
        rows: [],
        catalogSearch: '',
        // Data Simulasi dari 'Wings' Sesuai PDF Halaman 22
        simulatedCatalog: [
            { code: 'A800737', name: 'AP16-79.6-N34-A30-TC9.1', desc: 'ANGULAR PIN' },
            { code: 'A021493', name: 'S381-1X6.5X15X6', desc: 'COMPRESSION SPRING' },
            { code: 'A878663', name: '3DH18-3CBA7F-SB141', desc: 'CORE BLOCK' },
            { code: 'A891088', name: '3DCH-A085-SB248', desc: 'CORE BLOCK' }
        ],
        get filteredCatalog() {
            if (this.catalogSearch === '') return this.simulatedCatalog;
            return this.simulatedCatalog.filter(item => 
                item.code.toLowerCase().includes(this.catalogSearch.toLowerCase()) || 
                item.name.toLowerCase().includes(this.catalogSearch.toLowerCase())
            );
        },
        generateRows() {
            if (!this.categoryName || this.maxCavity < 1) return;
            this.rows = [];
            for (let i = 1; i <= this.maxCavity; i++) {
                this.rows.push({ cavity: i, code: '', name: '', desc: '', shoot: 0 });
            }
            this.step = 2; // Pindah ke Step 2 (Form Input & Tabel)
        },
        applyCatalogItem(item) {
            // Auto-fill semua baris saat tombol '+' di-klik
            this.rows.forEach(row => {
                row.code = item.code;
                row.name = item.name;
                row.desc = item.desc;
            });
        },
        resetGenerateModal() {
            this.step = 1;
            this.categoryName = '';
            this.maxCavity = 2;
            this.rows = [];
            this.catalogSearch = '';
            this.showGenerateModal = false;
        },

        // --- State untuk Edit & Delete Part ---
        editPart: { id: '', code: '', name: '', desc: '', shoot: 0 },
        deletePart: { id: '', code: '' }
    }">

        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('master-diesets.index') }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div class="p-2.5 bg-indigo-600 rounded-lg text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">Detail Item Parts</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola konfigurasi komponen untuk dieset ini</p>
                </div>
            </div>
            
            <button @click="showGenerateModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium flex items-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
                Add Category Part
            </button>
        </div>

        <div class="p-6 max-w-7xl mx-auto">
            
            @if (session('success'))
                <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-lg text-sm font-medium border border-green-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50/50 border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $dieset->name }}</h3>
                        <div class="mt-1 flex items-center text-sm text-gray-600 space-x-4">
                            <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> {{ $dieset->product_code }}</span>
                            <span class="text-gray-300">|</span>
                            <span>{{ $dieset->description }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Active Dieset
                        </span>
                        <p class="text-xs text-gray-500 mt-1">Total Shoot: {{ number_format($dieset->total_shoot) }}</p>
                    </div>
                </div>
                
                <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4 border-b border-gray-100">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600 whitespace-nowrap">Show</span>
                        <select class="w-20 border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        <span class="text-sm text-gray-600 whitespace-nowrap">entries</span>
                    </div>
                    
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 py-2 pr-3" placeholder="Cari Part...">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Category</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Parts</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Desc</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200 w-20">Cavity</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-right border-r border-gray-200 w-32">Achieve Shoot</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-right border-r border-gray-200 w-32">Max Shoot</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center min-w-[150px]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($groupedParts as $category => $parts)
                                @foreach($parts as $index => $part)
                                    <tr class="bg-white hover:bg-blue-50/50 transition-colors group">
                                        @if($index === 0)
                                            <td class="px-6 py-4 font-bold text-gray-900 border-r border-gray-200 border-b-0 align-top bg-gray-50/80" rowspan="{{ count($parts) }}">
                                                {{ $category }}
                                            </td>
                                        @endif
                                        
                                        <td class="px-6 py-4 border-r border-gray-100">
                                            <span class="font-medium text-gray-900">{{ $part->part_code }}</span> 
                                            <span class="text-gray-500 mx-1">-</span> 
                                            <span class="text-gray-700">{{ $part->name }}</span>
                                        </td>
                                        <td class="px-6 py-4 border-r border-gray-100 text-gray-600 truncate max-w-[200px]" title="{{ $part->description }}">{{ $part->description }}</td>
                                        <td class="px-6 py-4 text-center border-r border-gray-100 font-medium text-gray-700">
                                            <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-gray-200">{{ $part->cavity_number }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right border-r border-gray-100 font-bold text-indigo-600">{{ number_format($part->actual_shoot) }}</td>
                                        <td class="px-6 py-4 text-right border-r border-gray-100 text-gray-600">{{ number_format($part->max_shoot) }}</td>
                                        
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center space-x-2">
                                                <button @click="editPart = { id: {{ $part->id }}, code: '{{ addslashes($part->part_code) }}', name: '{{ addslashes($part->name) }}', desc: '{{ addslashes($part->description) }}', shoot: {{ $part->actual_shoot }} }; showEditPartModal = true" 
                                                        class="px-3 py-1.5 bg-blue-500 text-white rounded text-xs font-medium flex items-center hover:bg-blue-600 shadow-sm transition">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit
                                                </button>
                                                <button @click="deletePart = { id: {{ $part->id }}, code: '{{ addslashes($part->part_code) }}' }; showDeletePartModal = true" 
                                                        class="px-3 py-1.5 bg-red-500 text-white rounded text-xs font-medium flex items-center hover:bg-red-600 shadow-sm transition">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Delete
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            </div>
                                            <p class="text-gray-500 font-medium text-base">Belum ada Parts di Dieset ini.</p>
                                            <p class="text-gray-400 text-sm mt-1">Silakan klik tombol "Add Category Part" di atas.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(count($groupedParts) > 0)
                <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 text-xs text-gray-500">
                    Menampilkan data per kategori part.
                </div>
                @endif
            </div>
        </div>

        <div x-show="showGenerateModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/70 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            
            <div :class="step === 1 ? 'max-w-md' : 'max-w-5xl'" class="bg-white rounded-xl shadow-2xl w-full overflow-hidden flex flex-col transform transition-all duration-300" @click.away="resetGenerateModal()" style="max-height: 90vh;"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                
                <div class="px-6 py-4 border-b border-gray-200 bg-white flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Generate Parts
                            <span x-show="step===2" class="mx-2 text-gray-300">|</span>
                            <span x-show="step===2" class="text-blue-600 text-sm font-semibold bg-blue-50 px-2 py-1 rounded border border-blue-100" x-text="categoryName"></span>
                        </h3>
                    </div>
                    <button @click="resetGenerateModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="overflow-y-auto p-6 bg-gray-50/50 flex-1">
                    
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">
                        
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm text-blue-800">Tentukan nama kategori part dan jumlah cavity untuk di-generate secara otomatis.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category Name <span class="text-red-500">*</span></label>
                            <input type="text" x-model="categoryName" placeholder="Contoh: ANGULAR PIN" class="w-full border-gray-300 rounded-lg py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Maximum Cavity <span class="text-red-500">*</span></label>
                            <input type="number" x-model="maxCavity" min="1" max="100" class="w-full border-gray-300 rounded-lg py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                        </div>
                        
                        <div class="pt-2 flex justify-start">
                            <button @click="generateRows()" class="px-6 py-2.5 bg-orange-500 text-white font-bold rounded-lg shadow-sm hover:bg-orange-600 focus:ring-4 focus:ring-orange-200 transition flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> 
                                Generate
                            </button>
                        </div>
                    </div>

                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="flex flex-col lg:flex-row gap-6">
                        
                        <div class="lg:w-2/3 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h4 class="font-semibold text-gray-700 text-sm">Input Parts Data</h4>
                            </div>
                            
                            <form id="generateForm" action="{{ route('master-diesets.generate-parts', $dieset->id) }}" method="POST" class="flex-1 overflow-auto p-4">
                                @csrf
                                <input type="hidden" name="category" :value="categoryName">
                                
                                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                                    <table class="w-full text-sm text-left">
                                        <thead class="bg-gray-100 text-gray-600 border-b border-gray-200 text-xs uppercase">
                                            <tr>
                                                <th class="p-3 border-r border-gray-200 text-center w-16">Cavity</th>
                                                <th class="p-3 border-r border-gray-200">Parts Code</th>
                                                <th class="p-3 border-r border-gray-200">Parts Name</th>
                                                <th class="p-3 border-r border-gray-200">Desc</th>
                                                <th class="p-3 text-right">Shoot Limit</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            <template x-for="(row, index) in rows" :key="index">
                                                <tr class="hover:bg-blue-50/30 transition-colors">
                                                    <td class="p-2 border-r border-gray-100 text-center font-bold text-gray-700 bg-gray-50">
                                                        <input type="hidden" :name="`parts[${index}][cavity]`" :value="row.cavity">
                                                        <span x-text="row.cavity"></span>
                                                    </td>
                                                    <td class="p-2 border-r border-gray-100">
                                                        <input type="text" :name="`parts[${index}][code]`" x-model="row.code" required placeholder="Code..." class="w-full text-sm border-gray-300 rounded-md py-1.5 px-2.5 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                                    </td>
                                                    <td class="p-2 border-r border-gray-100">
                                                        <input type="text" :name="`parts[${index}][name]`" x-model="row.name" required placeholder="Name..." class="w-full text-sm border-gray-300 rounded-md py-1.5 px-2.5 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                                    </td>
                                                    <td class="p-2 border-r border-gray-100">
                                                        <input type="text" :name="`parts[${index}][desc]`" x-model="row.desc" placeholder="Desc..." class="w-full text-sm border-gray-300 rounded-md py-1.5 px-2.5 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                                    </td>
                                                    <td class="p-2">
                                                        <input type="number" :name="`parts[${index}][shoot]`" x-model="row.shoot" required min="0" class="w-24 text-sm border-gray-300 rounded-md py-1.5 px-2.5 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-right">
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <p class="text-xs text-gray-500 mt-3 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Anda bisa mengetik manual atau menggunakan Master Catalog di sebelah kanan.
                                </p>
                            </form>
                        </div>

                        <div class="lg:w-1/3 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-[500px]">
                            <div class="bg-gray-800 px-4 py-3 text-white">
                                <h4 class="font-semibold text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    Master Catalog (Wings)
                                </h4>
                            </div>
                            
                            <div class="p-4 border-b border-gray-100 bg-gray-50">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" x-model="catalogSearch" placeholder="Cari Kode atau Nama Part..." class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block pl-10 p-2 shadow-sm">
                                </div>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto p-2 space-y-2 bg-gray-50/50">
                                <template x-for="item in filteredCatalog" :key="item.code">
                                    <div class="group flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:border-blue-300 hover:shadow-md transition-all cursor-default">
                                        <div class="flex-1 mr-3">
                                            <p class="font-bold text-gray-900 text-sm" x-text="item.code"></p>
                                            <p class="text-xs text-gray-600 font-medium mt-0.5" x-text="item.name"></p>
                                            <p class="text-[11px] text-gray-400 italic mt-1" x-text="item.desc"></p>
                                        </div>
                                        <button type="button" @click="applyCatalogItem(item)" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-full hover:bg-blue-600 hover:text-white border border-blue-200 transition-colors shadow-sm shrink-0 tooltip" title="Terapkan ke semua baris">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                    </div>
                                </template>
                                
                                <div x-show="filteredCatalog.length === 0" class="text-center py-8 text-gray-500 text-sm">
                                    Tidak ada part yang cocok.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end items-center space-x-3">
                    <button x-show="step === 2" type="button" @click="step = 1" class="mr-auto px-4 py-2 text-gray-600 hover:text-gray-800 font-medium text-sm flex items-center transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Kembali
                    </button>
                    
                    <button type="button" @click="resetGenerateModal()" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                    
                    <button x-show="step === 2" type="submit" form="generateForm" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-bold flex items-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Semua Parts
                    </button>
                </div>
            </div>
        </div>

        <div x-show="showEditPartModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all" 
                 @click.away="showEditPartModal = false"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-gray-800 font-bold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Data Part
                    </h3>
                    <button @click="showEditPartModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="`/master-diesets/{{ $dieset->id }}/parts/${editPart.id}`" method="POST">
                    @csrf @method('PUT')
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Part Code</label>
                            <input type="text" name="part_code" x-model="editPart.code" required class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-gray-50" readonly>
                            <p class="text-xs text-gray-500 mt-1">Part code tidak dapat diubah setelah di-generate.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Part Name</label>
                            <input type="text" name="name" x-model="editPart.name" required class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                            <input type="text" name="description" x-model="editPart.desc" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Maximum / Actual Shoot Limit</label>
                            <div class="relative">
                                <input type="number" name="actual_shoot" x-model="editPart.shoot" required class="w-full border-orange-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 shadow-sm bg-orange-50 font-bold text-orange-700">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t border-gray-200 space-x-3">
                        <button type="button" @click="showEditPartModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium flex items-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showDeletePartModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden transform transition-all" 
                 @click.away="showDeletePartModal = false"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-5">
                        <svg class="text-red-600 w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Part?</h3>
                    <p class="mb-6 text-sm text-gray-500">Anda yakin ingin menghapus part kode <span class="font-bold text-gray-800" x-text="deletePart.code"></span> dari Dieset ini? Aksi ini tidak dapat dibatalkan.</p>
                    
                    <div class="flex justify-center space-x-3">
                        <button @click="showDeletePartModal = false" class="text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-sm font-medium px-5 py-2.5 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                        <form :action="`/master-diesets/{{ $dieset->id }}/parts/${deletePart.id}`" method="POST" class="inline-flex">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-white bg-red-600 hover:bg-red-700 rounded-lg text-sm font-medium px-5 py-2.5 focus:ring-4 focus:ring-red-200 transition shadow-sm">
                                Ya, Hapus Part
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</x-app-layout>