<x-app-layout>
    <div class="p-6">
        <div class="bg-white shadow-lg rounded-sm border border-gray-200 flex flex-col" style="min-height: 80vh;">
            
            <!-- Header Modal-Like (Sesuai PDF Halaman 13) -->
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h3 class="text-gray-600 font-semibold text-sm uppercase">DETAIL INSPECTION</h3>
                <a href="{{ route('monitoring') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            </div>

            <div class="p-6 border-b border-gray-100 bg-white">
                <h2 class="text-xl font-medium text-gray-800">{{ $dieset->name }}</h2>
                <p class="text-xs font-bold text-gray-600 mt-2">Produk : <span class="font-normal">{{ $dieset->product_code }} ({{ $dieset->description }})</span></p>
                <p class="text-xs font-bold text-gray-600 mt-1">Shoot : <span class="font-normal">{{ number_format($dieset->total_shoot) }}</span></p>
            </div>

            <!-- Table Data Inspeksi Sesuai PDF Halaman 13 -->
            <div class="flex-1 p-6 overflow-x-auto bg-white">
                <div class="flex justify-between items-center mb-4 text-xs text-gray-600">
                    <div>
                        <span>Show</span>
                        <select class="mx-1 border-gray-300 rounded py-1 px-2"><option>10</option></select>
                        <span>entries</span>
                    </div>
                    <div>
                        <label class="mr-2">Search:</label>
                        <input type="text" class="border-gray-300 rounded py-1 px-2 bg-gray-100">
                    </div>
                </div>

                <table class="w-full border border-gray-200 text-[11px]">
                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="p-2 font-semibold text-center border-r">No.</th>
                            <th class="p-2 font-semibold text-center border-r min-w-[120px]">Tanggal</th>
                            <th class="p-2 font-semibold text-center border-r min-w-[200px]">Parts</th>
                            <th class="p-2 font-semibold text-center border-r">Parts Shoot</th>
                            <th class="p-2 font-semibold text-center border-r">Total Shoot</th>
                            <th class="p-2 font-semibold text-center border-r">Gambar Parts</th>
                            <th class="p-2 font-semibold text-center border-r">Jenis Kerusakan</th>
                            <th class="p-2 font-semibold text-center border-r">Tindakan Perbaikan</th>
                            <th class="p-2 font-semibold text-center border-r min-w-[200px]">Detil Inspeksi</th>
                            <th class="p-2 font-semibold text-center border-r">Gambar Inspeksi</th>
                            <th class="p-2 font-semibold text-center">Mekanik Inspeksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($dieset->parts as $part)
                            @foreach($part->inspectionHistories as $history)
                                <tr class="bg-white border-b border-gray-100 hover:bg-gray-50">
                                    <td class="p-2 text-center border-r">{{ $no++ }}</td>
                                    <td class="p-2 text-center border-r text-gray-700">{{ $history->inspection_date->format('Y-m-d H:i:s') }}</td>
                                    <td class="p-2 border-r text-gray-700 font-medium">
                                        {{ $part->category ?? '' }} - ({{ $part->part_code }}) {{ $part->name }}; CAVITY: {{ $part->cavity_number }}
                                    </td>
                                    <td class="p-2 text-center border-r">{{ number_format($history->parts_shoot) }}</td>
                                    <td class="p-2 text-center border-r">{{ number_format($history->total_shoot) }}</td>
                                    
                                    <!-- Gambar Part Master -->
                                    <td class="p-2 text-center border-r flex justify-center">
                                        @if($part->image_path)
                                            <img src="{{ asset('storage/'.$part->image_path) }}" alt="part" class="h-12 w-auto rounded border">
                                        @else
                                            <span class="text-gray-400 italic">No img</span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-2 text-center border-r">{{ $history->condition }}</td>
                                    <td class="p-2 text-center border-r">{{ $history->action_taken ?? '-' }}</td>
                                    
                                    <!-- Detil Inspeksi / Alasan -->
                                    <td class="p-2 border-r text-gray-600">
                                        <b>Alasan Pergantian:</b> {{ $history->reason ?? '-' }} <br>
                                        <b>Detil:</b> {{ $history->damage_details ?? '-' }}
                                    </td>
                                    
                                    <!-- Gambar Hasil Inspeksi dari HP -->
                                    <td class="p-2 text-center border-r flex justify-center">
                                        @if($history->evidence_photo_path)
                                            <img src="{{ asset('storage/'.$history->evidence_photo_path) }}" alt="inspeksi" class="h-12 w-auto rounded border">
                                        @else
                                            <span class="text-gray-400 italic">No img</span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-2 text-center text-gray-700">{{ $history->operator->name ?? 'Unknown' }}</td>
                                </tr>
                            @endforeach
                        @endforeach

                        @if($no == 1)
                            <tr><td colspan="11" class="p-6 text-center text-gray-500">Tidak ada detail inspeksi.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Footer Close Button (Halaman 13 PDF Bawah) -->
            <div class="p-3 bg-[#424242] flex justify-end">
                <a href="{{ route('monitoring') }}" class="px-5 py-1.5 bg-[#5e5e5e] text-white rounded text-xs hover:bg-gray-500 transition border border-gray-600">Close</a>
            </div>
        </div>
    </div>
</x-app-layout>