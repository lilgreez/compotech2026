<x-app-layout>
    <div x-data="{ 
        currentView: 'table',
        showDeleteModal: false,
        formData: { id: '', nik: '', name: '', department: '' },
        
        openEdit(operator) {
            this.formData = { 
                id: operator.id, 
                nik: operator.nik, 
                name: operator.name, 
                department: operator.department 
            };
            this.currentView = 'edit';
        },
        openDelete(operator) {
            this.formData = { id: operator.id, name: operator.name };
            this.showDeleteModal = true;
        }
    }">

        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-2.5 bg-indigo-600 rounded-lg text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">Manajemen Operator</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola data dan akses akun operator mesin</p>
                </div>
            </div>
            
            <div x-show="currentView === 'table'" x-transition.opacity>
                <button @click="currentView = 'add'" class="w-full md:w-auto px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium flex items-center justify-center hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
                    Tambah Operator
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

            <div x-show="currentView === 'table'" x-transition.opacity class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden" style="display: none;">
                
                <div class="p-5 border-b border-gray-200 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <form method="GET" action="{{ route('operators.index') }}" class="w-full flex flex-col sm:flex-row justify-between items-center gap-4">
                        
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 whitespace-nowrap">Show</span>
                            <select name="per_page" class="w-20 border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 bg-white shadow-sm" onchange="this.form.submit()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span class="text-sm text-gray-600 whitespace-nowrap">entries</span>
                        </div>

                        <div class="relative w-full sm:w-72">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 py-2 pr-3 shadow-sm transition-shadow" placeholder="Cari NIK atau Nama..." onchange="this.form.submit()">
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200 w-16">No.</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">NIK (Username)</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Full Name</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Sub Dept</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center w-40">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($operators as $index => $operator)
                                <tr class="bg-white hover:bg-blue-50/50 transition-colors group">
                                    <td class="px-6 py-4 text-center text-gray-500 border-r border-gray-100">{{ $operators->firstItem() + $index }}</td>
                                    
                                    <td class="px-6 py-4 text-gray-900 border-r border-gray-100 font-bold">
                                        <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md text-xs border border-gray-200">{{ $operator->nik ?? '-' }}</span>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-gray-800 border-r border-gray-100 font-medium flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs mr-3">
                                            {{ substr($operator->name, 0, 2) }}
                                        </div>
                                        {{ $operator->name }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-gray-600 border-r border-gray-100">{{ $operator->department ?? '-' }}</td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                            <button @click="openEdit({{ $operator->toJson() }})" class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-md text-xs font-medium flex items-center transition-colors border border-blue-200 hover:border-blue-600">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit
                                            </button>
                                            <button @click="openDelete({{ $operator->toJson() }})" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-md text-xs font-medium flex items-center transition-colors border border-red-200 hover:border-red-600">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            </div>
                                            <p class="text-gray-500 font-medium text-base">Belum ada data Operator.</p>
                                            <p class="text-gray-400 text-sm mt-1">Silakan klik tombol "Tambah Operator" di atas.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($operators->hasPages() || $operators->total() > 0)
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-500 gap-4">
                        <div>
                            Showing <span class="font-medium text-gray-900">{{ $operators->firstItem() ?? 0 }}</span> to <span class="font-medium text-gray-900">{{ $operators->lastItem() ?? 0 }}</span> of <span class="font-medium text-gray-900">{{ $operators->total() }}</span> entries
                        </div>
                        <div>
                            {{ $operators->appends(request()->query())->links('pagination::tailwind') }}
                        </div>
                    </div>
                @endif
            </div>

            <div x-show="currentView === 'add'" x-transition.opacity class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden" style="display: none;" x-cloak>
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50 flex items-center">
                    <button @click="currentView = 'table'" class="mr-4 text-gray-400 hover:text-indigo-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </button>
                    <h3 class="text-gray-800 font-bold text-lg">Tambah Operator Baru</h3>
                </div>
                
                <form action="{{ route('operators.store') }}" method="POST" class="p-6">
                    @csrf
                    <div class="max-w-3xl space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">NIK (Username) <span class="text-red-500">*</span></label>
                                <input type="text" name="nik" required placeholder="Contoh: 123456" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                            </div>
                            
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required placeholder="Masukkan nama operator..." class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sub Departemen <span class="text-red-500">*</span></label>
                                <input type="text" name="department" value="Die Cast" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition bg-gray-50 text-gray-600">
                                <p class="text-xs text-gray-500 mt-1">Default departemen saat ini adalah Die Cast.</p>
                            </div>

                            <div class="sm:col-span-1 border-t border-gray-100 pt-6 mt-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required placeholder="••••••••" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                            </div>
                            
                            <div class="sm:col-span-1 border-t border-gray-100 pt-6 mt-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                                <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex justify-end space-x-3 mt-8">
                            <button type="button" @click="currentView = 'table'" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold flex items-center hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Operator
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div x-show="currentView === 'edit'" x-transition.opacity class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden" style="display: none;" x-cloak>
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50 flex items-center">
                    <button @click="currentView = 'table'" class="mr-4 text-gray-400 hover:text-indigo-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </button>
                    <h3 class="text-gray-800 font-bold text-lg">Edit Data Operator</h3>
                </div>

                <form :action="`/operators/${formData.id}`" method="POST" class="p-6">
                    @csrf @method('PUT')
                    <div class="max-w-3xl space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">NIK (Username) <span class="text-red-500">*</span></label>
                                <input type="text" name="nik" x-model="formData.nik" required class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition bg-gray-50 text-gray-600" readonly>
                                <p class="text-[11px] text-gray-500 mt-1">NIK tidak dapat diubah karena merupakan username.</p>
                            </div>
                            
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="formData.name" required class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sub Departemen <span class="text-red-500">*</span></label>
                                <input type="text" name="department" x-model="formData.department" required class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                            </div>

                            <div class="sm:col-span-1 border-t border-gray-100 pt-6 mt-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Baru</label>
                                <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                            </div>
                            
                            <div class="sm:col-span-1 border-t border-gray-100 pt-6 mt-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" placeholder="Kosongkan jika tidak diubah" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                            </div>
                            
                            <div class="sm:col-span-2 bg-orange-50 border border-orange-100 rounded-lg p-3 flex items-start">
                                <svg class="w-5 h-5 text-orange-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-xs text-orange-800">Biarkan kolom password kosong jika Anda hanya ingin memperbarui data profil tanpa mengganti password login.</p>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex justify-end space-x-3 mt-8">
                            <button type="button" @click="currentView = 'table'" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold flex items-center hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div x-show="showDeleteModal" 
                 class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
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
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Akun Operator?</h3>
                        <p class="mb-6 text-sm text-gray-500">Anda yakin ingin menghapus akun operator <span class="font-bold text-gray-800" x-text="formData.name"></span>? Akses login mereka akan dicabut permanen.</p>
                        
                        <div class="flex justify-center space-x-3">
                            <button @click="showDeleteModal = false" class="text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-sm font-medium px-5 py-2.5 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                            <form :action="`/operators/${formData.id}`" method="POST" class="inline-flex">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-white bg-red-600 hover:bg-red-700 rounded-lg text-sm font-medium px-5 py-2.5 focus:ring-4 focus:ring-red-200 transition shadow-sm">
                                    Ya, Hapus Akun
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>