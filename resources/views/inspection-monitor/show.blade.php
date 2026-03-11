<x-app-layout>
    <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('monitoring') }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors border border-transparent hover:border-indigo-200 tooltip" title="Kembali ke list">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            
            <div class="p-2.5 bg-indigo-600 rounded-lg text-white shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Detail Riwayat Inspeksi</h2>
                <p class="text-sm text-gray-500 mt-0.5">Catatan inspeksi dan pergantian part pada dieset</p>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-[95rem] mx-auto space-y-6">
        
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50/50 border-b border-gray-200 px-6 py-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $dieset->name }}</h3>
                    <div class="mt-2 flex flex-wrap items-center text-sm text-gray-600 gap-y-2">
                        <span class="flex items-center font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 mr-3">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> 
                            {{ $dieset->product_code ?? '-' }}
                        </span>
                        <span>{{ $dieset->description ?? '-' }}</span>
                    </div>
                </div>
                <div class="bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm text-right flex flex-col justify-center min-w-[140px]">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-0.5">Total Shoot</span>
                    <span class="text-lg font-bold text-indigo-600">{{ number_format($dieset->total_shoot) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden flex flex-col">
            
            <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600 whitespace-nowrap">Show</span>
                    <select class="w-20 border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 bg-white shadow-sm">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <span class="text-sm text-gray-600 whitespace-nowrap">entries</span>
                </div>
                
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 py-2 pr-3 transition-shadow" placeholder="Cari inspeksi...">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left whitespace-nowrap">
                    <thead class="text-[11px] text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-semibold text-center border-r border-gray-200 w-12">No.</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-center border-r border-gray-200 min-w-[130px]">Tanggal</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-left border-r border-gray-200 min-w-[220px]">Parts</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-right border-r border-gray-200">Parts Shoot</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-right border-r border-gray-200">Total Shoot</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-center border-r border-gray-200 w-24">Img Master</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-left border-r border-gray-200 min-w-[120px]">Kondisi</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-left border-r border-gray-200 min-w-[150px]">Tindakan</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-left border-r border-gray-200 min-w-[250px]">Detil Inspeksi</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-center border-r border-gray-200 w-24">Img Aktual</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-center min-w-[120px]">Mekanik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $no = 1; @endphp
                        @foreach($dieset->parts as $part)
                            @foreach($part->inspectionHistories as $history)
                                <tr class="bg-white hover:bg-blue-50/50 transition-colors group">
                                    <td class="px-4 py-3 text-center text-gray-500 border-r border-gray-100">{{ $no++ }}</td>
                                    
                                    <td class="px-4 py-3 text-center font-medium text-gray-700 border-r border-gray-100">
                                        {{ $history->inspection_date->format('Y-m-d') }}
                                        <div class="text-[10px] text-gray-400 mt-0.5">{{ $history->inspection_date->format('H:i:s') }}</div>
                                    </td>
                                    
                                    <td class="px-4 py-3 border-r border-gray-100">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900">{{ $part->part_code }}</span>
                                            <span class="text-gray-600 truncate max-w-[200px]" title="{{ $part->name }}">{{ $part->name }}</span>
                                            <div class="flex items-center mt-1">
                                                <span class="bg-gray-100 text-gray-600 text-[10px] px-1.5 py-0.5 rounded border border-gray-200 mr-1">{{ $part->category ?? 'N/A' }}</span>
                                                <span class="text-[10px] text-gray-400">CAVITY: {{ $part->cavity_number }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-3 text-right font-semibold text-orange-600 border-r border-gray-100">
                                        {{ number_format($history->parts_shoot) }}
                                    </td>
                                    
                                    <td class="px-4 py-3 text-right font-semibold text-indigo-600 border-r border-gray-100">
                                        {{ number_format($history->total_shoot) }}
                                    </td>
                                    
                                    <td class="px-4 py-2 text-center border-r border-gray-100">
                                        <div class="flex justify-center">
                                            @if($part->image_path)
                                                <div class="relative group/img cursor-pointer">
                                                    <img src="{{ asset('storage/'.$part->image_path) }}" alt="part" class="h-10 w-14 object-cover rounded shadow-sm border border-gray-200 transition-transform transform group-hover/img:scale-110">
                                                </div>
                                            @else
                                                <div class="flex flex-col items-center justify-center h-10 w-14 bg-gray-50 rounded border border-gray-200 border-dashed">
                                                    <span class="text-[9px] text-gray-400 italic">No img</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-3 border-r border-gray-100 text-gray-800 whitespace-normal">
                                        {{ $history->condition }}
                                    </td>
                                    
                                    <td class="px-4 py-3 border-r border-gray-100 text-gray-800 whitespace-normal">
                                        {{ $history->action_taken ?? '-' }}
                                    </td>
                                    
                                    <td class="px-4 py-3 border-r border-gray-100 whitespace-normal min-w-[250px]">
                                        <div class="text-gray-800 mb-1">
                                            <span class="font-semibold text-gray-600 text-[10px] uppercase tracking-wider block mb-0.5">Alasan:</span>
                                            {{ $history->reason ?? '-' }}
                                        </div>
                                        <div class="text-gray-600 bg-gray-50 p-1.5 rounded border border-gray-100 text-[11px] leading-relaxed mt-1.5">
                                            <span class="font-semibold text-gray-500 text-[10px] uppercase tracking-wider block mb-0.5">Detil:</span>
                                            {{ $history->damage_details ?? '-' }}
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-2 text-center border-r border-gray-100">
                                        <div class="flex justify-center">
                                            @if($history->evidence_photo_path)
                                                <div class="relative group/img cursor-pointer">
                                                    <img src="{{ asset('storage/'.$history->evidence_photo_path) }}" alt="inspeksi" class="h-10 w-14 object-cover rounded shadow-sm border border-gray-200 transition-transform transform group-hover/img:scale-110">
                                                </div>
                                            @else
                                                <div class="flex flex-col items-center justify-center h-10 w-14 bg-gray-50 rounded border border-gray-200 border-dashed">
                                                    <span class="text-[9px] text-gray-400 italic">No img</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-gray-700 font-medium border border-gray-200">
                                            <svg class="w-3 h-3 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $history->operator->name ?? 'Unknown' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
                
                @if($no == 1)
                    <div class="p-16 text-center bg-white border-b border-gray-100">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            </div>
                            <p class="text-gray-500 font-medium text-base">Tidak ada detail riwayat inspeksi.</p>
                            <p class="text-gray-400 text-xs mt-1">Belum ada catatan pergantian atau perbaikan untuk dieset ini.</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                <a href="{{ route('monitoring') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 transition shadow-sm flex items-center">
                    Tutup & Kembali
                </a>
            </div>
            
        </div>
    </div>
</x-app-layout>