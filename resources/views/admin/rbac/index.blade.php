<x-app-layout>
    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        
        editRole: {
            id: '',
            name: '',
            description: '',
            permissions: []
        },
        deleteRole: {
            id: '',
            name: '',
            usersCount: 0
        },

        hasPermission(permId) {
            return this.editRole.permissions.includes(permId);
        },
        togglePermission(permId) {
            const index = this.editRole.permissions.indexOf(permId);
            if (index > -1) {
                this.editRole.permissions.splice(index, 1);
            } else {
                this.editRole.permissions.push(permId);
            }
        },
        selectAllInGroup(permIds) {
            permIds.forEach(id => {
                if (!this.editRole.permissions.includes(id)) {
                    this.editRole.permissions.push(id);
                }
            });
        },
        deselectAllInGroup(permIds) {
            this.editRole.permissions = this.editRole.permissions.filter(id => !permIds.includes(id));
        }
    }">

        <!-- Header -->
        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-2.5 bg-blue-600 rounded-lg text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">Role-Based Access Control (RBAC)</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola data peran (roles), wewenang, dan konfigurasi hak akses permission sistem</p>
                </div>
            </div>
            
            <button @click="showAddModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium flex items-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
                Add New Role
            </button>
        </div>

        <div class="p-6 max-w-7xl mx-auto space-y-6">
            
            <!-- Alerts -->
            @if (session('success'))
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-lg text-sm font-medium border border-emerald-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-rose-50 text-rose-800 p-4 rounded-lg text-sm font-medium border border-rose-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if (isset($errors) && $errors->any())
                <div class="bg-rose-50 text-rose-800 p-4 rounded-lg text-sm border border-rose-200 shadow-sm">
                    <div class="font-semibold mb-1 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Terdapat kesalahan input:
                    </div>
                    <ul class="list-disc pl-9 space-y-1 text-xs">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <!-- Main Table Card -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                
                <!-- Table Controls / Filters -->
                <div class="p-5 border-b border-gray-200 bg-gray-50/70 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <form method="GET" action="{{ route('rbac.index') }}" class="w-full flex flex-col sm:flex-row justify-between items-center gap-4">
                        
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Show</span>
                            <select name="per_page" class="border-gray-300 rounded-lg py-1.5 px-3 text-xs focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm" onchange="this.form.submit()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">entries</span>
                        </div>

                        <div class="relative w-full sm:w-80">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}" class="bg-white border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 py-2 pr-8 shadow-sm" placeholder="Cari nama role atau deskripsi...">
                            @if($search)
                                <a href="{{ route('rbac.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left whitespace-nowrap">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold text-center w-16 border-r border-gray-200">No.</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200 min-w-[180px]">Nama Role</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Deskripsi</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200 w-36">Total Pengguna</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Hak Akses / Permissions</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center w-36">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($roles as $index => $role)
                                <tr class="bg-white hover:bg-blue-50/40 transition-colors group">
                                    <td class="px-6 py-4 text-center text-gray-400 border-r border-gray-100 font-mono">
                                        {{ $roles->firstItem() + $index }}
                                    </td>
                                    
                                    <!-- Nama Role -->
                                    <td class="px-6 py-4 border-r border-gray-100">
                                        <div class="flex items-center space-x-2.5">
                                            <div class="w-7 h-7 rounded-md flex items-center justify-center font-bold text-[11px]
                                                {{ $role->name === 'Admin' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}
                                            ">
                                                {{ strtoupper(substr($role->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <span class="font-bold text-gray-900 text-xs">{{ $role->name }}</span>
                                                @if($role->name === 'Admin')
                                                    <span class="ml-1.5 px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-50 text-red-600 border border-red-200">
                                                        Super Admin
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Deskripsi -->
                                    <td class="px-6 py-4 border-r border-gray-100 text-gray-600 max-w-sm truncate" title="{{ $role->description }}">
                                        {{ $role->description ?? '-' }}
                                    </td>

                                    <!-- Total Pengguna -->
                                    <td class="px-6 py-4 text-center border-r border-gray-100">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $role->users_count > 0 ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $role->users_count }} Pengguna
                                        </span>
                                    </td>

                                    <!-- Permissions -->
                                    <td class="px-6 py-4 border-r border-gray-100">
                                        @if($role->name === 'Admin')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Full System Access (19 Permissions)
                                            </span>
                                        @else
                                            <div class="flex flex-wrap gap-1 max-w-md">
                                                @forelse($role->permissions->take(4) as $p)
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                                        {{ $p->display_name }}
                                                    </span>
                                                @empty
                                                    <span class="text-gray-400 italic text-[11px]">- (Belum ada permission)</span>
                                                @endforelse
                                                @if($role->permissions->count() > 4)
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                        +{{ $role->permissions->count() - 4 }} lainnya
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Action (Edit & Delete) -->
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <button @click="editRole = {
                                                id: {{ $role->id }},
                                                name: '{{ addslashes($role->name) }}',
                                                description: '{{ addslashes($role->description ?? '') }}',
                                                permissions: {{ json_encode($role->permissions->pluck('id')->toArray()) }}
                                            }; showEditModal = true" 
                                                    class="px-2.5 py-1.5 bg-blue-500 text-white rounded text-xs font-medium flex items-center hover:bg-blue-600 shadow-sm transition">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit
                                            </button>
                                            
                                            @if($role->name !== 'Admin')
                                                <button @click="deleteRole = {
                                                    id: {{ $role->id }},
                                                    name: '{{ addslashes($role->name) }}',
                                                    usersCount: {{ $role->users_count }}
                                                }; showDeleteModal = true" 
                                                        class="px-2.5 py-1.5 bg-red-500 text-white rounded text-xs font-medium flex items-center hover:bg-red-600 shadow-sm transition">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Delete
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            <p class="text-gray-500 font-medium text-base">Belum ada data Role</p>
                                            <p class="text-gray-400 text-sm mt-1">Silakan klik tombol "Add New Role" untuk menambahkan peran baru.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-4">
                    <div>
                        Menampilkan <span class="font-bold text-gray-900">{{ $roles->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-gray-900">{{ $roles->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-900">{{ $roles->total() }}</span> total role
                    </div>
                    <div>{{ $roles->links('pagination::tailwind') }}</div>
                </div>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- MODAL TAMBAH ROLE (CREATE) -->
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
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-hidden transform transition-all my-8" 
                 @click.away="showAddModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-slate-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-bold text-base leading-tight">Tambah Data Role Baru</h3>
                            <p class="text-xs text-gray-500">Definisikan nama peran dan pilih izin permission yang diberikan</p>
                        </div>
                    </div>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('rbac.roles.store') }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Role <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" required placeholder="Contoh: Quality Control, Warehouse Lead" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Peran</label>
                                <input type="text" name="description" placeholder="Penjelasan tugas dan wewenang peran ini..." class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <label class="block text-xs font-bold text-gray-800 mb-2 uppercase tracking-wider">Pilih Hak Akses (Permissions):</label>
                            
                            <div class="space-y-4">
                                @foreach($groupedPermissions as $moduleName => $perms)
                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                        <div class="font-bold text-xs text-gray-800 mb-2 flex items-center justify-between border-b border-gray-200 pb-1.5">
                                            <span>{{ $moduleName }}</span>
                                            <span class="text-[10px] text-gray-400 font-normal">({{ $perms->count() }} items)</span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach($perms as $p)
                                                <label class="flex items-start space-x-2 text-xs text-gray-700 cursor-pointer hover:text-blue-700">
                                                    <input type="checkbox" name="permissions[]" value="{{ $p->id }}" class="mt-0.5 w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                                    <div>
                                                        <span class="font-medium block leading-tight">{{ $p->display_name }}</span>
                                                        <span class="text-[10px] text-gray-400 font-mono">{{ $p->name }}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t border-gray-200 space-x-2">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-medium hover:bg-gray-50 transition shadow-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-medium flex items-center hover:bg-blue-700 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Data Role
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- MODAL EDIT ROLE (UPDATE) -->
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
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-hidden transform transition-all my-8" 
                 @click.away="showEditModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-slate-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-bold text-base leading-tight">Edit Data Role</h3>
                            <p class="text-xs text-gray-500">Perbarui informasi role: <span class="font-bold text-gray-800" x-text="editRole.name"></span></p>
                        </div>
                    </div>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="`/rbac/roles/${editRole.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Role <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" x-model="editRole.name" required class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Peran</label>
                                <input type="text" name="description" x-model="editRole.description" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider">Pilih Hak Akses (Permissions):</label>
                                <template x-if="editRole.name === 'Admin'">
                                    <span class="text-xs text-red-600 font-semibold bg-red-50 px-2 py-0.5 rounded border border-red-200">
                                        Super Admin memiliki hak akses penuh otomatis
                                    </span>
                                </template>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach($groupedPermissions as $moduleName => $perms)
                                    @php
                                        $groupPermIds = $perms->pluck('id')->toArray();
                                    @endphp
                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                        <div class="font-bold text-xs text-gray-800 mb-2 flex items-center justify-between border-b border-gray-200 pb-1.5">
                                            <span>{{ $moduleName }}</span>
                                            <div class="flex space-x-2 text-[10px]">
                                                <button type="button" @click="selectAllInGroup({{ json_encode($groupPermIds) }})" class="text-blue-600 hover:underline">Pilih Semua</button>
                                                <span class="text-gray-300">|</span>
                                                <button type="button" @click="deselectAllInGroup({{ json_encode($groupPermIds) }})" class="text-rose-600 hover:underline">Hapus Semua</button>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach($perms as $p)
                                                <label class="flex items-start space-x-2 text-xs text-gray-700 cursor-pointer hover:text-blue-700">
                                                    <input type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $p->id }}" 
                                                           :checked="hasPermission({{ $p->id }})"
                                                           @change="togglePermission({{ $p->id }})"
                                                           class="mt-0.5 w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                                    <div>
                                                        <span class="font-medium block leading-tight">{{ $p->display_name }}</span>
                                                        <span class="text-[10px] text-gray-400 font-mono">{{ $p->name }}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t border-gray-200 space-x-2">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-medium hover:bg-gray-50 transition shadow-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-medium flex items-center hover:bg-blue-700 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- MODAL HAPUS ROLE (DELETE) -->
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
                    <h3 class="text-base font-bold text-gray-900 mb-1.5">Hapus Data Role?</h3>
                    <p class="mb-5 text-xs text-gray-500 leading-relaxed">
                        Anda yakin ingin menghapus role <span class="font-bold text-gray-800" x-text="deleteRole.name"></span>?<br>
                        Data role yang dihapus tidak dapat dikembalikan.
                    </p>
                    
                    <div class="flex justify-center space-x-2">
                        <button @click="showDeleteModal = false" class="text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-xs font-medium px-4 py-2 transition shadow-sm">Batal</button>
                        <form :action="`/rbac/roles/${deleteRole.id}`" method="POST" class="inline-flex">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white bg-rose-600 hover:bg-rose-700 rounded-lg text-xs font-medium px-4 py-2 transition shadow-sm">
                                Ya, Hapus Role
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
