<x-app-layout>
    <div x-data="{ 
        currentView: 'table',
        showDeleteModal: false,
        formData: { id: '', email: '', full_name: '', status: 'Aktif' },
        
        openEdit(item) {
            this.formData = { 
                id: item.id, 
                email: item.email, 
                full_name: item.full_name, 
                status: item.status 
            };
            this.currentView = 'edit';
        },
        openDelete(item) {
            this.formData = { id: item.id, email: item.email };
            this.showDeleteModal = true;
        }
    }">

        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-2.5 bg-indigo-600 rounded-lg text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">Pengaturan Email Report</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola daftar penerima laporan email otomatis dari sistem</p>
                </div>
            </div>
            
            <div x-show="currentView === 'table'" x-transition.opacity>
                <button @click="currentView = 'add'; formData = { email: '', full_name: '', status: 'Aktif' }" class="w-full md:w-auto px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium flex items-center justify-center hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
                    Tambah Penerima
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
                    <form method="GET" action="{{ route('email-reports.index') }}" class="w-full flex flex-col sm:flex-row justify-between items-center gap-4">
                        
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
                            <input type="text" name="search" value="{{ $search }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 py-2 pr-3 shadow-sm transition-shadow" placeholder="Cari email atau nama..." onchange="this.form.submit()">
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Alamat Email</th>
                                <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Nama Lengkap</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200 w-32">Status</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center w-40">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($emails as $item)
                                <tr class="bg-white hover:bg-blue-50/50 transition-colors group">
                                    
                                    <td class="px-6 py-4 text-gray-900 border-r border-gray-100 font-medium flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs mr-3">
                                            {{ strtoupper(substr($item->email, 0, 1)) }}
                                        </div>
                                        {{ $item->email }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-gray-700 border-r border-gray-100">{{ $item->full_name }}</td>
                                    
                                    <td class="px-6 py-4 text-center border-r border-gray-100">
                                        @if($item->status === 'Aktif')
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                            <button @click="openEdit({{ $item->toJson() }})" class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-md text-xs font-medium flex items-center transition-colors border border-blue-200 hover:border-blue-600">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit
                                            </button>
                                            <button @click="openDelete({{ $item->toJson() }})" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-md text-xs font-medium flex items-center transition-colors border border-red-200 hover:border-red-600">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <p class="text-gray-500 font-medium text-base">Belum ada data email penerima laporan.</p>
                                            <p class="text-gray-400 text-sm mt-1">Silakan klik tombol "Tambah Penerima" di atas.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($emails->hasPages() || $emails->total() > 0)
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-500 gap-4">
                        <div>
                            Showing <span class="font-medium text-gray-900">{{ $emails->firstItem() ?? 0 }}</span> to <span class="font-medium text-gray-900">{{ $emails->lastItem() ?? 0 }}</span> of <span class="font-medium text-gray-900">{{ $emails->total() }}</span> entries
                        </div>
                        <div>
                            {{ $emails->appends(request()->query())->links('pagination::tailwind') }}
                        </div>
                    </div>
                @endif
            </div>

            <div x-show="currentView === 'add' || currentView === 'edit'" x-transition.opacity class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden" style="display: none;" x-cloak>
                
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50 flex items-center">
                    <button @click="currentView = 'table'" class="mr-4 text-gray-400 hover:text-indigo-600 transition-colors tooltip" title="Kembali ke tabel">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </button>
                    <h3 class="text-gray-800 font-bold text-lg" x-text="currentView === 'add' ? 'Tambah Penerima Baru' : 'Edit Data Penerima'"></h3>
                </div>
                
                <form :action="currentView === 'add' ? '{{ route('email-reports.store') }}' : `/email-reports/${formData.id}`" method="POST" class="p-6">
                    @csrf
                    <template x-if="currentView === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="max-w-3xl space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" x-model="formData.email" required placeholder="spv@compotech.com" class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                            </div>
                            
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="full_name" x-model="formData.full_name" required placeholder="Masukkan nama..." class="w-full border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                            </div>
                            
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status Pengiriman <span class="text-red-500">*</span></label>
                                <select name="status" x-model="formData.status" class="w-full sm:w-1/2 border-gray-300 rounded-lg text-sm py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                                    <option value="Aktif">Aktif (Menerima Laporan)</option>
                                    <option value="Nonaktif">Nonaktif (Dihentikan)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-100 flex justify-end space-x-3 mt-8">
                            <button type="button" @click="currentView = 'table'" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold flex items-center hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Data
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
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Penerima Email?</h3>
                        <p class="mb-6 text-sm text-gray-500">Anda yakin ingin menghapus <span class="font-bold text-gray-800" x-text="formData.email"></span> dari daftar laporan? Aksi ini tidak dapat dibatalkan.</p>
                        
                        <div class="flex justify-center space-x-3">
                            <button @click="showDeleteModal = false" class="text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-sm font-medium px-5 py-2.5 focus:ring-4 focus:ring-gray-100 transition shadow-sm">Batal</button>
                            <form :action="`/email-reports/${formData.id}`" method="POST" class="inline-flex">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-white bg-red-600 hover:bg-red-700 rounded-lg text-sm font-medium px-5 py-2.5 focus:ring-4 focus:ring-red-200 transition shadow-sm">
                                    Ya, Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>