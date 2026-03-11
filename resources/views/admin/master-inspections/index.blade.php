<x-app-layout>
    @php
        // Translasi nama tab untuk UI
        $tabNames = [
            'kerusakan' => 'Jenis Kerusakan',
            'tindakan' => 'Tindakan Perbaikan',
            'alasan' => 'Alasan / Reasons'
        ];
        $currentTabName = $tabNames[$tab] ?? 'Jenis Kerusakan';
    @endphp

    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        formData: { id: '', name: '' }
    }">

        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-2.5 bg-indigo-600 rounded-lg text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">Master Inspection</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola kamus data untuk form riwayat inspeksi</p>
                </div>
            </div>
            
            <button @click="showAddModal = true" class="w-full md:w-auto px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium flex items-center justify-center hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
                Add {{ $currentTabName }}
            </button>
        </div>

        <div class="p-6 max-w-7xl mx-auto">
            @if (session('success'))
                <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-lg text-sm font-medium border border-green-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                
                <div class="bg-gray-50/80 border-b border-gray-200 px-6 pt-4">
                    <nav class="flex space-x-8 overflow-x-auto" aria-label="Tabs">
                        <a href="{{ route('master-inspections.index', ['tab' => 'kerusakan']) }}" 
                           class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $tab === 'kerusakan' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Jenis Kerusakan
                        </a>
                        <a href="{{ route('master-inspections.index', ['tab' => 'tindakan']) }}" 
                           class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $tab === 'tindakan' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Tindakan Perbaikan
                        </a>
                        <a href="{{ route('master-inspections.index', ['tab' => 'alasan']) }}" 
                           class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $tab === 'alasan' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Alasan / Reasons
                        </a>
                    </nav>
                </div>

                <div>
                    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white">
                        <form method="GET" action="{{ route('master-inspections.index') }}" class="w-full flex flex-col sm:flex-row justify-between items-center gap-4" x-data>
                            <input type="hidden" name="tab" value="{{ $tab }}">
                            
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600 whitespace-nowrap">Show</span>
                                <select name="per_page" class="w-20 border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 bg-white shadow-sm" x-on:change="$el.closest('form').submit()">
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
                                <input type="text" name="search" value="{{ $search }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 py-2 pr-3 shadow-sm transition-shadow" placeholder="Cari data {{ strtolower($currentTabName) }}..." x-on:input.debounce.500ms="$el.closest('form').submit()">
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left whitespace-nowrap">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200 w-16">No.</th>
                                    <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">{{ $currentTabName }}</th>
                                    <th scope="col" class="px-6 py-4 font-semibold text-center min-w-[140px] w-40">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($inspections as $index => $item)
                                    <tr class="bg-white hover:bg-blue-50/50 transition-colors group">
                                        <td class="px-6 py-4 text-center text-gray-500 border-r border-gray-100">{{ $inspections->firstItem() + $index }}</td>
                                        
                                        <td class="px-6 py-4 text-gray-800 border-r border-gray-100 font-medium">{{ $item->name }}</td>
                                        
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center space-x-2">
                                                <button @click="formData = { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}' }; showEditModal = true" 
                                                        class="px-3 py-1.5 bg-blue-500 text-white rounded text-xs font-medium flex items-center hover:bg-blue-600 shadow-sm transition">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit
                                                </button>
                                                <button @click="formData = { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}' }; showDeleteModal = true" 
                                                        class="px-3 py-1.5 bg-red-500 text-white rounded text-xs font-medium flex items-center hover:bg-red-600 shadow-sm transition">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                                </div>
                                                <p class="text-gray-500 font-medium text-base">Belum ada data {{ $currentTabName }}.</p>
                                                <p class="text-gray-400 text-sm mt-1">Silakan klik tombol "Add {{ $currentTabName }}" untuk menambahkan data.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($inspections->hasPages() || $inspections->total() > 0)
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-500 gap-4">
                            <div>
                                Showing <span class="font-medium text-gray-900">{{ $inspections->firstItem() ?? 0 }}</span> to <span class="font-medium text-gray-900">{{ $inspections->lastItem() ?? 0 }}</span> of <span class="font-medium text-gray-900">{{ $inspections->total() }}</span> entries
                            </div>
                            <div>
                                {{ $inspections->appends(request()->query())->links('pagination::tailwind') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="showAddModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all flex flex-col" 
                 @click.away="showAddModal = false"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-gray-800 font-bold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah {{ $currentTabName }}
                    </h3>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('master-inspections.store') }}" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="type" value="{{ $tab }}">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama {{ $currentTabName }} <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="Masukkan data..." class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
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
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all flex flex-col" 
                 @click.away="showEditModal = false"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-gray-800 font-bold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit {{ $currentTabName }}
                    </h3>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="`/master-inspections/${formData.id}`" method="POST" class="p-6">
                    @csrf @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama {{ $currentTabName }} <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="formData.name" required class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
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
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Data?</h3>
                    <p class="mb-6 text-sm text-gray-500">Anda yakin ingin menghapus <span class="font-bold text-gray-800" x-text="formData.name"></span>? Data yang dihapus tidak dapat dikembalikan.</p>
                    
                    <div class="flex justify-center space-x-3">
                        <button @click="showDeleteModal = false" class="text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-sm font-medium px-5 py-2.5 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                        <form :action="`/master-inspections/${formData.id}`" method="POST" class="inline-flex">
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