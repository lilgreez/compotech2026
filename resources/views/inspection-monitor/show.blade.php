<x-app-layout>
    {{-- Top Navigation & Header --}}
    <div class="px-6 py-5 bg-white border-b border-gray-200">
        <div class="max-w-[95rem] mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('monitoring', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                   class="p-2 text-gray-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors border border-gray-200" 
                   title="Kembali ke Daftar Inspection Monitor">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                
                <div class="w-10 h-10 rounded-lg bg-slate-900 flex items-center justify-center text-white shadow-sm ring-1 ring-slate-800">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl font-bold text-gray-900 tracking-tight">{{ $dieset->name }}</h2>
                        <span class="inline-flex items-center font-mono font-bold text-xs text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                            {{ $dieset->kode_mold ?? 'M-'.str_pad($dieset->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">Riwayat lengkap log inspeksi preventif, deteksi kerusakan, dan tindakan pergantian komponen part</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('repair-tickets.index') }}" 
                   class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-gray-300 rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Tiket Perbaikan
                </a>
                <a href="{{ route('dieset-status.show', $dieset->id) }}" 
                   class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-white bg-slate-900 rounded-lg hover:bg-slate-800 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Mold Status
                </a>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-[95rem] mx-auto space-y-6">
        
        {{-- Mold Technical Specifications & Inspection Overview Card --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-slate-50 border-b border-gray-200 px-6 py-3.5 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-900"></span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Spesifikasi Mold & Ringkasan Inspeksi</h3>
                </div>
                <div class="flex items-center space-x-3 text-xs">
                    <span class="text-gray-500">Total Log: <strong class="text-gray-900 font-mono">{{ $totalMoldInspections ?? 0 }}x</strong></span>
                    <span class="text-gray-300">|</span>
                    <span class="text-gray-500">OK: <strong class="text-emerald-600 font-mono">{{ $okCount ?? 0 }}</strong></span>
                    <span class="text-gray-300">|</span>
                    <span class="text-gray-500">Repair: <strong class="text-amber-600 font-mono">{{ $repairCount ?? 0 }}</strong></span>
                    <span class="text-gray-300">|</span>
                    <span class="text-gray-500">Replace: <strong class="text-rose-600 font-mono">{{ $replaceCount ?? 0 }}</strong></span>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-xs">
                <div>
                    <span class="text-gray-500 block mb-1">Ownership / Customer</span>
                    <p class="font-bold text-gray-900 text-sm">{{ $dieset->ownership ?: '-' }}</p>
                    <span class="text-gray-400 mt-1 block">Status: <strong class="text-gray-700">{{ $dieset->status ?: 'Active' }}</strong></span>
                </div>

                <div>
                    <span class="text-gray-500 block mb-1">Cavity & Tipe</span>
                    <p class="font-bold text-gray-900 text-sm">
                        {{ $dieset->cavity ? $dieset->cavity . ' Cavity' : '-' }}
                        <span class="text-xs font-medium text-gray-500">({{ $dieset->type_cavity ?: 'Standard' }})</span>
                    </p>
                    <span class="text-gray-400 mt-1 block">Tahun Mold: <strong class="text-gray-700 font-mono">{{ $dieset->production_year ?: '-' }}</strong></span>
                </div>

                <div>
                    <span class="text-gray-500 block mb-1">Total Shoot Mold</span>
                    <p class="font-bold text-slate-900 font-mono text-base">{{ number_format($dieset->total_shoot ?? 0) }}</p>
                    <span class="text-gray-400 mt-1 block">Dimensi: <strong class="text-gray-700 font-mono">{{ $dieset->dimensi_mold ?: '-' }}</strong></span>
                </div>

                <div>
                    <span class="text-gray-500 block mb-1">Sistem Cooling & Naple</span>
                    <p class="font-medium text-gray-800">{{ $dieset->type_cooling ?: '-' }} ({{ $dieset->ukuran_naple ?: '-' }})</p>
                    <span class="text-gray-400 mt-1 block">Eject: <strong class="text-gray-700">{{ $dieset->type_eject ?: '-' }}</strong> • Gate: <strong class="text-gray-700">{{ $dieset->type_gate ?: '-' }}</strong></span>
                </div>
            </div>
        </div>

        {{-- Inspection History Log Table --}}
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden flex flex-col">
            
            <div class="p-5 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                <div>
                    <h4 class="font-bold text-sm text-gray-900">Catatan Kronologis Log Inspeksi</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Daftar inspeksi berkala per komponen, kondisi aktual, tindakan mekanik, dan bukti foto</p>
                </div>
                
                {{-- Condition Tabs for this mold --}}
                <div class="inline-flex p-1 bg-gray-200/70 rounded-lg text-xs font-semibold">
                    <a href="{{ route('monitoring.show', ['id' => $dieset->id, 'start_date' => $startDate, 'end_date' => $endDate, 'condition' => 'all']) }}" 
                       class="px-3 py-1 rounded-md transition-all {{ ($conditionFilter ?? 'all') === 'all' ? 'bg-white text-gray-900 shadow-sm font-bold' : 'text-gray-600 hover:text-gray-900' }}">
                        Semua ({{ $totalMoldInspections ?? 0 }})
                    </a>
                    <a href="{{ route('monitoring.show', ['id' => $dieset->id, 'start_date' => $startDate, 'end_date' => $endDate, 'condition' => 'OK']) }}" 
                       class="px-3 py-1 rounded-md transition-all flex items-center gap-1 {{ ($conditionFilter ?? '') === 'OK' ? 'bg-white text-emerald-700 shadow-sm font-bold' : 'text-gray-600 hover:text-emerald-700' }}">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        OK ({{ $okCount ?? 0 }})
                    </a>
                    <a href="{{ route('monitoring.show', ['id' => $dieset->id, 'start_date' => $startDate, 'end_date' => $endDate, 'condition' => 'Repair']) }}" 
                       class="px-3 py-1 rounded-md transition-all flex items-center gap-1 {{ ($conditionFilter ?? '') === 'Repair' ? 'bg-white text-amber-700 shadow-sm font-bold' : 'text-gray-600 hover:text-amber-700' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Repair ({{ $repairCount ?? 0 }})
                    </a>
                    <a href="{{ route('monitoring.show', ['id' => $dieset->id, 'start_date' => $startDate, 'end_date' => $endDate, 'condition' => 'Replace']) }}" 
                       class="px-3 py-1 rounded-md transition-all flex items-center gap-1 {{ ($conditionFilter ?? '') === 'Replace' ? 'bg-white text-rose-700 shadow-sm font-bold' : 'text-gray-600 hover:text-rose-700' }}">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Replace ({{ $replaceCount ?? 0 }})
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left whitespace-nowrap">
                    <thead class="text-[11px] text-gray-600 uppercase tracking-wider bg-slate-100/75 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-bold text-center border-r border-gray-200 w-12 text-gray-500">No.</th>
                            <th scope="col" class="px-4 py-3 font-bold text-center border-r border-gray-200 min-w-[130px]">Tanggal & Waktu</th>
                            <th scope="col" class="px-4 py-3 font-bold text-left border-r border-gray-200 min-w-[220px]">Komponen Part</th>
                            <th scope="col" class="px-4 py-3 font-bold text-right border-r border-gray-200 w-28">Part Shoot</th>
                            <th scope="col" class="px-4 py-3 font-bold text-right border-r border-gray-200 w-28">Total Shoot</th>
                            <th scope="col" class="px-4 py-3 font-bold text-center border-r border-gray-200 w-24">Img Master</th>
                            <th scope="col" class="px-4 py-3 font-bold text-center border-r border-gray-200 w-28">Kondisi</th>
                            <th scope="col" class="px-4 py-3 font-bold text-left border-r border-gray-200 min-w-[160px]">Tindakan Perbaikan</th>
                            <th scope="col" class="px-4 py-3 font-bold text-left border-r border-gray-200 min-w-[250px]">Detil & Alasan</th>
                            <th scope="col" class="px-4 py-3 font-bold text-center border-r border-gray-200 w-24">Img Aktual</th>
                            <th scope="col" class="px-4 py-3 font-bold text-center min-w-[120px]">Mekanik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $no = 1; @endphp
                        @foreach($dieset->parts as $part)
                            @foreach($part->inspectionHistories as $history)
                                @php
                                    if ($history->condition === 'Replace') {
                                        $rowClass = 'bg-rose-50/20 hover:bg-rose-50/50';
                                        $condBadge = 'bg-rose-100 text-rose-800 border-rose-200';
                                    } elseif ($history->condition === 'Repair') {
                                        $rowClass = 'bg-amber-50/20 hover:bg-amber-50/50';
                                        $condBadge = 'bg-amber-100 text-amber-800 border-amber-200';
                                    } else {
                                        $rowClass = 'bg-white hover:bg-slate-50';
                                        $condBadge = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                    }
                                @endphp

                                <tr class="transition-colors group {{ $rowClass }}">
                                    {{-- No --}}
                                    <td class="px-4 py-3 text-center text-gray-500 font-mono border-r border-gray-100">{{ $no++ }}</td>
                                    
                                    {{-- Tanggal & Waktu --}}
                                    <td class="px-4 py-3 text-center border-r border-gray-100">
                                        <div class="font-mono font-semibold text-gray-800">{{ $history->inspection_date->format('Y-m-d') }}</div>
                                        <div class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $history->inspection_date->format('H:i') }} • {{ $history->inspection_date->diffForHumans() }}</div>
                                    </td>
                                    
                                    {{-- Komponen Part --}}
                                    <td class="px-4 py-3 border-r border-gray-100">
                                        <div class="flex flex-col">
                                            <span class="font-mono font-bold text-xs text-slate-800">{{ $part->part_code }}</span>
                                            <span class="text-gray-900 font-semibold truncate max-w-[200px]" title="{{ $part->name }}">{{ $part->name }}</span>
                                            <div class="flex items-center gap-1 mt-1">
                                                <span class="bg-gray-100 text-gray-600 text-[10px] px-1.5 py-0.2 rounded border border-gray-200 font-medium">{{ $part->category ?? 'General' }}</span>
                                                <span class="text-[10px] text-gray-500 font-mono">Cavity: {{ $part->cavity_number ?? 1 }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    {{-- Parts Shoot --}}
                                    <td class="px-4 py-3 text-right font-mono font-bold text-amber-700 border-r border-gray-100">
                                        {{ number_format($history->parts_shoot) }}
                                    </td>
                                    
                                    {{-- Total Shoot --}}
                                    <td class="px-4 py-3 text-right font-mono font-bold text-slate-800 border-r border-gray-100">
                                        {{ number_format($history->total_shoot) }}
                                    </td>
                                    
                                    {{-- Foto Master Part --}}
                                    <td class="px-4 py-2 text-center border-r border-gray-100">
                                        <div class="flex justify-center">
                                            @if($part->image_path)
                                                <a href="{{ asset('storage/'.$part->image_path) }}" target="_blank" class="relative group/img cursor-pointer">
                                                    <img src="{{ asset('storage/'.$part->image_path) }}" alt="part" class="h-9 w-12 object-cover rounded shadow-sm border border-gray-200 transition-transform transform group-hover/img:scale-110">
                                                </a>
                                            @else
                                                <div class="flex flex-col items-center justify-center h-8 w-11 bg-gray-50 rounded border border-gray-200 border-dashed text-gray-400">
                                                    <span class="text-[9px] italic">No img</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    {{-- Kondisi --}}
                                    <td class="px-4 py-3 text-center border-r border-gray-100">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold border {{ $condBadge }}">
                                            @if($history->condition === 'OK')
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                                OK
                                            @elseif($history->condition === 'Repair')
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                                Repair
                                            @else
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                                Replace
                                            @endif
                                        </span>
                                    </td>
                                    
                                    {{-- Tindakan Perbaikan --}}
                                    <td class="px-4 py-3 border-r border-gray-100 text-gray-800 whitespace-normal font-medium max-w-[180px]">
                                        {{ $history->action_taken ?: '-' }}
                                    </td>
                                    
                                    {{-- Detil & Alasan --}}
                                    <td class="px-4 py-3 border-r border-gray-100 whitespace-normal min-w-[250px]">
                                        <div class="text-gray-900 mb-1">
                                            <span class="font-bold text-gray-500 text-[10px] uppercase tracking-wider block mb-0.5">Alasan:</span>
                                            {{ $history->reason ?: '-' }}
                                        </div>
                                        @if($history->damage_details)
                                            <div class="text-gray-700 bg-gray-100/75 p-2 rounded border border-gray-200 text-[11px] leading-relaxed mt-1">
                                                <span class="font-bold text-gray-500 text-[10px] uppercase tracking-wider block mb-0.5">Detil Pemeriksaan:</span>
                                                {{ $history->damage_details }}
                                            </div>
                                        @endif
                                    </td>
                                    
                                    {{-- Foto Aktual --}}
                                    <td class="px-4 py-2 text-center border-r border-gray-100">
                                        <div class="flex justify-center">
                                            @if($history->evidence_photo_path)
                                                <a href="{{ asset('storage/'.$history->evidence_photo_path) }}" target="_blank" class="relative group/img cursor-pointer">
                                                    <img src="{{ asset('storage/'.$history->evidence_photo_path) }}" alt="inspeksi" class="h-9 w-12 object-cover rounded shadow-sm border border-gray-200 transition-transform transform group-hover/img:scale-110">
                                                </a>
                                            @else
                                                <div class="flex flex-col items-center justify-center h-8 w-11 bg-gray-50 rounded border border-gray-200 border-dashed text-gray-400">
                                                    <span class="text-[9px] italic">No img</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    {{-- Mekanik / Teknisi --}}
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded bg-slate-100 text-slate-800 text-xs font-medium border border-slate-200">
                                            <svg class="w-3 h-3 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $history->operator->name ?? 'Mekanik DAS' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
                
                @if($no == 1)
                    <div class="p-16 text-center bg-white border-b border-gray-100">
                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mb-3 text-slate-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-gray-800">Tidak ada riwayat inspeksi</p>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                Belum ada log pergantian atau perbaikan untuk mold ini pada filter yang dipilih.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="px-6 py-3.5 bg-gray-50 border-t border-gray-200 flex justify-between items-center text-xs">
                <span class="text-gray-500">
                    Sistem Pemantauan Log Audit & Inspeksi DAS PT. Compotec International
                </span>
                <a href="{{ route('monitoring', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-100 transition shadow-sm">
                    Tutup & Kembali
                </a>
            </div>
            
        </div>
    </div>
</x-app-layout>