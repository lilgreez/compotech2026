<x-app-layout>
    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        editData: { id: '', name: '', product_code: '', description: '', total_shoot: '', production_year: '' },
        deleteData: { id: '', name: '' }
    }">
    
        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-2.5 bg-blue-600 rounded-lg text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">Master DieSet</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola data master dieset dan informasi produk</p>
                </div>
            </div>
            
            <button @click="showAddModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium flex items-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
                Add New Dieset
            </button>
        </div>

        <div class="p-6 max-w-7xl mx-auto">
            @if (session('success'))
                <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-lg text-sm font-medium border border-green-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-lg text-sm font-medium border border-red-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-lg text-sm border border-red-200 shadow-sm">
                    <div class="font-semibold mb-1 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Terdapat kesalahan input:
                    </div>
                    <ul class="list-disc pl-9 space-y-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                
                <div class="p-5 border-b border-gray-200 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <form method="GET" action="{{ route('master-diesets.index') }}" class="w-full flex flex-col sm:flex-row justify-between items-center gap-4">
                        
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 whitespace-nowrap">Show</span>
                            <select name="per_page" class="w-20 border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm" onchange="this.form.submit()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span class="text-sm text-gray-600 whitespace-nowrap">entries</span>
                        </div>
                        
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 py-2 pr-3 shadow-sm" placeholder="Cari Dieset..." onchange="this.form.submit()">
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold text-center w-12 border-r border-gray-200">No.</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-left border-r border-gray-200">Dieset Name</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200">Product Code</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-left border-r border-gray-200">Product Name</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200">Actual Shoot (Pcs)</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200">Marked Year</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center min-w-[140px]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($diesets as $index => $dieset)
                                <tr class="bg-white hover:bg-blue-50/50 transition-colors group">
                                    <td class="px-6 py-4 text-center text-gray-500 border-r border-gray-100">{{ $diesets->firstItem() + $index }}</td>
                                    
                                    <td class="px-6 py-4 font-medium text-gray-900 border-r border-gray-100">
                                        <a href="{{ route('master-diesets.show', $dieset->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center">
                                            {{ $dieset->name }}
                                            <svg class="w-3 h-3 ml-1 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center text-gray-600 border-r border-gray-100">
                                        <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md text-xs font-medium border border-gray-200">{{ $dieset->product_code ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 truncate max-w-xs border-r border-gray-100" title="{{ $dieset->description }}">{{ $dieset->description ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center text-gray-700 font-medium border-r border-gray-100">{{ number_format($dieset->total_shoot) }}</td>
                                    <td class="px-6 py-4 text-center text-gray-600 border-r border-gray-100">{{ $dieset->production_year ?? '-' }}</td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <button @click="editData = { id: {{ $dieset->id }}, name: '{{ addslashes($dieset->name) }}', product_code: '{{ addslashes($dieset->product_code) }}', description: '{{ addslashes($dieset->description) }}', total_shoot: '{{ $dieset->total_shoot }}', production_year: '{{ $dieset->production_year }}' }; showEditModal = true" 
                                                    class="px-3 py-1.5 bg-blue-500 text-white rounded text-xs font-medium flex items-center hover:bg-blue-600 shadow-sm transition">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit
                                            </button>
                                            
                                            <button @click="deleteData = { id: {{ $dieset->id }}, name: '{{ addslashes($dieset->name) }}' }; showDeleteModal = true" 
                                                    class="px-3 py-1.5 bg-red-500 text-white rounded text-xs font-medium flex items-center hover:bg-red-600 shadow-sm transition">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <p class="text-gray-500 font-medium text-base">Belum ada data Master Dieset</p>
                                            <p class="text-gray-400 text-sm mt-1">Silakan klik tombol "Add New Dieset" untuk menambahkan data.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-500 gap-4">
                    <div>Showing <span class="font-medium text-gray-900">{{ $diesets->firstItem() ?? 0 }}</span> to <span class="font-medium text-gray-900">{{ $diesets->lastItem() ?? 0 }}</span> of <span class="font-medium text-gray-900">{{ $diesets->total() }}</span> entries</div>
                    <div>{{ $diesets->appends(request()->query())->links('pagination::tailwind') }}</div>
                </div>
            </div>
        </div>

        <div x-show="showAddModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm" 
             style="display: none;" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden transform transition-all" 
                 @click.away="showAddModal = false"
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-gray-800 font-bold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Data Dieset
                    </h3>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('master-diesets.store') }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Dieset Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required placeholder="Contoh: Mold A" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Product Code</label>
                                <input type="text" name="product_code" placeholder="Contoh: PRD-001" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Marked Year</label>
                                <input type="number" name="production_year" placeholder="Contoh: 2024" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-shadow">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Product Name / Description</label>
                                <input type="text" name="description" placeholder="Deskripsi produk..." class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-shadow">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Actual Shoot (Pcs)</label>
                                <input type="number" name="total_shoot" placeholder="0" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-shadow">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t border-gray-200 space-x-3">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium flex items-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showEditModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden transform transition-all" 
                 @click.away="showEditModal = false"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-gray-800 font-bold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Data Dieset
                    </h3>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="`/master-diesets/${editData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Dieset Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="editData.name" required class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Product Code</label>
                                <input type="text" name="product_code" x-model="editData.product_code" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Marked Year</label>
                                <input type="number" name="production_year" x-model="editData.production_year" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Product Name / Description</label>
                                <input type="text" name="description" x-model="editData.description" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Actual Shoot (Pcs)</label>
                                <input type="number" name="total_shoot" x-model="editData.total_shoot" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t border-gray-200 space-x-3">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium flex items-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showDeleteModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden transform transition-all" 
                 @click.away="showDeleteModal = false"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-5">
                        <svg class="text-red-600 w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Dieset?</h3>
                    <p class="mb-6 text-sm text-gray-500">Anda yakin ingin menghapus data <span class="font-bold text-gray-800" x-text="deleteData.name"></span>? Data yang dihapus tidak dapat dikembalikan.</p>
                    
                    <div class="flex justify-center space-x-3">
                        <button @click="showDeleteModal = false" class="text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-sm font-medium px-5 py-2.5 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                        <form :action="`/master-diesets/${deleteData.id}`" method="POST" class="inline-flex">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white bg-red-600 hover:bg-red-700 rounded-lg text-sm font-medium px-5 py-2.5 focus:ring-4 focus:ring-red-200 transition shadow-sm">
                                Ya, Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>