<x-app-layout>
    <x-slot name="title">DAS - Role-Based Access Control</x-slot>

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

        <!-- Page Header (Mission Control Aesthetic) -->
        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center space-x-3.5">
                <div class="p-2.5 bg-slate-900 rounded-lg text-white shadow-sm flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center space-x-2.5">
                        <h2 class="font-bold text-xl text-gray-900 leading-tight">Role-Based Access Control (RBAC)</h2>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Otorisasi Multi-Role Aktif • Super Admin Bypass
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Manajemen peran pengguna, hak otorisasi modul, dan pengamanan akses permission sistem</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2.5 w-full md:w-auto">
                <button type="button" @click="showAddModal = true" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold flex items-center justify-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg> 
                    Tambah Role Baru
                </button>
            </div>
        </div>

        <div class="p-6 max-w-7xl mx-auto space-y-6">
            
            <!-- Alerts -->
            @if (session('success'))
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl text-sm font-medium border border-emerald-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-rose-50 text-rose-800 p-4 rounded-xl text-sm font-medium border border-rose-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if (isset($errors) && $errors->any())
                <div class="bg-rose-50 text-rose-800 p-4 rounded-xl text-sm border border-rose-200 shadow-sm">
                    <div class="font-semibold mb-1 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Terdapat kesalahan input:
                    </div>
                    <ul class="list-disc pl-9 space-y-1 text-xs">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <!-- KPI Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Total Role -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-gray-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Peran (Roles)</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1.5 font-mono tabular-nums">{{ number_format($totalRoles) }}</h3>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Peran terdefinisi di sistem
                            </p>
                        </div>
                        <div class="p-3 bg-slate-100 rounded-lg text-slate-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Total Permissions -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-blue-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Permissions</p>
                            <h3 class="text-2xl font-bold text-blue-600 mt-1.5 font-mono tabular-nums">{{ number_format($totalPermissions) }}</h3>
                            <p class="text-xs text-blue-700 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Hak otorisasi lintas 10 modul
                            </p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Pengguna Terhubung -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-emerald-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Pengguna Terhubung</p>
                            <h3 class="text-2xl font-bold text-emerald-600 mt-1.5 font-mono tabular-nums">{{ number_format($totalUsers) }}</h3>
                            <p class="text-xs text-emerald-700 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                User aktif terikat hak role
                            </p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Superuser Policy -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-purple-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Superuser Policy</p>
                            <h3 class="text-2xl font-bold text-purple-600 mt-1.5 font-mono">Bypass Aktif</h3>
                            <p class="text-xs text-purple-700 mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                Role Admin memiliki akses penuh
                            </p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Table Card -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                
                <!-- Quick Status Filter Tabs -->
                <div class="border-b border-gray-200 bg-gray-50/50 px-5 pt-3 flex flex-wrap items-center gap-2">
                    <a href="{{ route('rbac.index', array_merge(request()->query(), ['tab' => 'all', 'page' => 1])) }}"
                       class="px-3.5 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($tab ?? 'all') == 'all' ? 'border-blue-600 text-blue-600 bg-white rounded-t-lg' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span>Semua Role</span>
                        <span class="px-1.5 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($tab ?? 'all') == 'all' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700' }}">
                            {{ $totalRoles }}
                        </span>
                    </a>

                    <a href="{{ route('rbac.index', array_merge(request()->query(), ['tab' => 'system', 'page' => 1])) }}"
                       class="px-3.5 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($tab ?? '') == 'system' ? 'border-rose-600 text-rose-700 bg-white rounded-t-lg' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span>Role Sistem (Admin)</span>
                        <span class="px-1.5 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($tab ?? '') == 'system' ? 'bg-rose-100 text-rose-800' : 'bg-gray-200 text-gray-700' }}">
                            1
                        </span>
                    </a>

                    <a href="{{ route('rbac.index', array_merge(request()->query(), ['tab' => 'operational', 'page' => 1])) }}"
                       class="px-3.5 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-2 {{ ($tab ?? '') == 'operational' ? 'border-blue-600 text-blue-600 bg-white rounded-t-lg' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/70 rounded-t-lg' }}">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>Role Operasional</span>
                        <span class="px-1.5 py-0.5 rounded text-[11px] font-mono tabular-nums {{ ($tab ?? '') == 'operational' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700' }}">
                            {{ max(0, $totalRoles - 1) }}
                        </span>
                    </a>
                </div>

                <!-- Secondary Filter Bar -->
                <div class="p-4 border-b border-gray-200 bg-white flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3">
                    <form method="GET" action="{{ route('rbac.index') }}" class="w-full flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3">
                        <input type="hidden" name="tab" value="{{ $tab ?? 'all' }}">

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

                            @if(($tab ?? 'all') !== 'all' || !empty($search))
                                <a href="{{ route('rbac.index') }}" class="px-2.5 py-1.5 text-xs text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg font-semibold inline-flex items-center gap-1 transition">
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
                            <input type="text" name="search" value="{{ $search }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:bg-white block w-full pl-9 pr-8 py-2 shadow-sm transition placeholder:text-gray-400 font-medium" placeholder="Cari nama role atau deskripsi..." onkeydown="if(event.key === 'Enter') this.form.submit()">
                            @if($search)
                                <a href="{{ route('rbac.index', ['tab' => $tab]) }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
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
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200 min-w-[200px]">Nama Role & Hierarki</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">Deskripsi Wewenang</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold text-center border-r border-gray-200 w-36">Total Pengguna</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">Hak Akses / Permissions</th>
                                <th scope="col" class="px-4 py-3.5 font-semibold text-center w-36">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($roles as $index => $role)
                                @php
                                    $borderClass = $role->name === 'Admin' ? 'border-l-4 border-l-rose-500 bg-white' : 'border-l-4 border-l-blue-500 bg-white';
                                @endphp
                                <tr class="{{ $borderClass }} hover:bg-slate-50/90 transition-colors">
                                    <!-- No -->
                                    <td class="px-4 py-3 text-center text-gray-500 border-r border-gray-100 font-mono tabular-nums text-xs">
                                        {{ $roles->firstItem() + $index }}
                                    </td>
                                    
                                    <!-- Nama Role -->
                                    <td class="px-5 py-3 border-r border-gray-100">
                                        <div class="flex items-center space-x-2.5">
                                            <div class="w-7 h-7 rounded-lg flex items-center justify-center font-bold text-[11px] font-mono shrink-0
                                                {{ $role->name === 'Admin' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}
                                            ">
                                                {{ strtoupper(substr($role->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="font-bold text-gray-900 text-xs">{{ $role->name }}</span>
                                                    @if($role->name === 'Admin')
                                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                            Super Admin
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-[10px] text-gray-400 font-mono block mt-0.5">Role ID: #{{ $role->id }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Deskripsi -->
                                    <td class="px-5 py-3 border-r border-gray-100 text-gray-600 text-xs max-w-sm truncate font-medium" title="{{ $role->description }}">
                                        {{ $role->description ?: 'Tidak ada deskripsi wewenang tercatat.' }}
                                    </td>

                                    <!-- Total Pengguna -->
                                    <td class="px-4 py-3 text-center border-r border-gray-100">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold font-mono tabular-nums {{ $role->users_count > 0 ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $role->users_count }} Pengguna
                                        </span>
                                    </td>

                                    <!-- Permissions -->
                                    <td class="px-5 py-3 border-r border-gray-100">
                                        @if($role->name === 'Admin')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Full System Access ({{ $totalPermissions }} Permissions)
                                            </span>
                                        @else
                                            <div class="flex flex-wrap gap-1 max-w-md">
                                                @forelse($role->permissions->take(4) as $p)
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                                        {{ $p->display_name }}
                                                    </span>
                                                @empty
                                                    <span class="text-gray-400 italic text-[11px]">- (Belum ada permission dikonfigurasi)</span>
                                                @endforelse
                                                @if($role->permissions->count() > 4)
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 font-mono tabular-nums">
                                                        +{{ $role->permissions->count() - 4 }} lainnya
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Action (Edit & Delete) -->
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center items-center space-x-1.5">
                                            <button type="button" 
                                                    @click="editRole = {
                                                        id: {{ $role->id }},
                                                        name: '{{ addslashes($role->name) }}',
                                                        description: '{{ addslashes($role->description ?? '') }}',
                                                        permissions: {{ json_encode($role->permissions->pluck('id')->toArray()) }}
                                                    }; showEditModal = true" 
                                                    class="px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium inline-flex items-center gap-1 shadow-xs transition"
                                                    title="Edit Wewenang & Hak Akses">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Edit
                                            </button>
                                            
                                            @if($role->name !== 'Admin')
                                                <button type="button" 
                                                        @click="deleteRole = {
                                                            id: {{ $role->id }},
                                                            name: '{{ addslashes($role->name) }}',
                                                            usersCount: {{ $role->users_count }}
                                                        }; showDeleteModal = true" 
                                                        class="px-2.5 py-1.5 bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 rounded-lg text-xs font-medium inline-flex items-center gap-1 transition"
                                                        title="Hapus Role">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                </svg>
                                            </div>
                                            <p class="font-bold text-gray-800 text-sm">Belum Ada Data Role Ditemukan</p>
                                            <p class="text-xs text-gray-400 mt-1">Silakan klik tombol "Tambah Role Baru" atau reset kriteria pencarian Anda.</p>
                                            @if(($tab ?? 'all') !== 'all' || !empty($search))
                                                <a href="{{ route('rbac.index') }}" class="mt-4 px-3.5 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-100 transition inline-flex items-center gap-1.5">
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

                <!-- Pagination Footer -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-4">
                    <div>
                        Menampilkan <span class="font-bold text-gray-900 font-mono tabular-nums">{{ $roles->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-gray-900 font-mono tabular-nums">{{ $roles->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-gray-900 font-mono tabular-nums">{{ $roles->total() }}</span> peran terdaftar
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
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-hidden my-8 border border-gray-200" 
                 @click.away="showAddModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="p-1.5 bg-blue-100 text-blue-700 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-bold text-sm">Tambah Data Role Baru</h3>
                            <p class="text-[11px] text-gray-500">Definisikan nama peran dan pilih hak otorisasi modul yang diberikan</p>
                        </div>
                    </div>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('rbac.roles.store') }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Role <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" required placeholder="Contoh: Quality Control, Warehouse Lead" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Deskripsi Peran</label>
                                <input type="text" name="description" placeholder="Penjelasan tugas dan wewenang operasional peran ini..." class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400">
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <label class="block text-xs font-bold text-gray-800 mb-2 uppercase tracking-wider">Pilih Hak Akses (Permissions):</label>
                            
                            <div class="space-y-3">
                                @foreach($groupedPermissions as $moduleName => $perms)
                                    <div class="bg-gray-50/80 p-3.5 rounded-xl border border-gray-200">
                                        <div class="font-bold text-xs text-gray-800 mb-2.5 flex items-center justify-between border-b border-gray-200 pb-1.5">
                                            <span class="text-blue-700">{{ $moduleName }}</span>
                                            <span class="text-[10px] text-gray-400 font-mono">({{ $perms->count() }} permissions)</span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach($perms as $p)
                                                <label class="flex items-start space-x-2 text-xs text-gray-700 cursor-pointer hover:text-blue-700 p-1 rounded hover:bg-white transition">
                                                    <input type="checkbox" name="permissions[]" value="{{ $p->id }}" class="mt-0.5 w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                                    <div>
                                                        <span class="font-semibold block leading-tight text-gray-800">{{ $p->display_name }}</span>
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

                    <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t border-gray-200 space-x-2.5">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold flex items-center hover:bg-blue-700 transition shadow-sm gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 
                            Simpan Data Role
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
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-hidden my-8 border border-gray-200" 
                 @click.away="showEditModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="p-1.5 bg-blue-100 text-blue-700 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-bold text-sm">Edit Data Role</h3>
                            <p class="text-[11px] text-gray-500">Perbarui konfigurasi role: <span class="font-bold text-gray-800" x-text="editRole.name"></span></p>
                        </div>
                    </div>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="'{{ url('/rbac/roles') }}/' + editRole.id" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Role <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" x-model="editRole.name" :readonly="editRole.name === 'Admin'" required class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 font-semibold" :class="editRole.name === 'Admin' ? 'bg-gray-100 cursor-not-allowed' : ''">
                                <template x-if="editRole.name === 'Admin'">
                                    <p class="text-[10px] text-rose-600 mt-1">Nama role Admin diproteksi karena merupakan superuser sistem.</p>
                                </template>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Deskripsi Peran</label>
                                <input type="text" name="description" x-model="editRole.description" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400">
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider">Konfigurasi Hak Akses (Permissions):</label>
                                <template x-if="editRole.name === 'Admin'">
                                    <span class="text-xs text-rose-700 font-semibold bg-rose-50 px-2.5 py-0.5 rounded-full border border-rose-200">
                                        Super Admin memiliki hak akses penuh otomatis
                                    </span>
                                </template>
                            </div>
                            
                            <div class="space-y-3">
                                @foreach($groupedPermissions as $moduleName => $perms)
                                    @php
                                        $groupPermIds = $perms->pluck('id')->toArray();
                                    @endphp
                                    <div class="bg-gray-50/80 p-3.5 rounded-xl border border-gray-200">
                                        <div class="font-bold text-xs text-gray-800 mb-2.5 flex items-center justify-between border-b border-gray-200 pb-1.5">
                                            <span class="text-blue-700">{{ $moduleName }}</span>
                                            <div class="flex space-x-2 text-[10px]">
                                                <button type="button" @click="selectAllInGroup({{ json_encode($groupPermIds) }})" class="text-blue-600 hover:underline font-semibold">Pilih Semua</button>
                                                <span class="text-gray-300">|</span>
                                                <button type="button" @click="deselectAllInGroup({{ json_encode($groupPermIds) }})" class="text-rose-600 hover:underline font-semibold">Hapus Semua</button>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach($perms as $p)
                                                <label class="flex items-start space-x-2 text-xs text-gray-700 cursor-pointer hover:text-blue-700 p-1 rounded hover:bg-white transition">
                                                    <input type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $p->id }}" 
                                                           :checked="hasPermission({{ $p->id }})"
                                                           @change="togglePermission({{ $p->id }})"
                                                           class="mt-0.5 w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                                    <div>
                                                        <span class="font-semibold block leading-tight text-gray-800">{{ $p->display_name }}</span>
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

                    <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t border-gray-200 space-x-2.5">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold flex items-center hover:bg-blue-700 transition shadow-sm gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 
                            Simpan Perubahan
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
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden border border-gray-200" 
                 @click.away="showDeleteModal = false">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-xl bg-rose-100 text-rose-600 mb-3.5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 mb-1.5">Hapus Data Role?</h3>
                    <p class="mb-4 text-xs text-gray-500 leading-relaxed">
                        Anda yakin ingin menghapus role <strong class="text-gray-900" x-text="deleteRole.name"></strong>?<br>
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <template x-if="deleteRole.usersCount > 0">
                        <div class="mb-4 p-2.5 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-[11px] font-medium text-left">
                            Perhatian: Terdapat <span class="font-bold" x-text="deleteRole.usersCount"></span> pengguna yang saat ini terikat pada role ini.
                        </div>
                    </template>
                    
                    <div class="flex justify-center space-x-2 pt-2 border-t border-gray-100">
                        <button @click="showDeleteModal = false" class="text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-xs font-semibold px-4 py-2 transition shadow-xs">Batal</button>
                        <form :action="'{{ url('/rbac/roles') }}/' + deleteRole.id" method="POST" class="inline-flex">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white bg-rose-600 hover:bg-rose-700 rounded-lg text-xs font-bold px-4 py-2 transition shadow-xs">
                                Ya, Hapus Role
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
