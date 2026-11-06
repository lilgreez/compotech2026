<x-app-layout>
    <x-slot name="title">DAS - Dashboard</x-slot>

    <!-- Operational Header Bar -->
    <div class="bg-white border-b border-gray-200 px-6 py-5">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">Ringkasan Operasional Pabrik</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                        Mission Control
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1 flex items-center space-x-2">
                    <span class="font-medium text-gray-700">{{ auth()->user()->name }} (Role: {{ auth()->user()->role_name }})</span>
                    <span>•</span>
                    <span id="realtimeClock" class="tabular-nums font-mono text-gray-500">Memuat waktu...</span>
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
                <a href="{{ route('repair-tickets.index') }}" class="px-3.5 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition shadow-sm inline-flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Ajukan Tiket Perbaikan
                </a>
                <a href="{{ route('dieset-status.export') }}" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-sm inline-flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Status Mold
                </a>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <!-- 4 Core Operational KPI Metric Cards (Industrial Clean Grid) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Card 1: Total Mold -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-gray-300 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Cetakan Mold</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1 tabular-nums">{{ number_format($totalMolds) }}</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="text-emerald-700 font-semibold tabular-nums">{{ number_format($activeMolds) }} Aktif</span>, 
                            <span class="text-amber-700 tabular-nums">{{ number_format($naMolds) }} Non-Aktif</span>
                        </p>
                    </div>
                    <div class="p-3 bg-slate-100 rounded-lg text-slate-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('dieset-status.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 inline-flex items-center">
                        Buka Mold Status <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Card 2: Low Stock Parts -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-gray-300 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Stok Suku Cadang</p>
                        <h3 class="text-2xl font-bold {{ $lowStockParts > 0 ? 'text-red-600' : 'text-gray-900' }} mt-1 tabular-nums">
                            {{ number_format($lowStockParts) }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="{{ $lowStockParts > 0 ? 'text-red-700 font-semibold' : 'text-gray-500' }}">Kritis (<= 2 pcs)</span>, 
                            <span class="text-emerald-700 font-semibold tabular-nums">{{ number_format($safeStockParts) }} Aman</span>
                        </p>
                    </div>
                    <div class="p-3 {{ $lowStockParts > 0 ? 'bg-red-50 text-red-600' : 'bg-gray-100 text-gray-500' }} rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('parts-stock.index', ['tab' => 'low']) }}" class="text-xs font-semibold {{ $lowStockParts > 0 ? 'text-red-600 hover:text-red-800' : 'text-blue-600 hover:text-blue-800' }} inline-flex items-center">
                        Cek Daftar Stok Kritis <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Card 3: Tiket Perbaikan Aktif -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-gray-300 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Tiket Perbaikan Mold</p>
                        <h3 class="text-2xl font-bold text-amber-600 mt-1 tabular-nums">{{ number_format($activeTicketsCount) }}</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($urgentTicketsCount > 0)
                                <span class="text-red-600 font-bold tabular-nums">{{ $urgentTicketsCount }} Urgent</span>, Dalam Penanganan
                            @else
                                <span class="text-gray-600">Tidak ada tiket urgent</span>
                            @endif
                        </p>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('repair-tickets.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 inline-flex items-center">
                        Buka Antrian Tiket <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Card 4: SPK & Inspeksi -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-gray-300 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">SPK & Inspeksi</p>
                        <h3 class="text-2xl font-bold text-purple-600 mt-1 tabular-nums">{{ number_format($activeSpkCount) }}</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="text-purple-700 font-semibold tabular-nums">{{ $activeSpkCount }} SPK Berjalan</span>, 
                            <span class="text-gray-600 tabular-nums">{{ number_format($inspections30Days) }} Inspeksi/30hr</span>
                        </p>
                    </div>
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('mold-requests.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 inline-flex items-center">
                        Lihat Tracker SPK <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

        </div>

        <!-- Main Workspace: 2-Column Industrial Split -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left 2 Cols: Activity Feed & Frequency Trends -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Table: Aktivitas Inspeksi Lapangan Terbaru -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50/50 flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Aktivitas Inspeksi Lapangan Terbaru</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Catatan pemeriksaan fisik cetakan pasca proses injeksi</p>
                        </div>
                        <a href="{{ route('monitoring') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 inline-flex items-center">
                            Lihat Semua <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left whitespace-nowrap">
                            <thead class="text-[11px] text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-4 py-3 font-semibold border-r border-gray-100">Waktu</th>
                                    <th scope="col" class="px-4 py-3 font-semibold border-r border-gray-100">Mold & Komponen</th>
                                    <th scope="col" class="px-4 py-3 font-semibold text-center border-r border-gray-100">Kondisi</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Operator / Mekanik</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentInspections as $insp)
                                    <tr class="bg-white hover:bg-slate-50 transition">
                                        <td class="px-4 py-3 border-r border-gray-100 text-gray-600 tabular-nums">
                                            <div class="font-medium text-gray-900">{{ $insp->inspection_date->format('d M Y') }}</div>
                                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $insp->inspection_date->format('H:i') }} WIB</div>
                                        </td>
                                        
                                        <td class="px-4 py-3 border-r border-gray-100">
                                            <p class="font-bold text-gray-900">{{ $insp->part->dieset->name ?? 'Mold' }}</p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">{{ $insp->part->part_code ?? '-' }} : {{ $insp->part->name ?? '-' }}</p>
                                        </td>
                                        
                                        <td class="px-4 py-3 text-center border-r border-gray-100">
                                            @php
                                                $condStyle = match($insp->condition) {
                                                    'OK' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'Repair' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    'Replace' => 'bg-red-50 text-red-700 border-red-200',
                                                    default => 'bg-gray-50 text-gray-700 border-gray-200'
                                                };
                                            @endphp
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[11px] font-bold border {{ $condStyle }}">
                                                {{ $insp->condition }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-4 py-3 text-gray-700">
                                            <div class="font-medium">{{ $insp->operator->name ?? 'Mekanik' }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-xs">
                                            Belum ada rekaman inspeksi lapangan pada sistem.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Industrial Chart: Frekuensi Inspeksi 7 Hari Terakhir -->
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Aktivitas Inspeksi 7 Hari Terakhir</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Distribusi volume inspeksi per hari</p>
                        </div>
                        <span class="text-xs font-semibold text-gray-400">Total 7 Hari: {{ array_sum($chartData) }} kali</span>
                    </div>

                    <!-- Pure Tailwind Responsive Bar Visualization (Fast, Zero External CDN lag) -->
                    <div class="grid grid-cols-7 gap-3 pt-6 pb-2 items-end h-44 border-b border-gray-100">
                        @foreach($chartDates as $idx => $label)
                            @php
                                $val = $chartData[$idx] ?? 0;
                                $pct = $maxChartVal > 0 ? max(8, round(($val / $maxChartVal) * 100)) : 8;
                            @endphp
                            <div class="flex flex-col items-center h-full justify-end group">
                                <span class="text-[10px] font-bold text-gray-600 mb-1 tabular-nums group-hover:text-blue-600 transition">
                                    {{ $val }}
                                </span>
                                <div class="w-full max-w-[32px] bg-slate-100 rounded-t-md relative flex items-end h-28 overflow-hidden">
                                    <div class="w-full rounded-t-md {{ $val > 0 ? 'bg-blue-600 group-hover:bg-blue-700' : 'bg-slate-200' }} transition-all duration-300" style="height: {{ $pct }}%"></div>
                                </div>
                                <span class="text-[10px] text-gray-500 mt-2 font-mono whitespace-nowrap">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right 1 Col: Urgent Queue & Operations Shortcuts -->
            <div class="space-y-6">

                <!-- Panel: Antrian Tiket Perbaikan Mendesak (Priority Queue) -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50/50 flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <h3 class="text-sm font-bold text-gray-900">Antrian Perbaikan Mold</h3>
                            @if($activeTicketsCount > 0)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                    {{ $activeTicketsCount }}
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('repair-tickets.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">Semua</a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse($recentTickets as $ticket)
                            <div class="p-4 hover:bg-slate-50 transition">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('repair-tickets.show', $ticket->id) }}" class="font-mono font-bold text-xs text-blue-600 hover:underline truncate">
                                                {{ $ticket->ticket_number }}
                                            </a>
                                            @if($ticket->priority === 'Urgent')
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-red-100 text-red-800">Urgent</span>
                                            @elseif($ticket->priority === 'High')
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800">High</span>
                                            @endif
                                        </div>
                                        <p class="text-xs font-semibold text-gray-800 mt-1 truncate">{{ $ticket->dieset ? $ticket->dieset->name : 'N/A' }}</p>
                                        <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-1">{{ $ticket->issue_description }}</p>
                                    </div>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $ticket->status === 'In Progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $ticket->status }}
                                    </span>
                                </div>
                                <div class="mt-2.5 flex items-center justify-between text-[11px] text-gray-500">
                                    <span>PIC: <strong class="text-gray-700">{{ $ticket->technician ? $ticket->technician->name : 'Belum Ada' }}</strong></span>
                                    <span class="tabular-nums font-bold text-gray-700">{{ $ticket->progress_percent }}%</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-400 text-xs">
                                Seluruh cetakan mold beroperasi normal tanpa tiket perbaikan pending.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Panel: SPK Pembuatan Mold Berjalan -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-gray-900">SPK Mold Baru Berjalan</h3>
                        <a href="{{ route('mold-requests.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">Semua</a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse($activeSpks as $spk)
                            <div class="p-4 hover:bg-slate-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <a href="{{ route('mold-requests.show', $spk->id) }}" class="font-mono text-xs font-bold text-blue-600 hover:underline">
                                            {{ $spk->spk_number }}
                                        </a>
                                        <h4 class="text-xs font-bold text-gray-900 mt-0.5">{{ $spk->mold_name }}</h4>
                                        <p class="text-[11px] text-gray-500">Klien: {{ $spk->customer }}</p>
                                    </div>
                                    <span class="text-xs font-bold text-gray-900 tabular-nums">{{ $spk->total_progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full {{ $spk->total_progress >= 75 ? 'bg-emerald-500' : 'bg-blue-600' }}" style="width: {{ $spk->total_progress }}%"></div>
                                </div>
                                <div class="mt-2 text-[10px] text-gray-400 flex justify-between">
                                    <span>PIC: {{ $spk->pic_name ?: '-' }}</span>
                                    <span>Target: {{ $spk->target_date ? $spk->target_date->format('d M Y') : '-' }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-400 text-xs">
                                Tidak ada SPK pembuatan mold baru yang sedang berjalan.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Panel: Administrator & Governance Shortcuts -->
                @if(auth()->user()->hasRole('Admin'))
                <div class="bg-slate-900 text-white rounded-xl p-5 shadow-sm border border-slate-800">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Admin System Control
                    </h4>

                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <a href="{{ route('audit-logs.index') }}" class="p-2.5 bg-slate-800 hover:bg-slate-700 rounded-lg text-slate-200 transition font-medium flex items-center justify-between">
                            <span>Audit Trail</span>
                            <span class="text-slate-500">→</span>
                        </a>
                        <a href="{{ route('rbac.index') }}" class="p-2.5 bg-slate-800 hover:bg-slate-700 rounded-lg text-slate-200 transition font-medium flex items-center justify-between">
                            <span>RBAC Roles</span>
                            <span class="text-slate-500">→</span>
                        </a>
                        <a href="{{ route('operators.index') }}" class="p-2.5 bg-slate-800 hover:bg-slate-700 rounded-lg text-slate-200 transition font-medium flex items-center justify-between">
                            <span>Akun Operator</span>
                            <span class="text-slate-500">→</span>
                        </a>
                        <a href="{{ route('email-reports.index') }}" class="p-2.5 bg-slate-800 hover:bg-slate-700 rounded-lg text-slate-200 transition font-medium flex items-center justify-between">
                            <span>Email Report</span>
                            <span class="text-slate-500">→</span>
                        </a>
                    </div>
                </div>
                @endif

            </div>

        </div>

    </div>

    <!-- Micro-Script: Realtime Clock (Native, Lightweight) -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function updateClock() {
                const now = new Date();
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                
                const dayName = days[now.getDay()];
                const day = String(now.getDate()).padStart(2, '0');
                const month = months[now.getMonth()];
                const year = now.getFullYear();
                
                const h = String(now.getHours()).padStart(2, '0');
                const m = String(now.getMinutes()).padStart(2, '0');
                const s = String(now.getSeconds()).padStart(2, '0');
                
                const clockElement = document.getElementById('realtimeClock');
                if (clockElement) {
                    clockElement.innerText = `${dayName}, ${day} ${month} ${year} | ${h}:${m}:${s} WIB`;
                }
            }
            setInterval(updateClock, 1000);
            updateClock();
        });
    </script>
</x-app-layout>