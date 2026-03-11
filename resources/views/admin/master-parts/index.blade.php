<x-app-layout>
    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        formData: { id: '', code: '', name: '', desc: '', max_shoot: '', notes: '' },
        imagePreview: null,
        
        fileChosen(event) {
            this.fileToDataUrl(event, src => this.imagePreview = src)
        },
        fileToDataUrl(event, callback) {
            if (! event.target.files.length) return
            let file = event.target.files[0], reader = new FileReader()
            reader.readAsDataURL(file)
            reader.onload = e => callback(e.target.result)
        },
        openEdit(part) {
            this.formData = { id: part.id, code: part.part_code, name: part.name, desc: part.description, max_shoot: part.max_shoot, notes: part.item_notes };
            this.imagePreview = part.image_path ? '/storage/' + part.image_path : null;
            this.showEditModal = true;
        }
    }">

        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-2.5 bg-indigo-600 rounded-lg text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">Master Katalog Parts</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola data master komponen dan spesifikasinya</p>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <form action="{{ route('master-parts.sync') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="w-full md:w-auto px-4 py-2 bg-orange-500 text-white rounded-lg text-sm font-medium flex items-center justify-center hover:bg-orange-600 focus:ring-4 focus:ring-orange-100 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> 
                        Sync from Wings
                    </button>
                </form>
                
                <button @click="imagePreview = null; showAddModal = true" class="w-full md:w-auto px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium flex items-center justify-center hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
                    Insert Data
                </button>
            </div>
        </div>

        <div class="p-6 max-w-7xl mx-auto">
            @if (session('success'))
                <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-lg text-sm font-medium border border-green-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-lg text-sm border border-red-200 shadow-sm">
                    <div class="font-semibold mb-1 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Terdapat kesalahan input:
                    </div>
                    <ul class="list-disc pl-9 space-y-1">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                
                <div class="p-5 border-b border-gray-200 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <form method="GET" action="{{ route('master-parts.index') }}" class="w-full flex flex-col sm:flex-row justify-between items-center gap-4">
                        
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 whitespace-nowrap">Show</span>
                            <select name="per_page" class="w-20 border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 bg-white shadow-sm" onchange="this.form.submit()">
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
                            <input type="text" name="search" value="{{ $search }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 py-2 pr-3 shadow-sm transition-shadow" placeholder="Cari Part..." onchange="this.form.submit()">
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200 w-16">No.</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200 text-center">Item Image</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Item Code</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Item Name</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Item Desc</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-right border-r border-gray-200">Max Shoot</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Item Notes</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center min-w-[140px]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($parts as $index => $part)
                                <tr class="bg-white hover:bg-blue-50/50 transition-colors group">
                                    <td class="px-6 py-4 text-center text-gray-500 border-r border-gray-100">{{ $parts->firstItem() + $index }}</td>
                                    
                                    <td class="px-6 py-3 text-center border-r border-gray-100">
                                        <div class="flex justify-center items-center">
                                            @if($part->image_path)
                                                <img src="{{ asset('storage/'.$part->image_path) }}" alt="image" class="h-10 w-14 object-cover rounded border border-gray-200 shadow-sm transition-transform transform group-hover:scale-110">
                                            @else
                                                <div class="flex flex-col items-center justify-center h-10 w-14 bg-gray-50 rounded border border-gray-200 border-dashed">
                                                    <span class="text-[9px] text-gray-400 italic">No img</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-gray-900 border-r border-gray-100 font-bold">{{ $part->part_code }}</td>
                                    <td class="px-6 py-4 text-gray-800 border-r border-gray-100 font-medium">{{ $part->name }}</td>
                                    <td class="px-6 py-4 text-gray-600 border-r border-gray-100 truncate max-w-[200px]" title="{{ $part->description }}">{{ $part->description ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right text-indigo-600 font-semibold border-r border-gray-100">{{ number_format($part->max_shoot) }}</td>
                                    <td class="px-6 py-4 text-gray-500 border-r border-gray-100 italic text-xs truncate max-w-[150px]" title="{{ $part->item_notes }}">{{ $part->item_notes ?? '-' }}</td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <button @click="openEdit({{ $part->toJson() }})" class="px-3 py-1.5 bg-blue-500 text-white rounded text-xs font-medium flex items-center hover:bg-blue-600 shadow-sm transition">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit
                                            </button>
                                            <button @click="formData = { id: {{ $part->id }}, code: '{{ addslashes($part->part_code) }}' }; showDeleteModal = true" class="px-3 py-1.5 bg-red-500 text-white rounded text-xs font-medium flex items-center hover:bg-red-600 shadow-sm transition">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <p class="text-gray-500 font-medium text-base">Belum ada data Katalog Parts.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-500 gap-4">
                    <div>Showing <span class="font-medium text-gray-900">{{ $parts->firstItem() ?? 0 }}</span> to <span class="font-medium text-gray-900">{{ $parts->lastItem() ?? 0 }}</span> of <span class="font-medium text-gray-900">{{ $parts->total() }}</span> entries</div>
                    <div>{{ $parts->appends(request()->query())->links('pagination::tailwind') }}</div>
                </div>
            </div>
        </div>

        <div x-show="showAddModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all flex flex-col max-h-full" 
                 @click.away="showAddModal = false"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-gray-800 font-bold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Parts Baru
                    </h3>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('master-parts.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Item Code <span class="text-red-500">*</span></label>
                            <input type="text" name="part_code" required placeholder="Contoh: A800737" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Item Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="Contoh: ANGULAR PIN" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Item Description</label>
                            <input type="text" name="description" placeholder="Deskripsi part..." class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                        </div>
                        
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Max Shoot <span class="text-red-500">*</span></label>
                            <input type="number" name="max_shoot" required placeholder="Batas maksimal shoot" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Item Notes (Opsional)</label>
                            <textarea name="item_notes" rows="2" placeholder="Catatan tambahan..." class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition"></textarea>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Foto Part</label>
                            <div class="flex items-start space-x-4">
                                <div class="flex-1">
                                    <input type="file" name="image" id="fileUploadAdd" class="hidden" accept="image/*" @change="fileChosen">
                                    <div class="relative rounded-lg shadow-sm">
                                        <input type="text" readonly placeholder="Klik tombol di samping untuk memilih gambar" class="w-full border-gray-300 bg-gray-50 rounded-lg text-sm py-2 pl-3 pr-24 text-gray-500 focus:ring-0 cursor-not-allowed" :value="imagePreview ? 'Gambar dipilih' : ''">
                                        <button type="button" onclick="document.getElementById('fileUploadAdd').click()" class="absolute inset-y-0 right-0 px-4 text-sm font-medium text-white bg-indigo-500 rounded-r-lg hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-indigo-300">
                                            Browse
                                        </button>
                                    </div>
                                    <p class="mt-1 text-[11px] text-gray-500">Maks. 2MB. Format: JPG, PNG.</p>
                                </div>
                                <div x-show="imagePreview" style="display: none;" class="flex-shrink-0">
                                    <img :src="imagePreview" class="h-16 w-20 object-cover rounded-lg border border-gray-200 shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-4 border-t border-gray-100 flex justify-end space-x-3">
                        <button type="button" @click="showAddModal = false" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold flex items-center hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showEditModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all flex flex-col max-h-full" 
                 @click.away="showEditModal = false"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-gray-800 font-bold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Data Parts
                    </h3>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="`/master-parts/${formData.id}`" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Item Code</label>
                            <input type="text" name="part_code" x-model="formData.code" required class="w-full border-gray-300 bg-gray-100 rounded-lg text-sm py-2 px-3 text-gray-500 cursor-not-allowed shadow-sm" readonly>
                            <p class="text-[10px] text-gray-400 mt-1">Item Code terkunci.</p>
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Item Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="formData.name" required class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Item Description</label>
                            <input type="text" name="description" x-model="formData.desc" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                        </div>
                        
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Max Shoot <span class="text-red-500">*</span></label>
                            <input type="number" name="max_shoot" x-model="formData.max_shoot" required class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Item Notes (Opsional)</label>
                            <textarea name="item_notes" x-model="formData.notes" rows="2" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition"></textarea>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Update Foto Part</label>
                            <div class="flex items-start space-x-4">
                                <div class="flex-1">
                                    <input type="file" name="image" id="fileUploadEdit" class="hidden" accept="image/*" @change="fileChosen">
                                    <div class="relative rounded-lg shadow-sm">
                                        <input type="text" readonly placeholder="Pilih gambar baru..." class="w-full border-gray-300 bg-gray-50 rounded-lg text-sm py-2 pl-3 pr-24 text-gray-500 focus:ring-0 cursor-not-allowed" :value="imagePreview ? 'Gambar siap diupload' : ''">
                                        <button type="button" onclick="document.getElementById('fileUploadEdit').click()" class="absolute inset-y-0 right-0 px-4 text-sm font-medium text-white bg-indigo-500 rounded-r-lg hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-indigo-300">
                                            Browse
                                        </button>
                                    </div>
                                    <p class="mt-1 text-[11px] text-gray-500">Abaikan jika tidak ingin mengubah gambar.</p>
                                </div>
                                <div x-show="imagePreview" style="display: none;" class="flex-shrink-0">
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

        <div x-show="showDeleteModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all" 
                 @click.away="showDeleteModal = false"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-5">
                        <svg class="text-red-600 w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Part Katalog?</h3>
                    <p class="mb-6 text-sm text-gray-500">Anda yakin ingin menghapus kode part <span class="font-bold text-gray-800" x-text="formData.code"></span>? Data yang dihapus tidak dapat dikembalikan.</p>
                    
                    <div class="flex justify-center space-x-3">
                        <button @click="showDeleteModal = false" class="text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-sm font-medium px-5 py-2.5 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                        <form :action="`/master-parts/${formData.id}`" method="POST" class="inline-flex">
                            @csrf @method('DELETE')
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