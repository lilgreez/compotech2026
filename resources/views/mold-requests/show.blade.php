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
                <a href="{{ route('mold-requests.index') }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition" title="Kembali ke Daftar SPK">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h2 class="font-bold text-xl text-gray-900 leading-tight font-mono">{{ $moldRequest->spk_number }}</h2>
                        
                        @if($moldRequest->status === 'Completed')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Selesai (Completed)
                            </span>
                        @elseif($moldRequest->status === 'In Progress')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                In Progress
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                {{ $moldRequest->status }}
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2 text-xs text-gray-500 mt-1">
                        <span class="font-semibold text-gray-700">{{ $moldRequest->mold_name }}</span>
                        <span class="text-gray-300">•</span>
                        <span>Ownership: <strong class="text-gray-700 uppercase">{{ $moldRequest->customer }}</strong></span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2.5 w-full md:w-auto">
                <button type="button" @click="showMaterialModal = true" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition shadow-sm inline-flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Kebutuhan Part</span>
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
                        <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider block">Progress Kumulatif SPK</span>
                        <h3 class="text-3xl font-bold text-gray-900 mt-0.5 font-mono tabular-nums">{{ $moldRequest->total_progress }}%</h3>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                        <div>
                            <span class="text-gray-400 block text-[11px] font-medium">Mulai Pengerjaan:</span>
                            <span class="font-bold text-gray-800 font-mono tabular-nums">{{ $moldRequest->start_date ? $moldRequest->start_date->format('d M Y') : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[11px] font-medium">Target Selesai:</span>
                            <span class="font-bold text-gray-800 font-mono tabular-nums">{{ $moldRequest->target_date ? $moldRequest->target_date->format('d M Y') : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[11px] font-medium">PIC Pelaksana:</span>
                            <span class="font-bold text-gray-800">{{ $moldRequest->pic_name ?: 'Belum ditentukan' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[11px] font-medium">Diterbitkan Oleh:</span>
                            <span class="font-bold text-gray-800">{{ $moldRequest->creator ? $moldRequest->creator->name : 'System Admin' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Big Progress Bar -->
                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                    <div class="h-3 rounded-full transition-all duration-500 {{ $moldRequest->total_progress >= 100 ? 'bg-emerald-500' : ($moldRequest->total_progress >= 50 ? 'bg-blue-600' : 'bg-amber-500') }}" 
                         style="width: {{ $moldRequest->total_progress }}%"></div>
                </div>

                @if($moldRequest->notes)
                    <div class="mt-4 pt-3.5 border-t border-gray-100 text-xs text-gray-600 flex items-start gap-2">
                        <span class="font-bold text-gray-700 shrink-0">Catatan Spesifikasi:</span>
                        <span class="italic">{{ $moldRequest->notes }}</span>
                    </div>
                @endif
            </div>

            <!-- SECTION 1: TAHAPAN PENGERJAAN SPK (5 TAHAPAN STANDAR) -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            Tahapan Fabrikasi Pembuatan Mold (Standard 5 Steps)
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Persentase progress kumulatif terhitung otomatis berdasarkan status bobot tiap tahapan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @foreach($moldRequest->stages as $stage)
                        <div class="border rounded-xl p-4 flex flex-col justify-between transition-all {{ $stage->status === 'Completed' ? 'border-emerald-300 bg-emerald-50/30' : ($stage->status === 'In Progress' ? 'border-blue-300 bg-blue-50/20' : 'border-gray-200 bg-gray-50/40') }}">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[11px] font-mono font-bold text-gray-400">Step {{ $stage->stage_order }}</span>
                                    @if($stage->status === 'Completed')
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">
                                            Completed
                                        </span>
                                    @elseif($stage->status === 'In Progress')
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-800">
                                            In Progress
                                        </span>
                                    @else
                                        <span class="text-[10px] font-medium px-2 py-0.5 rounded bg-gray-200 text-gray-700">
                                            Pending
                                        </span>
                                    @endif
                                </div>
                                <h4 class="font-bold text-gray-900 text-sm">{{ $stage->stage_name }}</h4>
                                <p class="text-xs text-gray-500 mt-0.5">Bobot: <span class="font-bold text-gray-700 font-mono tabular-nums">{{ $stage->weight_percent }}%</span></p>

                                @if($stage->notes)
                                    <p class="text-[11px] text-gray-600 italic mt-2.5 bg-white/80 p-2 rounded-lg border border-gray-200/60">
                                        "{{ $stage->notes }}"
                                    </p>
                                @endif

                                @if($stage->completed_at)
                                    <div class="text-[10px] text-emerald-700 font-mono tabular-nums mt-2">
                                        Selesai: {{ $stage->completed_at->format('d M Y') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-200/60">
                                <button type="button" @click="openStageModal({{ $stage->toJson() }})" class="w-full py-1.5 px-3 bg-white hover:bg-gray-100 text-gray-800 border border-gray-300 rounded-lg text-xs font-semibold shadow-xs transition flex items-center justify-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Update Tahapan
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- SECTION 2: CHECKLIST PART & MATERIAL -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="p-5 border-b border-gray-200 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <div class="p-1.5 bg-slate-100 text-slate-700 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            Checklist Kebutuhan Part & Material Mold
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Daftar kebutuhan suku cadang, baja blok, dan material cetakan untuk fabrikasi SPK ini</p>
                    </div>
                    <button type="button" @click="showMaterialModal = true" class="px-3.5 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition shadow-xs inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        + Tambah Part / Material
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-[11px] text-gray-500 uppercase tracking-wider bg-gray-50/80 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-center w-12 border-r border-gray-200">No.</th>
                                <th class="px-5 py-3 border-r border-gray-200">Nama Part / Komponen</th>
                                <th class="px-4 py-3 text-center border-r border-gray-200 w-32">Kebutuhan Qty</th>
                                <th class="px-4 py-3 text-center border-r border-gray-200 w-32">Tersedia Qty</th>
                                <th class="px-4 py-3 text-center border-r border-gray-200 w-36">Status Pengadaan</th>
                                <th class="px-5 py-3 border-r border-gray-200">Catatan</th>
                                <th class="px-4 py-3 text-center w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($moldRequest->materials as $idx => $mat)
                                <tr class="bg-white hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3 text-center text-gray-500 border-r border-gray-100 font-mono tabular-nums text-xs">{{ $idx + 1 }}</td>
                                    <td class="px-5 py-3 font-bold text-gray-900 border-r border-gray-100 text-xs">{{ $mat->part_name }}</td>
                                    <td class="px-4 py-3 text-center border-r border-gray-100 font-bold font-mono tabular-nums text-xs">{{ $mat->required_qty }} pcs</td>
                                    <td class="px-4 py-3 text-center border-r border-gray-100 font-bold font-mono tabular-nums text-xs text-blue-600">{{ $mat->available_qty }} pcs</td>
                                    <td class="px-4 py-3 text-center border-r border-gray-100">
                                        @if($mat->status === 'Tersedia' || $mat->status === 'Selesai')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                {{ $mat->status }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                {{ $mat->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-gray-500 text-xs border-r border-gray-100">{{ $mat->notes ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <form action="{{ route('mold-requests.materials.destroy', [$moldRequest->id, $mat->id]) }}" method="POST" onsubmit="return confirm('Hapus part material ini dari checklist SPK?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-semibold p-1 hover:bg-rose-50 rounded transition">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-400 text-xs">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            <p class="font-medium text-gray-600">Belum ada checklist kebutuhan part untuk SPK ini.</p>
                                            <p class="text-[11px] text-gray-400 mt-0.5">Klik tombol "+ Tambah Part / Material" di atas untuk menambahkan daftar material.</p>
                                        </div>
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
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-200" 
                 @click.away="showStageModal = false">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-2">
                        <div class="p-1.5 bg-blue-100 text-blue-700 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="text-gray-900 font-bold text-sm">
                            Update Tahapan: <span x-text="activeStage ? activeStage.stage_name : ''"></span>
                        </h3>
                    </div>
                    <button @click="showStageModal = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="'{{ url('/mold-requests/' . $moldRequest->id . '/stages') }}/' + (activeStage ? activeStage.id : '')" method="POST" class="p-6 space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Status Tahapan <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 font-medium" :value="activeStage ? activeStage.status : 'Pending'">
                            <option value="Pending">Pending (Belum Mulai)</option>
                            <option value="In Progress">In Progress (Sedang Dikerjakan)</option>
                            <option value="Completed">Completed (Tahapan Selesai)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Progress / Hasil Pengerjaan</label>
                        <textarea name="notes" rows="3" placeholder="Catatan progress, hasil pengerjaan, atau kendala mesin/material..." class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400" :value="activeStage ? activeStage.notes : ''"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-3 border-t border-gray-100">
                        <button type="button" @click="showStageModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition shadow-sm inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: TAMBAH MATERIAL -->
        <div x-show="showMaterialModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-200" 
                 @click.away="showMaterialModal = false">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-2">
                        <div class="p-1.5 bg-blue-100 text-blue-700 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h3 class="text-gray-900 font-bold text-sm">Tambah Kebutuhan Part / Material</h3>
                    </div>
                    <button @click="showMaterialModal = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('mold-requests.materials.store', $moldRequest->id) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Part / Material <span class="text-red-500">*</span></label>
                        <input type="text" name="part_name" required placeholder="Contoh: Core Block Baja SKD61, Angular Pin, Spring" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Dibutuhkan (Qty) <span class="text-red-500">*</span></label>
                            <input type="number" name="required_qty" required min="1" value="1" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Tersedia (Qty)</label>
                            <input type="number" name="available_qty" min="0" value="0" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Status Pengadaan</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 font-medium">
                            <option value="Menunggu Pengadaan">Menunggu Pengadaan</option>
                            <option value="Tersedia">Tersedia</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Spesifikasi</label>
                        <input type="text" name="notes" placeholder="Catatan supplier, ukuran dimensi, grade material..." class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400">
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-3 border-t border-gray-100">
                        <button type="button" @click="showMaterialModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition shadow-sm inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambahkan Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
