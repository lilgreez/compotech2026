<x-app-layout>
    <div x-data="{
        activeTab: '{{ $activeTab }}',
        showAddRoleModal: false,
        showEditRoleModal: false,
        showEditUserRoleModal: false,
        showDeleteRoleModal: false,
        
        editRole: {
            id: '',
            name: '',
            description: '',
            permissions: []
        },
        editUser: {
            id: '',
            name: '',
            email: '',
            nik: '',
            department: '',
            roles: []
        },
        deleteRole: {
            id: '',
            name: '',
            usersCount: 0
        },

        // Helper to check if a permission is selected in editRole
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
        selectAllPermissionsInGroup(permIds) {
            permIds.forEach(id => {
                if (!this.editRole.permissions.includes(id)) {
                    this.editRole.permissions.push(id);
                }
            });
        },
        deselectAllPermissionsInGroup(permIds) {
            this.editRole.permissions = this.editRole.permissions.filter(id => !permIds.includes(id));
        }
    }">

        <!-- Page Header -->
        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-2.5 bg-blue-600 rounded-lg text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">Role-Based Access Control (RBAC)</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola peran pengguna (roles), hak akses permission sistem, dan matriks wewenang operasional</p>
                </div>
            </div>
            
            <button @click="showAddRoleModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium flex items-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
                Tambah Role Baru
            </button>
        </div>

        <div class="p-6 max-w-7xl mx-auto space-y-6">

            <!-- KPI Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Roles</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_roles']) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pengguna</p>
                        <p class="text-2xl font-bold text-indigo-600 mt-1">{{ number_format($stats['total_users']) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Permissions Sistem</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($stats['total_permissions']) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Super Admin Active</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($stats['admin_users']) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </div>
            </div>

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

            <!-- Tab Navigation Header -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="flex border-b border-gray-200 bg-gray-50/70 px-4 pt-3 space-x-6 text-sm font-semibold">
                    <button type="button" 
                            @click="activeTab = 'roles'" 
                            :class="activeTab === 'roles' ? 'border-b-2 border-blue-600 text-blue-600 pb-3 font-bold' : 'text-gray-500 hover:text-gray-800 pb-3'"
                            class="flex items-center space-x-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Daftar Roles & Hak Akses</span>
                        <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">{{ $roles->count() }}</span>
                    </button>

                    <button type="button" 
                            @click="activeTab = 'users'" 
                            :class="activeTab === 'users' ? 'border-b-2 border-blue-600 text-blue-600 pb-3 font-bold' : 'text-gray-500 hover:text-gray-800 pb-3'"
                            class="flex items-center space-x-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>Penugasan Role User</span>
                        <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs bg-indigo-100 text-indigo-800">{{ $users->total() }}</span>
                    </button>

                    <button type="button" 
                            @click="activeTab = 'matrix'" 
                            :class="activeTab === 'matrix' ? 'border-b-2 border-blue-600 text-blue-600 pb-3 font-bold' : 'text-gray-500 hover:text-gray-800 pb-3'"
                            class="flex items-center space-x-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                        <span>Matriks Hak Akses (Matrix)</span>
                    </button>
                </div>

                <!-- =============================================================== -->
                <!-- TAB 1: DAFTAR ROLES & HAK AKSES -->
                <!-- =============================================================== -->
                <div x-show="activeTab === 'roles'" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($roles as $role)
                            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col justify-between">
                                <div>
                                    <!-- Role Header -->
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm
                                                {{ $role->name === 'Admin' ? 'bg-red-50 text-red-600 border border-red-200' : '' }}
                                                {{ $role->name === 'Supervisor' ? 'bg-indigo-50 text-indigo-600 border border-indigo-200' : '' }}
                                                {{ $role->name === 'Maintenance' ? 'bg-sky-50 text-sky-600 border border-sky-200' : '' }}
                                                {{ $role->name === 'Operator' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : '' }}
                                                {{ !in_array($role->name, ['Admin', 'Supervisor', 'Maintenance', 'Operator']) ? 'bg-purple-50 text-purple-600 border border-purple-200' : '' }}
                                            ">
                                                {{ strtoupper(substr($role->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center space-x-2">
                                                    <h3 class="text-base font-bold text-gray-900">{{ $role->name }}</h3>
                                                    @if(in_array($role->name, ['Admin', 'Supervisor', 'Maintenance', 'Operator']))
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                                            Core System
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-purple-100 text-purple-700 border border-purple-200">
                                                            Custom Role
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500 mt-0.5">{{ $role->description ?? 'Tidak ada deskripsi tambahan.' }}</p>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                {{ $role->users_count }} Pengguna
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Permissions Badges Preview -->
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                                Hak Akses ({{ $role->name === 'Admin' ? 'Semua Hak Akses' : $role->permissions->count() . ' Permissions' }}):
                                            </span>
                                            <span class="text-[11px] text-gray-400">
                                                {{ $role->name === 'Admin' ? 'Superuser Bypass' : '' }}
                                            </span>
                                        </div>

                                        <div class="flex flex-wrap gap-1.5 max-h-28 overflow-y-auto pr-1">
                                            @if($role->name === 'Admin')
                                                <span class="px-2 py-1 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Full System Permissions (Bypass Semua Modul)
                                                </span>
                                            @else
                                                @forelse($role->permissions as $p)
                                                    <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-700 border border-gray-200" title="{{ $p->description }}">
                                                        {{ $p->display_name }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-gray-400 italic">Belum ada permission yang ditugaskan ke role ini.</span>
                                                @endforelse
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-5 pt-3 border-t border-gray-100 flex items-center justify-between">
                                    <div class="text-[11px] text-gray-400 font-mono">
                                        ID Role: #{{ $role->id }}
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button @click="editRole = {
                                            id: {{ $role->id }},
                                            name: '{{ addslashes($role->name) }}',
                                            description: '{{ addslashes($role->description ?? '') }}',
                                            permissions: {{ json_encode($role->permissions->pluck('id')->toArray()) }}
                                        }; showEditRoleModal = true" 
                                                class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-xs font-medium flex items-center transition shadow-sm">
                                            <svg class="w-3.5 h-3.5 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Kelola Permissions
                                        </button>

                                        @if(!in_array($role->name, ['Admin', 'Supervisor', 'Maintenance', 'Operator']))
                                            <button @click="deleteRole = {
                                                id: {{ $role->id }},
                                                name: '{{ addslashes($role->name) }}',
                                                usersCount: {{ $role->users_count }}
                                            }; showDeleteRoleModal = true" 
                                                    class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg border border-transparent hover:border-rose-200 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- =============================================================== -->
                <!-- TAB 2: PENUGASAN ROLE PENGGUNA (USER ROLE ASSIGNMENT) -->
                <!-- =============================================================== -->
                <div x-show="activeTab === 'users'" class="p-6 space-y-4" style="display: none;">
                    
                    <!-- Search Controls -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <form method="GET" action="{{ route('rbac.index') }}" class="w-full sm:w-80">
                            <input type="hidden" name="tab" value="users">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search_user" value="{{ $search }}" class="bg-white border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 py-2 pr-8 shadow-sm" placeholder="Cari nama, NIK, email, departemen...">
                                @if($search)
                                    <a href="{{ route('rbac.index', ['tab' => 'users']) }}" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </a>
                                @endif
                            </div>
                        </form>
                        <div class="text-xs text-gray-500">
                            Total Pengguna: <span class="font-bold text-gray-900">{{ $users->total() }}</span> akun terdaftar
                        </div>
                    </div>

                    <!-- User Roles Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-xs text-left whitespace-nowrap">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-4 py-3 font-semibold text-center w-12 border-r border-gray-200">No.</th>
                                    <th scope="col" class="px-4 py-3 font-semibold border-r border-gray-200">Pengguna / Akun</th>
                                    <th scope="col" class="px-4 py-3 font-semibold border-r border-gray-200">NIK / Departemen</th>
                                    <th scope="col" class="px-4 py-3 font-semibold border-r border-gray-200">Peran Aktif (Role)</th>
                                    <th scope="col" class="px-4 py-3 font-semibold text-center w-28">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($users as $index => $u)
                                    <tr class="bg-white hover:bg-blue-50/40 transition-colors">
                                        <td class="px-4 py-3 text-center text-gray-400 border-r border-gray-100 font-mono">
                                            {{ $users->firstItem() + $index }}
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-100">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs border border-slate-200 flex-shrink-0">
                                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900 leading-tight">{{ $u->name }}</p>
                                                    <p class="text-[11px] text-gray-500 font-mono">{{ $u->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-100 text-gray-600">
                                            <p class="font-mono font-medium">{{ $u->nik ?? '-' }}</p>
                                            <p class="text-[11px] text-gray-400">{{ $u->department ?? 'General' }}</p>
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-100">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse($u->roles as $r)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold
                                                        {{ $r->name === 'Admin' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                                                        {{ $r->name === 'Supervisor' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : '' }}
                                                        {{ $r->name === 'Maintenance' ? 'bg-sky-50 text-sky-700 border border-sky-200' : '' }}
                                                        {{ $r->name === 'Operator' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                                        {{ !in_array($r->name, ['Admin', 'Supervisor', 'Maintenance', 'Operator']) ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}
                                                    ">
                                                        {{ $r->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-gray-400 italic text-[11px]">Belum memiliki role</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button @click="editUser = {
                                                id: {{ $u->id }},
                                                name: '{{ addslashes($u->name) }}',
                                                email: '{{ addslashes($u->email) }}',
                                                nik: '{{ addslashes($u->nik ?? '') }}',
                                                department: '{{ addslashes($u->department ?? '') }}',
                                                roles: {{ json_encode($u->roles->pluck('id')->toArray()) }}
                                            }; showEditUserRoleModal = true" 
                                                    class="px-2.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-xs font-semibold border border-blue-200 transition">
                                                Ubah Role
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-xs">
                                            Tidak ditemukan pengguna yang sesuai pencarian.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pt-2">
                        {{ $users->links('pagination::tailwind') }}
                    </div>
                </div>

                <!-- =============================================================== -->
                <!-- TAB 3: MATRIKS HAK AKSES (PERMISSION MATRIX) -->
                <!-- =============================================================== -->
                <div x-show="activeTab === 'matrix'" class="p-6" style="display: none;">
                    
                    <form action="{{ route('rbac.matrix.sync') }}" method="POST">
                        @csrf
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Matriks Hak Akses Antar Peran</h3>
                                <p class="text-xs text-gray-500">Centang kotak untuk memberikan izin permission pada masing-masing role secara langsung</p>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition shadow-sm flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Perubahan Matriks
                            </button>
                        </div>

                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="w-full text-xs text-left whitespace-nowrap">
                                <thead class="text-xs text-gray-600 uppercase bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th scope="col" class="px-4 py-3.5 font-bold border-r border-gray-200 bg-gray-100 min-w-[280px]">
                                            Modul & Nama Hak Akses (Permission)
                                        </th>
                                        @foreach($roles as $role)
                                            <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-gray-200 min-w-[120px]">
                                                <div class="flex flex-col items-center">
                                                    <span class="text-xs font-bold text-gray-900">{{ $role->name }}</span>
                                                    <span class="text-[10px] text-gray-400 normal-case font-normal">({{ $role->users_count }} user)</span>
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupedPermissions as $moduleName => $perms)
                                        <!-- Group Header -->
                                        <tr class="bg-slate-100 font-bold text-slate-800 border-t border-b border-slate-200">
                                            <td colspan="{{ 1 + $roles->count() }}" class="px-4 py-2 text-[11px] uppercase tracking-wider flex items-center">
                                                <span class="w-2 h-2 rounded-full bg-blue-600 mr-2"></span>
                                                {{ $moduleName }} ({{ $perms->count() }} Permissions)
                                            </td>
                                        </tr>

                                        @foreach($perms as $perm)
                                            <tr class="bg-white hover:bg-blue-50/30 transition-colors border-b border-gray-100">
                                                <td class="px-4 py-2.5 border-r border-gray-100">
                                                    <p class="font-semibold text-gray-900 leading-tight">{{ $perm->display_name }}</p>
                                                    <p class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $perm->name }}</p>
                                                </td>

                                                @foreach($roles as $role)
                                                    <td class="px-4 py-2.5 text-center border-r border-gray-100">
                                                        @if($role->name === 'Admin')
                                                            <span class="inline-flex items-center text-emerald-600 font-bold" title="Super Admin Bypass (Selalu Aktif)">
                                                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                            </span>
                                                        @else
                                                            <input type="checkbox" 
                                                                   name="matrix[{{ $role->id }}][]" 
                                                                   value="{{ $perm->id }}"
                                                                   {{ $role->permissions->contains('id', $perm->id) ? 'checked' : '' }}
                                                                   class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition shadow-sm flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Perubahan Matriks
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- MODAL TAMBAH ROLE BARU -->
        <!-- =================================================================== -->
        <div x-show="showAddRoleModal" 
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
                 @click.away="showAddRoleModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-slate-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-bold text-base leading-tight">Tambah Role Baru</h3>
                            <p class="text-xs text-gray-500">Definisikan nama role dan pilih izin permission yang diberikan</p>
                        </div>
                    </div>
                    <button @click="showAddRoleModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
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
                            <label class="block text-xs font-bold text-gray-800 mb-2 uppercase tracking-wider">Pilih Permissions untuk Role Ini:</label>
                            
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
                        <button type="button" @click="showAddRoleModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-medium hover:bg-gray-50 transition shadow-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-medium flex items-center hover:bg-blue-700 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Role
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- MODAL EDIT ROLE & PERMISSIONS -->
        <!-- =================================================================== -->
        <div x-show="showEditRoleModal" 
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
                 @click.away="showEditRoleModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-slate-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-bold text-base leading-tight">Edit Role & Permissions</h3>
                            <p class="text-xs text-gray-500">Perbarui wewenang untuk: <span class="font-bold text-gray-800" x-text="editRole.name"></span></p>
                        </div>
                    </div>
                    <button @click="showEditRoleModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
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
                                <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider">Centang Hak Akses (Permissions):</label>
                                <template x-if="editRole.name === 'Admin'">
                                    <span class="text-xs text-red-600 font-semibold bg-red-50 px-2 py-0.5 rounded border border-red-200">
                                        Admin memiliki hak akses penuh otomatis
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
                                                <button type="button" @click="selectAllPermissionsInGroup({{ json_encode($groupPermIds) }})" class="text-blue-600 hover:underline">Pilih Semua</button>
                                                <span class="text-gray-300">|</span>
                                                <button type="button" @click="deselectAllPermissionsInGroup({{ json_encode($groupPermIds) }})" class="text-rose-600 hover:underline">Hapus Semua</button>
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
                        <button type="button" @click="showEditRoleModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-medium hover:bg-gray-50 transition shadow-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg text-xs font-medium flex items-center hover:bg-amber-700 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- MODAL UBAH ROLE PENGGUNA (USER ROLE ASSIGNMENT) -->
        <!-- =================================================================== -->
        <div x-show="showEditUserRoleModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all" 
                 @click.away="showEditUserRoleModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-slate-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-bold text-base leading-tight">Ubah Role Pengguna</h3>
                            <p class="text-xs text-gray-500" x-text="editUser.name"></p>
                        </div>
                    </div>
                    <button @click="showEditUserRoleModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="`/rbac/users/${editUser.id}/roles`" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-4">
                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-200 text-xs">
                            <p class="text-gray-500">Email: <strong class="text-gray-900" x-text="editUser.email"></strong></p>
                            <p class="text-gray-500 mt-1">NIK: <strong class="text-gray-900" x-text="editUser.nik || '-'"></strong> | Dept: <strong class="text-gray-900" x-text="editUser.department || 'General'"></strong></p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Tugaskan Role (Pilih satu atau lebih):</label>
                            
                            <div class="space-y-2">
                                @foreach($roles as $r)
                                    <label class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-blue-50/50 cursor-pointer transition">
                                        <div class="flex items-center space-x-2.5">
                                            <input type="checkbox" 
                                                   name="roles[]" 
                                                   value="{{ $r->id }}"
                                                   :checked="editUser.roles.includes({{ $r->id }})"
                                                   class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                            <div>
                                                <span class="font-bold text-xs text-gray-900">{{ $r->name }}</span>
                                                <p class="text-[11px] text-gray-500">{{ $r->description ?? 'Role operasional sistem' }}</p>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                                            {{ $r->users_count }} user
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t border-gray-200 space-x-2">
                        <button type="button" @click="showEditUserRoleModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-medium hover:bg-gray-50 transition shadow-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-medium flex items-center hover:bg-indigo-700 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Role User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- MODAL HAPUS ROLE -->
        <!-- =================================================================== -->
        <div x-show="showDeleteRoleModal" 
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
                 @click.away="showDeleteRoleModal = false">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center w-14 h-14 rounded-full bg-rose-100 mb-4">
                        <svg class="text-rose-600 w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1.5">Hapus Role?</h3>
                    <p class="mb-5 text-xs text-gray-500 leading-relaxed">
                        Anda yakin ingin menghapus role <span class="font-bold text-gray-800" x-text="deleteRole.name"></span>?<br>
                        Tindakan ini akan mencabut seluruh relasi permission pada peran ini.
                    </p>
                    
                    <div class="flex justify-center space-x-2">
                        <button @click="showDeleteRoleModal = false" class="text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-xs font-medium px-4 py-2 transition shadow-sm">Batal</button>
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
