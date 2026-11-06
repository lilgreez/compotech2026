<x-app-layout>
    <x-slot name="title">DAS - Tiket {{ $ticket->ticket_number }}</x-slot>

    <div x-data="{ 
        showAssignModal: false, 
        showProgressModal: false 
    }">
        <!-- Page Header -->
        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center space-x-3.5">
                <a href="{{ route('repair-tickets.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <div class="flex items-center space-x-3">
                        <h2 class="font-bold text-xl text-gray-900 leading-tight font-mono">{{ $ticket->ticket_number }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $ticket->status === 'Resolved' || $ticket->status === 'Closed' ? 'bg-emerald-100 text-emerald-800' : ($ticket->status === 'In Progress' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ $ticket->status }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded text-xs font-bold {{ $ticket->priority === 'Urgent' ? 'bg-red-100 text-red-800' : ($ticket->priority === 'High' ? 'bg-amber-100 text-amber-800' : 'bg-blue-50 text-blue-700') }}">
                            Prioritas: {{ $ticket->priority }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5">Mold: {{ $ticket->dieset ? $ticket->dieset->name : 'N/A' }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2.5">
                <button type="button" @click="showAssignModal = true" class="px-3.5 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    {{ $ticket->technician_user_id ? 'Ganti Teknisi' : 'Tugaskan Teknisi' }}
                </button>

                <button type="button" @click="showProgressModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition shadow-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Update Progress
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left 2 Cols: Ticket Info & Evidence -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Detail Permasalahan -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <h3 class="text-base font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Deskripsi Permasalahan & Kerusakan
                        </h3>

                        <div class="prose max-w-none text-gray-800 text-sm whitespace-pre-line leading-relaxed bg-gray-50/60 p-4 rounded-lg border border-gray-100">
                            {{ $ticket->issue_description }}
                        </div>

                        <!-- Progress Bar & Notes -->
                        <div class="mt-6 pt-5 border-t border-gray-100">
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Progress Penanganan</span>
                                <span class="text-xs font-bold text-gray-900 tabular-nums">{{ $ticket->progress_percent }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="h-2.5 rounded-full {{ $ticket->progress_percent >= 100 ? 'bg-emerald-500' : 'bg-blue-600' }} transition-all duration-300" style="width: {{ $ticket->progress_percent }}%"></div>
                            </div>

                            @if($ticket->technician_notes)
                                <div class="mt-4 p-3 bg-blue-50/50 border border-blue-100 rounded-lg text-xs text-blue-900">
                                    <span class="font-bold block mb-0.5">Catatan Terakhir Teknisi:</span>
                                    {{ $ticket->technician_notes }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Foto Bukti Kerusakan -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <h3 class="text-base font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Foto Bukti Kerusakan
                        </h3>

                        @if($ticket->evidence_photo)
                            <div class="max-w-md">
                                <a href="{{ asset('storage/'.$ticket->evidence_photo) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$ticket->evidence_photo) }}" alt="Evidence Photo" class="w-full h-auto rounded-lg border border-gray-200 shadow-sm hover:opacity-95 transition">
                                </a>
                                <p class="text-[11px] text-gray-400 mt-1 italic">Klik gambar untuk membuka ukuran penuh.</p>
                            </div>
                        @else
                            <div class="p-8 text-center bg-gray-50 rounded-lg border border-dashed border-gray-200 text-gray-400 text-xs">
                                Tidak ada foto bukti yang diunggah saat pelaporan tiket.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Col: Metadata & Timeline Logs -->
                <div class="space-y-6">
                    
                    <!-- Metadata Panel -->
                    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3.5 text-xs">
                        <h4 class="font-bold text-gray-900 text-sm pb-2 border-b border-gray-100">Informasi Tiket</h4>

                        <div>
                            <span class="text-gray-400 block">Cetakan Mold:</span>
                            <span class="font-bold text-gray-800 text-sm">{{ $ticket->dieset ? $ticket->dieset->name : 'N/A' }}</span>
                        </div>

                        <div>
                            <span class="text-gray-400 block">Pelapor Kerusakan:</span>
                            <span class="font-semibold text-gray-800">{{ $ticket->reporter ? $ticket->reporter->name : 'Operator' }}</span>
                        </div>

                        <div>
                            <span class="text-gray-400 block">Waktu Pengajuan:</span>
                            <span class="font-medium text-gray-700 tabular-nums">{{ $ticket->created_at->format('d F Y, H:i') }} WIB</span>
                        </div>

                        <div>
                            <span class="text-gray-400 block">Teknisi PIC:</span>
                            @if($ticket->technician)
                                <span class="font-bold text-blue-600">{{ $ticket->technician->name }}</span>
                            @else
                                <span class="text-amber-600 font-semibold italic">Belum Ditugaskan</span>
                            @endif
                        </div>

                        @if($ticket->resolved_at)
                            <div>
                                <span class="text-gray-400 block">Waktu Selesai:</span>
                                <span class="font-bold text-emerald-600 tabular-nums">{{ $ticket->resolved_at->format('d F Y, H:i') }} WIB</span>
                            </div>
                        @endif
                    </div>

                    <!-- Timeline Audit Logs -->
                    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                        <h4 class="font-bold text-gray-900 text-sm mb-4 pb-2 border-b border-gray-100 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Timeline Penanganan
                        </h4>

                        <div class="relative pl-5 border-l-2 border-gray-200 space-y-4">
                            @forelse($ticket->logs as $log)
                                <div class="relative">
                                    <div class="absolute -left-[27px] top-0.5 w-3 h-3 rounded-full bg-blue-600 ring-4 ring-white"></div>
                                    <div class="text-xs">
                                        <div class="font-bold text-gray-800">{{ $log->action }}</div>
                                        <p class="text-gray-600 mt-0.5">{{ $log->note }}</p>
                                        <div class="text-[10px] text-gray-400 mt-1 tabular-nums">
                                            {{ $log->user ? $log->user->name : 'Sistem' }} • {{ $log->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400">Belum ada riwayat aktivitas.</p>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- MODAL: PENUGASAN TEKNISI -->
        <div x-show="showAssignModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden" 
                 @click.away="showAssignModal = false">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-gray-900 font-bold text-base">Penugasan Teknisi Maintenance</h3>
                    <button @click="showAssignModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('repair-tickets.assign', $ticket->id) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Pilih Teknisi Penanggung Jawab <span class="text-red-500">*</span></label>
                        <select name="technician_user_id" required class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Teknisi --</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ $ticket->technician_user_id == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Instruksi Khusus (Opsional)</label>
                        <textarea name="note" rows="2" placeholder="Catatan instruksi penanganan untuk teknisi..." class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="showAssignModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition">Tugaskan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: UPDATE PROGRESS PERBAIKAN -->
        <div x-show="showProgressModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden" 
                 @click.away="showProgressModal = false">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-gray-900 font-bold text-base">Update Status & Progress Perbaikan</h3>
                    <button @click="showProgressModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('repair-tickets.update-progress', $ticket->id) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Status Tiket</label>
                        <select name="status" required class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                            <option value="Open" {{ $ticket->status === 'Open' ? 'selected' : '' }}>Open (Belum Dimulai)</option>
                            <option value="In Progress" {{ $ticket->status === 'In Progress' ? 'selected' : '' }}>In Progress (Sedang Dikerjakan)</option>
                            <option value="Pending Part" {{ $ticket->status === 'Pending Part' ? 'selected' : '' }}>Pending Part (Menunggu Suku Cadang)</option>
                            <option value="Resolved" {{ $ticket->status === 'Resolved' ? 'selected' : '' }}>Resolved (Selesai Diperbaiki)</option>
                            <option value="Closed" {{ $ticket->status === 'Closed' ? 'selected' : '' }}>Closed (Ditutup)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Persentase Progress (0 - 100%)</label>
                        <input type="number" name="progress_percent" min="0" max="100" value="{{ $ticket->progress_percent }}" required class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Tindakan Teknisi</label>
                        <textarea name="technician_notes" rows="3" placeholder="Tindakan yang telah dilakukan, part yang diganti, atau kendala..." class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">{{ $ticket->technician_notes }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="showProgressModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition">Simpan Progress</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
