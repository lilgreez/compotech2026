<x-app-layout>
    {{-- Top Navigation & Header --}}
    <div class="px-6 py-5 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('dieset-status.index') }}" 
                   class="p-2 text-gray-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors border border-gray-200" 
                   title="Kembali ke Daftar Mold Status">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                
                <div class="w-10 h-10 rounded-lg bg-slate-900 flex items-center justify-center text-white shadow-sm ring-1 ring-slate-800">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl font-bold text-gray-900 tracking-tight">{{ $dieset->name }}</h2>
                        <span class="inline-flex items-center font-mono font-bold text-xs text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                            {{ $dieset->kode_mold ?? 'M-'.str_pad($dieset->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">Monitoring rincian status komponen, siklus tembakan (shoot), dan ketersediaan stok</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if(($criticalParts ?? 0) > 0)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                        <span class="w-2 h-2 mr-1.5 rounded-full bg-rose-600 animate-ping"></span>
                        Warning ({{ $criticalParts }} Part Kritis)
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <span class="w-2 h-2 mr-1.5 rounded-full bg-emerald-600"></span>
                        Status Aman (Stok Terpenuhi)
                    </span>
                @endif
                <a href="{{ route('master-diesets.show', $dieset->id) }}" 
                   class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-gray-300 rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Kelola di Master Mold
                </a>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-7xl mx-auto space-y-6">
        
        {{-- Mold Technical Specifications Card --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-slate-50 border-b border-gray-200 px-6 py-3.5 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-900"></span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Spesifikasi Teknis Mold</h3>
                </div>
                <div class="flex items-center space-x-3 text-xs">
                    <span class="text-gray-500">Total Komponen: <strong class="text-gray-900 font-mono">{{ $totalParts ?? 0 }}</strong></span>
                    <span class="text-gray-300">|</span>
                    <span class="text-gray-500">Stok Aman: <strong class="text-emerald-600 font-mono">{{ $safeParts ?? 0 }}</strong></span>
                    <span class="text-gray-300">|</span>
                    <span class="text-gray-500">Stok Kritis: <strong class="text-rose-600 font-mono">{{ $criticalParts ?? 0 }}</strong></span>
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
                    <span class="text-gray-500 block mb-1">Dimensi & Ring</span>
                    <p class="font-semibold text-gray-800 font-mono">{{ $dieset->dimensi_mold ?: '-' }}</p>
                    <span class="text-gray-400 mt-1 block">Lokating Ring: <strong class="text-gray-700 font-mono">{{ $dieset->diameter_lokating_ring ?: '-' }}</strong></span>
                </div>

                <div>
                    <span class="text-gray-500 block mb-1">Cooling & Eject System</span>
                    <p class="font-medium text-gray-800">{{ $dieset->type_cooling ?: '-' }} (Naple: {{ $dieset->ukuran_naple ?: '-' }})</p>
                    <span class="text-gray-400 mt-1 block">Eject: <strong class="text-gray-700">{{ $dieset->type_eject ?: '-' }}</strong> • Gate: <strong class="text-gray-700">{{ $dieset->type_gate ?: '-' }}</strong></span>
                </div>
            </div>
        </div>

        {{-- Parts Lifecycle & Stock Table --}}
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden flex flex-col">
            
            <div class="p-5 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                <div>
                    <h4 class="font-bold text-sm text-gray-900">Daftar Komponen & Lifecycle Parts</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Pantau rasio actual shoot terhadap batas maksimum tembakan dan ketersediaan stok fisik</p>
                </div>
                
                <div class="flex items-center space-x-4 text-xs">
                    <div class="flex items-center">
                        <span class="w-2.5 h-2.5 rounded bg-emerald-500 mr-1.5"></span>
                        <span class="text-gray-600">Stok Aman (> 2)</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-2.5 h-2.5 rounded bg-rose-500 mr-1.5"></span>
                        <span class="text-gray-600">Stok Kritis (≤ 2)</span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left whitespace-nowrap">
                    <thead class="text-[11px] text-gray-600 uppercase tracking-wider bg-slate-100/75 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-4 py-3.5 font-bold border-r border-gray-200">Kategori</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-gray-200 w-20">Cavity</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-left border-r border-gray-200 w-36">Kode Part</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-left border-r border-gray-200 min-w-[200px]">Nama Part</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-left border-r border-gray-200 max-w-[200px]">Deskripsi</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-left border-r border-gray-200 w-64">Siklus Tembakan (Shoot Usage)</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-center border-r border-gray-200 w-32">Stok Gudang</th>
                            <th scope="col" class="px-4 py-3.5 font-bold text-center w-24">Foto Part</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($groupedParts as $category => $parts)
                            @foreach($parts as $index => $part)
                                @php
                                    $maxShoot = $part->max_shoot > 0 ? $part->max_shoot : 3000000;
                                    $actualShoot = $part->actual_shoot ?? 0;
                                    $usagePercent = min(100, round(($actualShoot / $maxShoot) * 100, 1));
                                    $isLow = $part->current_stock <= 2;

                                    // Color tone shoot progress
                                    if ($usagePercent >= 90) {
                                        $barColor = 'bg-rose-500';
                                    } elseif ($usagePercent >= 75) {
                                        $barColor = 'bg-amber-500';
                                    } else {
                                        $barColor = 'bg-blue-600';
                                    }
                                @endphp
                                
                                <tr class="hover:bg-slate-50 transition-colors {{ $isLow ? 'bg-rose-50/20' : 'bg-white' }}">
                                    @if($index === 0)
                                        <td class="px-4 py-3.5 font-bold text-gray-900 border-r border-gray-200 align-top bg-slate-50/50" rowspan="{{ count($parts) }}">
                                            <div class="flex items-center space-x-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700"></span>
                                                <span>{{ $category }}</span>
                                            </div>
                                            <span class="text-[10px] text-gray-400 block mt-0.5">({{ count($parts) }} item)</span>
                                        </td>
                                    @endif
                                    
                                    {{-- Cavity --}}
                                    <td class="px-4 py-3 text-center border-r border-gray-100">
                                        <span class="bg-gray-100 text-gray-700 font-mono text-[11px] font-semibold px-2 py-0.5 rounded border border-gray-200">
                                            {{ $part->cavity_number ?? '-' }}
                                        </span>
                                    </td>
                                    
                                    {{-- Kode Part --}}
                                    <td class="px-4 py-3 border-r border-gray-100">
                                        <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded text-xs border border-slate-200">
                                            {{ $part->part_code ?? '-' }}
                                        </span>
                                    </td>
                                    
                                    {{-- Nama Part --}}
                                    <td class="px-4 py-3 border-r border-gray-100 text-gray-900 font-semibold">
                                        {{ $part->name }}
                                    </td>
                                    
                                    {{-- Deskripsi --}}
                                    <td class="px-4 py-3 border-r border-gray-100 text-gray-600 truncate max-w-[200px]" title="{{ $part->description }}">
                                        {{ $part->description ?: '-' }}
                                    </td>
                                    
                                    {{-- Shoot Usage Progress --}}
                                    <td class="px-4 py-3 border-r border-gray-100">
                                        <div class="w-full">
                                            <div class="flex justify-between items-center text-[11px] mb-1">
                                                <span class="font-mono font-bold text-gray-900">{{ number_format($actualShoot) }}</span>
                                                <span class="text-gray-400 font-mono">/ {{ number_format($maxShoot) }} ({{ $usagePercent }}%)</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                                <div class="{{ $barColor }} h-1.5 rounded-full transition-all duration-300" style="width: {{ $usagePercent }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    {{-- Stok Fisik --}}
                                    <td class="px-4 py-3 text-center border-r border-gray-100">
                                        @if($isLow)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded font-mono font-bold text-xs bg-rose-100 text-rose-800 border border-rose-200">
                                                {{ $part->current_stock }} unit
                                                <span class="ml-1 text-[10px] uppercase font-bold text-rose-600">(Kritis)</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded font-mono font-bold text-xs bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                {{ $part->current_stock }} unit
                                            </span>
                                        @endif
                                    </td>
                                    
                                    {{-- Image --}}
                                    <td class="px-4 py-2 text-center">
                                        @if($part->image_path)
                                            <a href="{{ asset('storage/'.$part->image_path) }}" target="_blank" class="inline-block relative group">
                                                <img src="{{ asset('storage/'.$part->image_path) }}" alt="part" class="h-9 w-12 object-cover rounded border border-gray-200 shadow-sm transition-transform transform group-hover:scale-110">
                                            </a>
                                        @else
                                            <div class="inline-flex items-center justify-center h-8 w-10 bg-slate-50 rounded border border-gray-200 text-gray-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center bg-white">
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mb-3 text-slate-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">Belum ada item parts terhubung</p>
                                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                            Mold ini belum memiliki komponen spare part yang didaftarkan. Anda dapat menambahkan item parts melalui menu Master Mold.
                                        </p>
                                        <a href="{{ route('master-diesets.show', $dieset->id) }}" class="mt-4 inline-flex items-center px-3.5 py-2 text-xs font-semibold text-white bg-slate-900 rounded-lg hover:bg-slate-800 transition-colors">
                                            Kelola Komponen Mold
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-3.5 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <span class="text-xs text-gray-500">
                    Sistem Inventaris Part Terintegrasi DAS PT. Compotec International
                </span>
                <a href="{{ route('dieset-status.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-100 transition shadow-sm">
                    Tutup & Kembali
                </a>
            </div>
            
        </div>
    </div>
</x-app-layout>