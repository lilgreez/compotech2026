<x-app-layout>
    <x-slot name="title">DAS - Tiket Perbaikan Mold</x-slot>

    <div x-data="{ showCreateModal: false }">
        <!-- Page Header -->
        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center space-x-3.5">
                <div class="p-2.5 bg-slate-900 rounded-lg text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-900 leading-tight">Tiket Perbaikan Mold (Repair Ticketing)</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Pengajuan komplain kerusakan mold antar departemen, penugasan teknisi, dan pemantauan perbaikan</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2.5 w-full md:w-auto">
                <button type="button" @click="showCreateModal = true" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium flex items-center justify-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
                    Ajukan Tiket Perbaikan
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

            <!-- KPI Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Total Tiket -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Tiket Masuk</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1.5 tabular-nums">{{ number_format($totalTickets) }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Seluruh pengajuan</p>
                        </div>
                        <div class="p-3 bg-slate-100 rounded-lg text-slate-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Open / Antrian -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Menunggu Penugasan</p>
                            <h3 class="text-2xl font-bold text-amber-600 mt-1.5 tabular-nums">{{ number_format($openTickets) }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Status Open</p>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-lg text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Dalam Pengerjaan -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Sedang Diperbaiki</p>
                            <h3 class="text-2xl font-bold text-blue-600 mt-1.5 tabular-nums">{{ number_format($inProgressTickets) }}</h3>
                            <p class="text-xs text-gray-500 mt-1">In Progress / Pending Part</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Selesai -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Perbaikan Selesai</p>
                            <h3 class="text-2xl font-bold text-emerald-600 mt-1.5 tabular-nums">{{ number_format($resolvedTickets) }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Mold siap produksi kembali</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                
                <!-- Filter Bar -->
                <div class="p-5 border-b border-gray-200 bg-gray-50/50 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
                    <form method="GET" action="{{ route('repair-tickets.index') }}" class="w-full flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
                        
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-semibold uppercase text-gray-500">Status:</span>
                                <select name="status" class="border-gray-300 rounded-lg py-1.5 px-3 text-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm cursor-pointer" onchange="this.form.submit()">
                                    <option value="all" {{ ($statusFilter ?? 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="Open" {{ ($statusFilter ?? '') == 'Open' ? 'selected' : '' }}>Open</option>
                                    <option value="In Progress" {{ ($statusFilter ?? '') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Pending Part" {{ ($statusFilter ?? '') == 'Pending Part' ? 'selected' : '' }}>Pending Part</option>
                                    <option value="Resolved" {{ ($statusFilter ?? '') == 'Resolved' ? 'selected' : '' }}>Resolved / Selesai</option>
                                </select>
                            </div>

                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-semibold uppercase text-gray-500">Prioritas:</span>
                                <select name="priority" class="border-gray-300 rounded-lg py-1.5 px-3 text-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm cursor-pointer" onchange="this.form.submit()">
                                    <option value="all" {{ ($priorityFilter ?? 'all') == 'all' ? 'selected' : '' }}>Semua Prioritas</option>
                                    <option value="Urgent" {{ ($priorityFilter ?? '') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                                    <option value="High" {{ ($priorityFilter ?? '') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ ($priorityFilter ?? '') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ ($priorityFilter ?? '') == 'Low' ? 'selected' : '' }}>Low</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Search Box -->
                        <div class="relative w-full md:w-80">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 pr-8 py-2 shadow-sm transition" placeholder="Cari No. Tiket, Mold, Deskripsi..." onkeydown="if(event.key === 'Enter') this.form.submit()">
                            @if($search)
                                <a href="{{ route('repair-tickets.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 font-semibold text-center border-r border-gray-200 w-16">No.</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">No. Tiket</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">Mold Bermasalah</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold text-center border-r border-gray-200">Prioritas</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">Pelapor / Tanggal</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">Teknisi PIC</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200 w-36">Progress</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold text-center border-r border-gray-200">Status</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold text-center min-w-[120px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($tickets as $index => $ticket)
                                <tr class="bg-white hover:bg-slate-50/80 transition-colors {{ $ticket->priority === 'Urgent' ? 'bg-red-50/20' : '' }}">
                                    <td class="px-5 py-3.5 text-center text-gray-500 border-r border-gray-100 tabular-nums">
                                        {{ $tickets->firstItem() + $index }}
                                    </td>
                                    <td class="px-5 py-3.5 border-r border-gray-100 font-mono font-bold text-gray-900 text-xs">
                                        <a href="{{ route('repair-tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $ticket->ticket_number }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3.5 border-r border-gray-100">
                                        <div class="font-semibold text-gray-900">{{ $ticket->dieset ? $ticket->dieset->name : 'N/A' }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5 truncate max-w-xs" title="{{ $ticket->issue_description }}">{{ $ticket->issue_description }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 text-center border-r border-gray-100">
                                        @if($ticket->priority === 'Urgent')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800">
                                                Urgent
                                            </span>
                                        @elseif($ticket->priority === 'High')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">
                                                High
                                            </span>
                                        @elseif($ticket->priority === 'Medium')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                                Medium
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700">
                                                Low
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 border-r border-gray-100 text-xs">
                                        <div class="font-semibold text-gray-800">{{ $ticket->reporter ? $ticket->reporter->name : 'Operator' }}</div>
                                        <div class="text-gray-400 tabular-nums">{{ $ticket->created_at->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 border-r border-gray-100 text-xs text-gray-700">
                                        {{ $ticket->technician ? $ticket->technician->name : 'Belum Ditugaskan' }}
                                    </td>
                                    <td class="px-5 py-3.5 border-r border-gray-100">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1 bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                                <div class="h-1.5 rounded-full {{ $ticket->progress_percent >= 100 ? 'bg-emerald-500' : 'bg-blue-600' }}" style="width: {{ $ticket->progress_percent }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-gray-700 tabular-nums">{{ $ticket->progress_percent }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-center border-r border-gray-100">
                                        @if($ticket->status === 'Resolved' || $ticket->status === 'Closed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                Selesai
                                            </span>
                                        @elseif($ticket->status === 'In Progress')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                In Progress
                                            </span>
                                        @elseif($ticket->status === 'Pending Part')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                                Menunggu Part
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                Open
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <a href="{{ route('repair-tickets.show', $ticket->id) }}" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded text-xs font-medium inline-flex items-center shadow-sm transition">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="font-medium text-gray-700">Tidak ada tiket perbaikan</p>
                                            <p class="text-xs text-gray-400 mt-1">Seluruh cetakan mold dalam kondisi operasional optimal.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-500 gap-4">
                    <div>
                        Menampilkan <span class="font-semibold text-gray-900 tabular-nums">{{ $tickets->firstItem() ?? 0 }}</span> sampai <span class="font-semibold text-gray-900 tabular-nums">{{ $tickets->lastItem() ?? 0 }}</span> dari total <span class="font-semibold text-gray-900 tabular-nums">{{ number_format($tickets->total()) }}</span> tiket
                    </div>
                    <div>
                        {{ $tickets->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: AJUKAN TIKET PERBAIKAN -->
        <div x-show="showCreateModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-xl overflow-hidden" 
                 @click.away="showCreateModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-gray-900 font-bold text-base flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Pengajuan Tiket Perbaikan Mold
                    </h3>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('repair-tickets.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Pilih Mold Bermasalah <span class="text-red-500">*</span></label>
                        <select name="dieset_id" required class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Mold --</option>
                            @foreach($molds as $mold)
                                <option value="{{ $mold->id }}">{{ $mold->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Tingkat Prioritas <span class="text-red-500">*</span></label>
                        <select name="priority" required class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                            <option value="Medium" selected>Medium (Standard Maintenance)</option>
                            <option value="Low">Low (Bisa Dijadwalkan)</option>
                            <option value="High">High (Perlu Ditangani Segera)</option>
                            <option value="Urgent">Urgent (Mesin Stop / Kritis)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Deskripsi Kerusakan / Gejala <span class="text-red-500">*</span></label>
                        <textarea name="issue_description" required rows="3" placeholder="Jelaskan secara detail kerusakan, komponen yang aus/patah, atau kendala operasional..." class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Upload Foto Bukti Kerusakan (Opsional)</label>
                        <input type="file" name="evidence_photo" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-[11px] text-gray-400 mt-1">Maks. 3MB. Format: JPG, PNG, WEBP.</p>
                    </div>

                    <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition">Ajukan Tiket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
