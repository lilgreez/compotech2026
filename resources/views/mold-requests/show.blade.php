<x-app-layout>
    <x-slot name="title">DAS - Detail SPK {{ $moldRequest->spk_number }}</x-slot>

    <div x-data="{ 
        showMaterialModal: false,
        activeStage: null,
        showStageModal: false,
        openStageModal(stage) {
            this.activeStage = stage;
            this.showStageModal = true;
        }
    }">
        <!-- Page Header -->
        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center space-x-3.5">
                <a href="{{ route('mold-requests.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <div class="flex items-center space-x-3">
                        <h2 class="font-bold text-xl text-gray-900 leading-tight font-mono">{{ $moldRequest->spk_number }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $moldRequest->status === 'Completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $moldRequest->status }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $moldRequest->mold_name }} — Klien: {{ $moldRequest->customer }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2.5">
                <button type="button" @click="showMaterialModal = true" class="px-3.5 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    + Tambah Material
                </button>
            </div>
        </div>

        <div class="p-6 max-w-7xl mx-auto space-y-6">
            
            <!-- Alert Notifications -->
            @if (session('success'))
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl text-sm font-medium border border-emerald-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Progress Tracker Overview Card -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                    <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Progress Kumulatif SPK</span>
                        <h3 class="text-3xl font-bold text-gray-900 mt-1 tabular-nums">{{ $moldRequest->total_progress }}%</h3>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                        <div>
                            <span class="text-gray-400 block">Mulai Pengerjaan:</span>
                            <span class="font-bold text-gray-800 tabular-nums">{{ $moldRequest->start_date ? $moldRequest->start_date->format('d M Y') : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block">Target Selesai:</span>
                            <span class="font-bold text-gray-800 tabular-nums">{{ $moldRequest->target_date ? $moldRequest->target_date->format('d M Y') : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block">PIC Pelaksana:</span>
                            <span class="font-bold text-gray-800">{{ $moldRequest->pic_name ?: 'Belum ditentukan' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block">Diterbitkan Oleh:</span>
                            <span class="font-bold text-gray-800">{{ $moldRequest->creator ? $moldRequest->creator->name : 'System' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Big Progress Bar -->
                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                    <div class="h-3 rounded-full {{ $moldRequest->total_progress >= 100 ? 'bg-emerald-500' : 'bg-blue-600' }} transition-all duration-500" style="width: {{ $moldRequest->total_progress }}%"></div>
                </div>
            </div>

            <!-- SECTION 1: TAHAPAN PENGERJAAN SPK -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Tahapan Pengerjaan Pembuatan Mold</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Persentase progress terhitung otomatis berdasarkan status penyelesaian tiap tahapan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @foreach($moldRequest->stages as $stage)
                        <div class="border rounded-xl p-4 flex flex-col justify-between transition-all {{ $stage->status === 'Completed' ? 'border-emerald-300 bg-emerald-50/30' : ($stage->status === 'In Progress' ? 'border-blue-300 bg-blue-50/20' : 'border-gray-200 bg-gray-50/40') }}">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-mono font-bold text-gray-400">Step {{ $stage->stage_order }}</span>
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded {{ $stage->status === 'Completed' ? 'bg-emerald-100 text-emerald-800' : ($stage->status === 'In Progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-600') }}">
                                        {{ $stage->status }}
                                    </span>
                                </div>
                                <h4 class="font-bold text-gray-900 text-sm">{{ $stage->stage_name }}</h4>
                                <p class="text-xs text-gray-500 mt-0.5">Bobot: <span class="font-semibold text-gray-700">{{ $stage->weight_percent }}%</span></p>

                                @if($stage->notes)
                                    <p class="text-[11px] text-gray-600 italic mt-2 bg-white/70 p-2 rounded border border-gray-100">
                                        "{{ $stage->notes }}"
                                    </p>
                                @endif
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-200/60 flex items-center justify-between">
                                <button type="button" @click="openStageModal({{ $stage->toJson() }})" class="w-full py-1.5 px-3 bg-white hover:bg-gray-100 text-gray-800 border border-gray-300 rounded text-xs font-semibold shadow-sm transition">
                                    Update Status
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- SECTION 2: CHECKLIST PART & MATERIAL -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="p-5 border-b border-gray-200 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Checklist Kebutuhan Part / Material</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Daftar kebutuhan suku cadang dan material cetakan untuk SPK ini</p>
                    </div>
                    <button type="button" @click="showMaterialModal = true" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition">
                        + Tambah Part
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-5 py-3 text-center w-12 border-r border-gray-200">No.</th>
                                <th class="px-5 py-3 border-r border-gray-200">Nama Part / Komponen</th>
                                <th class="px-5 py-3 text-center border-r border-gray-200">Kebutuhan Qty</th>
                                <th class="px-5 py-3 text-center border-r border-gray-200">Ketersediaan Qty</th>
                                <th class="px-5 py-3 text-center border-r border-gray-200">Status Ketersediaan</th>
                                <th class="px-5 py-3 border-r border-gray-200">Catatan</th>
                                <th class="px-5 py-3 text-center w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($moldRequest->materials as $idx => $mat)
                                <tr class="bg-white hover:bg-slate-50 transition">
                                    <td class="px-5 py-3 text-center text-gray-500 border-r border-gray-100 tabular-nums">{{ $idx + 1 }}</td>
                                    <td class="px-5 py-3 font-semibold text-gray-900 border-r border-gray-100">{{ $mat->part_name }}</td>
                                    <td class="px-5 py-3 text-center border-r border-gray-100 font-bold tabular-nums">{{ $mat->required_qty }} pcs</td>
                                    <td class="px-5 py-3 text-center border-r border-gray-100 font-bold tabular-nums">{{ $mat->available_qty }} pcs</td>
                                    <td class="px-5 py-3 text-center border-r border-gray-100">
                                        @if($mat->status === 'Tersedia' || $mat->status === 'Selesai')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                {{ $mat->status }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                {{ $mat->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-gray-500 text-xs border-r border-gray-100">{{ $mat->notes ?: '-' }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <form action="{{ route('mold-requests.materials.destroy', [$moldRequest->id, $mat->id]) }}" method="POST" onsubmit="return confirm('Hapus part material ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-gray-400 text-xs">
                                        Belum ada checklist kebutuhan part untuk SPK ini. Klik "+ Tambah Part" untuk menambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- MODAL: UPDATE TAHAPAN -->
        <div x-show="showStageModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden" 
                 @click.away="showStageModal = false">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-gray-900 font-bold text-base">
                        Update Tahapan: <span x-text="activeStage ? activeStage.stage_name : ''"></span>
                    </h3>
                    <button @click="showStageModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="`/mold-requests/{{ $moldRequest->id }}/stages/${activeStage ? activeStage.id : ''}`" method="POST" class="p-6 space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Status Tahapan</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500" :value="activeStage ? activeStage.status : 'Pending'">
                            <option value="Pending">Pending (Belum Mulai)</option>
                            <option value="In Progress">In Progress (Sedang Dikerjakan)</option>
                            <option value="Completed">Completed (Selesai)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Pengerjaan</label>
                        <textarea name="notes" rows="3" placeholder="Catatan progress atau kendala mesin/material..." class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500" :value="activeStage ? activeStage.notes : ''"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="showStageModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: TAMBAH MATERIAL -->
        <div x-show="showMaterialModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden" 
                 @click.away="showMaterialModal = false">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-gray-900 font-bold text-base">Tambah Kebutuhan Part / Material</h3>
                    <button @click="showMaterialModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('mold-requests.materials.store', $moldRequest->id) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Part / Material <span class="text-red-500">*</span></label>
                        <input type="text" name="part_name" required placeholder="Contoh: Core Block SKD61, Angular Pin" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Dibutuhkan (Qty) <span class="text-red-500">*</span></label>
                            <input type="number" name="required_qty" required min="1" value="1" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Tersedia (Qty)</label>
                            <input type="number" name="available_qty" min="0" value="0" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                            <option value="Menunggu Pengadaan">Menunggu Pengadaan</option>
                            <option value="Tersedia">Tersedia</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Tambahan</label>
                        <input type="text" name="notes" placeholder="Catatan supplier, ukuran, dll..." class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="showMaterialModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition">Tambahkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
