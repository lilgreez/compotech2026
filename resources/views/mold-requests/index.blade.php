<x-app-layout>
    <x-slot name="title">DAS - SPK Mold Baru</x-slot>

    <div x-data="{ showCreateModal: false }">
        <!-- Page Header -->
        <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center space-x-3.5">
                <div class="p-2.5 bg-slate-900 rounded-lg text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-900 leading-tight">SPK Mold Baru & Progress Tracking</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Penerbitan SPK pembuatan mold baru, checklist kebutuhan material, dan pelacakan progress tahapan</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2.5 w-full md:w-auto">
                <button type="button" @click="showCreateModal = true" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium flex items-center justify-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
                    Terbitkan SPK Baru
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
                <!-- Card 1: Total SPK -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total SPK Terbit</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1.5 tabular-nums">{{ number_format($totalSpk) }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Akumulasi pembuatan mold</p>
                        </div>
                        <div class="p-3 bg-slate-100 rounded-lg text-slate-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Sedang Dikerjakan -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Sedang Dikerjakan</p>
                            <h3 class="text-2xl font-bold text-blue-600 mt-1.5 tabular-nums">{{ number_format($inProgressSpk) }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Dalam proses produksi</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Selesai -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">SPK Selesai</p>
                            <h3 class="text-2xl font-bold text-emerald-600 mt-1.5 tabular-nums">{{ number_format($completedSpk) }}</h3>
                            <p class="text-xs text-gray-500 mt-1">100% tahapan rampung</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Rata-rata Progress -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Rata-rata Progress</p>
                            <h3 class="text-2xl font-bold text-purple-600 mt-1.5 tabular-nums">{{ $avgProgress }}%</h3>
                            <p class="text-xs text-gray-500 mt-1">Tingkat penyelesaian aktif</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                
                <!-- Filter Bar -->
                <div class="p-5 border-b border-gray-200 bg-gray-50/50 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
                    <form method="GET" action="{{ route('mold-requests.index') }}" class="w-full flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
                        
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-semibold uppercase text-gray-500">Status:</span>
                            <select name="status" class="border-gray-300 rounded-lg py-1.5 px-3 text-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm cursor-pointer" onchange="this.form.submit()">
                                <option value="all" {{ ($statusFilter ?? 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="In Progress" {{ ($statusFilter ?? '') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ ($statusFilter ?? '') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Draft" {{ ($statusFilter ?? '') == 'Draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        
                        <!-- Search Box -->
                        <div class="relative w-full md:w-80">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 pr-8 py-2 shadow-sm transition" placeholder="Cari No. SPK, Mold, Customer..." onkeydown="if(event.key === 'Enter') this.form.submit()">
                            @if($search)
                                <a href="{{ route('mold-requests.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
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
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">No. SPK</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">Nama Mold & Customer</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">PIC Pelaksana</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200">Target Selesai</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold border-r border-gray-200 w-48">Progress Pengerjaan</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold text-center border-r border-gray-200">Status</th>
                                <th scope="col" class="px-5 py-3.5 font-semibold text-center min-w-[120px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($moldRequests as $index => $spk)
                                <tr class="bg-white hover:bg-slate-50/80 transition-colors">
                                    <td class="px-5 py-3.5 text-center text-gray-500 border-r border-gray-100 tabular-nums">
                                        {{ $moldRequests->firstItem() + $index }}
                                    </td>
                                    <td class="px-5 py-3.5 border-r border-gray-100 font-mono font-bold text-gray-900 text-xs">
                                        <a href="{{ route('mold-requests.show', $spk->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $spk->spk_number }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3.5 border-r border-gray-100">
                                        <div class="font-semibold text-gray-900">{{ $spk->mold_name }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $spk->customer }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 border-r border-gray-100 text-gray-700">
                                        {{ $spk->pic_name ?: '-' }}
                                    </td>
                                    <td class="px-5 py-3.5 border-r border-gray-100 text-gray-600 text-xs tabular-nums">
                                        {{ $spk->target_date ? $spk->target_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-5 py-3.5 border-r border-gray-100">
                                        <div class="flex items-center space-x-2.5">
                                            <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div class="h-2 rounded-full {{ $spk->total_progress >= 100 ? 'bg-emerald-500' : ($spk->total_progress >= 50 ? 'bg-blue-600' : 'bg-amber-500') }} transition-all duration-300" style="width: {{ $spk->total_progress }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-gray-800 tabular-nums w-9 text-right">{{ $spk->total_progress }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-center border-r border-gray-100">
                                        @if($spk->status === 'Completed')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Selesai
                                            </span>
                                        @elseif($spk->status === 'In Progress')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                In Progress
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                                {{ $spk->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <div class="flex justify-center items-center space-x-2">
                                            <a href="{{ route('mold-requests.show', $spk->id) }}" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded text-xs font-medium inline-flex items-center shadow-sm transition">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                Tracker
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            <p class="font-medium text-gray-700">Belum ada data SPK Pembuatan Mold</p>
                                            <p class="text-xs text-gray-400 mt-1">Terbitkan SPK baru untuk memulai pengerjaan dan pelacakan progress.</p>
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
                        Menampilkan <span class="font-semibold text-gray-900 tabular-nums">{{ $moldRequests->firstItem() ?? 0 }}</span> sampai <span class="font-semibold text-gray-900 tabular-nums">{{ $moldRequests->lastItem() ?? 0 }}</span> dari total <span class="font-semibold text-gray-900 tabular-nums">{{ number_format($moldRequests->total()) }}</span> SPK
                    </div>
                    <div>
                        {{ $moldRequests->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: TERBITKAN SPK BARU -->
        <div x-show="showCreateModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]" 
                 @click.away="showCreateModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-gray-900 font-bold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Penerbitan SPK Pembuatan Mold Baru
                    </h3>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('mold-requests.store') }}" method="POST" class="flex-1 overflow-y-auto p-6 space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nomor SPK <span class="text-red-500">*</span></label>
                            <input type="text" name="spk_number" required placeholder="Contoh: SPK-2026-001" class="w-full font-mono border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Mold / Part Mold <span class="text-red-500">*</span></label>
                            <input type="text" name="mold_name" required placeholder="Contoh: MOLD CRANKCASE LH K56" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Customer / Klien <span class="text-red-500">*</span></label>
                            <input type="text" name="customer" required placeholder="Contoh: PT. Astra Honda Motor" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">PIC Pelaksana / Tooling</label>
                            <input type="text" name="pic_name" placeholder="Nama teknisi / tim tooling..." class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" required value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Target Selesai <span class="text-red-500">*</span></label>
                            <input type="date" name="target_date" required value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Spesifikasi Tambahan</label>
                        <textarea name="notes" rows="2" placeholder="Catatan material, dimensi, atau persyaratan khusus..." class="w-full border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm"></textarea>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-800">
                        <span class="font-bold">Info Tahapan Pengerjaan Otomatis:</span> SPK akan otomatis dipecah ke 5 tahapan standar sesuai BRD: <span class="font-semibold">Desain (20%) → Machining (30%) → Assembly (20%) → Trial (15%) → Finishing (15%)</span>.
                    </div>

                    <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold flex items-center hover:bg-blue-700 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Terbitkan SPK
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
