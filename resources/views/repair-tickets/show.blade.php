<x-app-layout>
    <x-slot name="title">DAS - Tiket Perbaikan {{ $ticket->ticket_number }}</x-slot>

    <div x-data="{ 
        showAssignModal: false, 
        showProgressModal: false,
        progressValue: {{ $ticket->progress_percent }}
    }">
        <!-- Page Header -->
        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center space-x-3.5">
                <a href="{{ route('repair-tickets.index') }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition" title="Kembali ke Daftar Tiket">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h2 class="font-bold text-xl text-gray-900 leading-tight font-mono">{{ $ticket->ticket_number }}</h2>
                        
                        <!-- Status Badge -->
                        @if($ticket->status === 'Resolved' || $ticket->status === 'Closed')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Selesai
                            </span>
                        @elseif($ticket->status === 'In Progress')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                In Progress
                            </span>
                        @elseif($ticket->status === 'Pending Part')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800">
                                <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                Menunggu Suku Cadang
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Open (Antrian)
                            </span>
                        @endif

                        <!-- Priority Badge -->
                        @if($ticket->priority === 'Urgent')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-ping"></span>
                                Prioritas: Urgent
                            </span>
                        @elseif($ticket->priority === 'High')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                Prioritas: High
                            </span>
                        @elseif($ticket->priority === 'Medium')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                Prioritas: Medium
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                Prioritas: Low
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2 text-xs text-gray-500 mt-1">
                        @if($ticket->dieset && $ticket->dieset->kode_mold)
                            <span class="px-1.5 py-0.5 bg-slate-100 text-slate-800 rounded font-mono font-bold text-[11px] border border-slate-200">
                                {{ $ticket->dieset->kode_mold }}
                            </span>
                        @endif
                        <span class="font-semibold text-gray-700">{{ $ticket->dieset ? $ticket->dieset->name : 'N/A' }}</span>
                        @if($ticket->dieset && $ticket->dieset->ownership)
                            <span class="text-gray-300">•</span>
                            <span class="uppercase">{{ $ticket->dieset->ownership }}</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2.5 w-full md:w-auto">
                <button type="button" @click="showAssignModal = true" class="w-full sm:w-auto px-3.5 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition shadow-sm inline-flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>{{ $ticket->technician_user_id ? 'Ganti Teknisi PIC' : 'Tugaskan Teknisi' }}</span>
                </button>

                <button type="button" @click="showProgressModal = true" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition shadow-sm inline-flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Update Progress</span>
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
                <!-- Left 2 Cols: Ticket Issue, Technician Work, & Evidence -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Detail Permasalahan -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-gray-100">
                            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                <div class="p-1.5 bg-rose-50 text-rose-600 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                Deskripsi Kerusakan & Gejala Lapangan
                            </h3>
                            <span class="text-xs text-gray-400 font-mono tabular-nums">
                                Dilaporkan: {{ $ticket->created_at->format('d M Y H:i') }} WIB
                            </span>
                        </div>

                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4.5 text-slate-800 text-xs sm:text-sm leading-relaxed whitespace-pre-line font-medium">
                            {{ $ticket->issue_description }}
                        </div>

                        @if($ticket->part)
                            <div class="mt-4 pt-3.5 border-t border-gray-100 flex items-center gap-2 text-xs">
                                <span class="text-gray-400 font-medium">Komponen Terdampak:</span>
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded font-semibold border border-blue-100">
                                    [{{ $ticket->part->part_code }}] {{ $ticket->part->part_name }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Progress Penanganan & Catatan Teknisi -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-gray-100">
                            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                                </div>
                                Status Penanganan & Tindakan Terakhir
                            </h3>
                            <button type="button" @click="showProgressModal = true" class="text-xs text-blue-600 font-semibold hover:text-blue-800 transition">
                                + Perbarui Status
                            </button>
                        </div>

                        <!-- Progress Bar with Percentage -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-semibold text-gray-600 uppercase tracking-wider text-[11px]">Tingkat Penyelesaian Perbaikan</span>
                                <span class="font-mono font-bold text-gray-900 tabular-nums text-sm">{{ $ticket->progress_percent }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                <div class="h-3 rounded-full transition-all duration-500 {{ $ticket->progress_percent >= 100 ? 'bg-emerald-500' : ($ticket->progress_percent >= 50 ? 'bg-blue-600' : 'bg-amber-500') }}" 
                                     style="width: {{ $ticket->progress_percent }}%"></div>
                            </div>
                        </div>

                        <!-- Catatan Teknisi -->
                        <div class="mt-5 pt-4 border-t border-gray-100">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 block mb-2">Catatan Tindakan Teknisi:</span>
                            @if($ticket->technician_notes)
                                <div class="bg-blue-50/60 border border-blue-100 rounded-xl p-4 text-xs text-slate-800 leading-relaxed font-medium">
                                    {{ $ticket->technician_notes }}
                                </div>
                            @else
                                <div class="p-3 bg-gray-50 rounded-lg text-xs text-gray-400 italic border border-dashed border-gray-200">
                                    Belum ada catatan teknis penanganan yang dicatat oleh teknisi.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Foto Bukti Kerusakan -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-gray-100">
                            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                <div class="p-1.5 bg-slate-100 text-slate-700 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                Foto Bukti Kerusakan Fisik
                            </h3>
                        </div>

                        @if($ticket->evidence_photo)
                            <div class="max-w-lg">
                                <a href="{{ asset('storage/'.$ticket->evidence_photo) }}" target="_blank" class="block group relative overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                                    <img src="{{ asset('storage/'.$ticket->evidence_photo) }}" alt="Foto Bukti Kerusakan" class="w-full h-auto object-cover group-hover:scale-102 transition duration-300">
                                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-semibold gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                        Buka Ukuran Penuh
                                    </div>
                                </a>
                                <p class="text-[11px] text-gray-400 mt-2">Lampiran foto dokumentasi saat tiket pertama kali diajukan.</p>
                            </div>
                        @else
                            <div class="p-8 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200 text-gray-400 text-xs">
                                <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="font-medium text-gray-600">Tidak ada lampiran foto bukti</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Pelapor tidak mengunggah file foto saat pengajuan tiket ini.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Col: Metadata Specs & Timeline Penanganan -->
                <div class="space-y-6">
                    
                    <!-- Metadata Panel -->
                    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3.5 text-xs">
                        <h4 class="font-bold text-gray-900 text-sm pb-2.5 border-b border-gray-100 flex items-center justify-between">
                            <span>Informasi Spesifikasi</span>
                            <span class="text-[10px] text-gray-400 font-mono font-normal">SLA Tracker</span>
                        </h4>

                        <div>
                            <span class="text-gray-400 uppercase text-[10px] font-semibold tracking-wider block">Cetakan Mold:</span>
                            <div class="flex items-center space-x-2 mt-1">
                                @if($ticket->dieset && $ticket->dieset->kode_mold)
                                    <span class="px-1.5 py-0.5 bg-slate-100 text-slate-800 rounded font-mono font-bold text-[11px] border border-slate-200">
                                        {{ $ticket->dieset->kode_mold }}
                                    </span>
                                @endif
                                <span class="font-bold text-gray-900 text-xs">{{ $ticket->dieset ? $ticket->dieset->name : 'N/A' }}</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-gray-400 uppercase text-[10px] font-semibold tracking-wider block">Ownership / Pemilik:</span>
                            <span class="font-semibold text-gray-800 text-xs mt-0.5 block">{{ $ticket->dieset && $ticket->dieset->ownership ? $ticket->dieset->ownership : 'Internal Compotec' }}</span>
                        </div>

                        <div>
                            <span class="text-gray-400 uppercase text-[10px] font-semibold tracking-wider block">Pelapor Kerusakan:</span>
                            <div class="flex items-center space-x-1.5 mt-1">
                                <div class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-[10px]">
                                    {{ substr($ticket->reporter ? $ticket->reporter->name : 'O', 0, 1) }}
                                </div>
                                <span class="font-semibold text-gray-800 text-xs">{{ $ticket->reporter ? $ticket->reporter->name : 'Operator' }}</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-gray-400 uppercase text-[10px] font-semibold tracking-wider block">Teknisi Penanggung Jawab:</span>
                            @if($ticket->technician)
                                <div class="flex items-center space-x-1.5 mt-1">
                                    <div class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-[10px]">
                                        {{ substr($ticket->technician->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-blue-600 text-xs">{{ $ticket->technician->name }}</span>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 mt-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Belum Ditugaskan
                                </span>
                            @endif
                        </div>

                        <div>
                            <span class="text-gray-400 uppercase text-[10px] font-semibold tracking-wider block">Waktu Pengajuan:</span>
                            <span class="font-medium text-gray-700 font-mono tabular-nums mt-0.5 block">{{ $ticket->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>

                        @if($ticket->resolved_at)
                            <div class="pt-2 border-t border-gray-100">
                                <span class="text-gray-400 uppercase text-[10px] font-semibold tracking-wider block">Waktu Selesai (Resolved):</span>
                                <span class="font-bold text-emerald-600 font-mono tabular-nums mt-0.5 block">{{ $ticket->resolved_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                        @endif
                    </div>

                    <!-- Timeline Audit Logs -->
                    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                        <h4 class="font-bold text-gray-900 text-sm mb-4 pb-2.5 border-b border-gray-100 flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Timeline Penanganan
                            </span>
                            <span class="text-[11px] font-mono tabular-nums text-gray-400 font-normal">
                                {{ $ticket->logs->count() }} Aktivitas
                            </span>
                        </h4>

                        <div class="relative pl-5 border-l-2 border-gray-200 space-y-4">
                            @forelse($ticket->logs as $log)
                                <div class="relative">
                                    <div class="absolute -left-[27px] top-0.5 w-3 h-3 rounded-full bg-blue-600 ring-4 ring-white"></div>
                                    <div class="text-xs">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-gray-800">{{ $log->action }}</span>
                                            <span class="text-[10px] text-gray-400 font-mono tabular-nums">
                                                {{ $log->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        @if($log->note)
                                            <p class="text-gray-600 mt-1 leading-relaxed bg-gray-50 p-2 rounded border border-gray-100 text-[11px]">
                                                {{ $log->note }}
                                            </p>
                                        @endif
                                        <div class="text-[10px] text-gray-400 mt-1 font-medium">
                                            Eksekutor: <span class="text-gray-600">{{ $log->user ? $log->user->name : 'Sistem' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 italic">Belum ada riwayat aktivitas pada tiket ini.</p>
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
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-200" 
                 @click.away="showAssignModal = false">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-2">
                        <div class="p-1.5 bg-blue-100 text-blue-700 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h3 class="text-gray-900 font-bold text-sm">Penugasan Teknisi Maintenance</h3>
                    </div>
                    <button @click="showAssignModal = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('repair-tickets.assign', $ticket->id) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Pilih Teknisi Penanggung Jawab <span class="text-red-500">*</span></label>
                        <select name="technician_user_id" required class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 font-medium">
                            <option value="">-- Pilih Teknisi Maintenance --</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ $ticket->technician_user_id == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }} ({{ $tech->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Instruksi Khusus / Catatan (Opsional)</label>
                        <textarea name="note" rows="2" placeholder="Catatan instruksi khusus untuk teknisi (misal: koordinasi dengan operator shift 1)..." class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-3 border-t border-gray-100">
                        <button type="button" @click="showAssignModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition shadow-sm inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Tugaskan Teknisi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: UPDATE PROGRESS PERBAIKAN -->
        <div x-show="showProgressModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-200" 
                 @click.away="showProgressModal = false">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-2">
                        <div class="p-1.5 bg-blue-100 text-blue-700 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="text-gray-900 font-bold text-sm">Update Status & Progress Perbaikan</h3>
                    </div>
                    <button @click="showProgressModal = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('repair-tickets.update-progress', $ticket->id) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Status Tiket</label>
                        <select name="status" required class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 font-medium">
                            <option value="Open" {{ $ticket->status === 'Open' ? 'selected' : '' }}>Open (Belum Dimulai)</option>
                            <option value="In Progress" {{ $ticket->status === 'In Progress' ? 'selected' : '' }}>In Progress (Sedang Dikerjakan)</option>
                            <option value="Pending Part" {{ $ticket->status === 'Pending Part' ? 'selected' : '' }}>Pending Part (Menunggu Suku Cadang)</option>
                            <option value="Resolved" {{ $ticket->status === 'Resolved' ? 'selected' : '' }}>Resolved (Selesai Diperbaiki)</option>
                            <option value="Closed" {{ $ticket->status === 'Closed' ? 'selected' : '' }}>Closed (Ditutup)</option>
                        </select>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">Persentase Progress</label>
                            <span class="text-xs font-mono font-bold text-blue-600 tabular-nums" x-text="progressValue + '%'"></span>
                        </div>
                        <input type="range" min="0" max="100" step="5" x-model="progressValue" name="progress_percent" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                        <div class="flex justify-between gap-1.5 mt-2">
                            <button type="button" @click="progressValue = 25" class="px-2 py-1 text-[11px] bg-slate-100 hover:bg-slate-200 rounded text-slate-700 font-medium font-mono">25%</button>
                            <button type="button" @click="progressValue = 50" class="px-2 py-1 text-[11px] bg-slate-100 hover:bg-slate-200 rounded text-slate-700 font-medium font-mono">50%</button>
                            <button type="button" @click="progressValue = 75" class="px-2 py-1 text-[11px] bg-slate-100 hover:bg-slate-200 rounded text-slate-700 font-medium font-mono">75%</button>
                            <button type="button" @click="progressValue = 100" class="px-2 py-1 text-[11px] bg-emerald-100 hover:bg-emerald-200 rounded text-emerald-800 font-bold font-mono">100% Selesai</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Tindakan Teknisi</label>
                        <textarea name="technician_notes" rows="3" placeholder="Tindakan yang telah dilakukan, part yang diganti, atau kendala lapangan..." class="w-full border-gray-300 rounded-lg text-xs py-2 px-3 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400">{{ $ticket->technician_notes }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-3 border-t border-gray-100">
                        <button type="button" @click="showProgressModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition shadow-sm inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
