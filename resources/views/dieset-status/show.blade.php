<x-app-layout>
    <div class="p-6">
        <div class="bg-white shadow-lg rounded-sm border border-gray-200 flex flex-col" style="min-height: 80vh;">
            
            <!-- Header Modal-Like -->
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h3 class="text-gray-600 font-semibold text-sm uppercase">Detail Dieset</h3>
                <a href="{{ route('dieset-status.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            </div>

            <!-- Dieset Info Sesuai PDF Halaman 10 -->
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-medium text-gray-800">Dieset Name : {{ $dieset->name }}</h2>
                <p class="text-sm text-gray-600 mt-1">Product : {{ $dieset->product_code }} ({{ $dieset->description }})</p>
                <p class="text-sm text-gray-600">Tahun : {{ $dieset->production_year }}</p>
            </div>

            <!-- Table Data Parts -->
            <div class="flex-1 p-6 overflow-x-auto">
                <div class="flex justify-end mb-4">
                    <div class="flex items-center text-sm">
                        <label class="mr-2 text-gray-600">Search:</label>
                        <input type="text" class="border-gray-300 rounded text-sm py-1 px-3 w-48 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <table class="w-full border border-gray-200 text-xs">
                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="p-3 font-semibold text-center border-r">Kategori</th>
                            <th class="p-3 font-semibold text-center border-r">Cavity</th>
                            <th class="p-3 font-semibold text-center border-r">Kode Parts</th>
                            <th class="p-3 font-semibold text-center border-r">Nama Parts</th>
                            <th class="p-3 font-semibold text-center border-r">Desc Parts</th>
                            <th class="p-3 font-semibold text-center border-r">Actual Shoot</th>
                            <th class="p-3 font-semibold text-center border-r">Max Shoot</th>
                            <th class="p-3 font-semibold text-center border-r">Stock</th>
                            <th class="p-3 font-semibold text-center">Parts Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedParts as $category => $parts)
                            @foreach($parts as $index => $part)
                                @php
                                    // Bikin zebra stripe hijau muda selang seling sesuai PDF
                                    $bgRow = $loop->parent->iteration % 2 == 0 ? 'bg-[#e8f5e9]' : 'bg-white';
                                @endphp
                                <tr class="{{ $bgRow }} border-b border-gray-200">
                                    <!-- Kategori hanya tampil di baris pertama tiap grup -->
                                    @if($index === 0)
                                        <td class="p-3 font-bold text-gray-800 border-r align-top" rowspan="{{ count($parts) }}">{{ $category ?? '-' }}</td>
                                    @endif
                                    
                                    <td class="p-3 text-center border-r">{{ $part->cavity_number ?? '-' }}</td>
                                    <td class="p-3 text-center border-r text-blue-500 hover:underline cursor-pointer">{{ $part->part_code ?? '-' }}</td>
                                    <td class="p-3 border-r text-gray-700">{{ $part->name }}</td>
                                    <td class="p-3 border-r text-gray-700">{{ $part->description ?? '-' }}</td>
                                    <td class="p-3 text-center border-r font-bold text-gray-800">{{ number_format($part->actual_shoot) }}</td>
                                    <td class="p-3 text-center border-r font-bold text-gray-800">{{ number_format($part->max_shoot) }}</td>
                                    <td class="p-3 text-center border-r text-gray-800">{{ $part->current_stock }}</td>
                                    <td class="p-3 text-center flex justify-center items-center">
                                        @if($part->image_path)
                                            <img src="{{ asset('storage/'.$part->image_path) }}" alt="part" class="h-10 w-auto object-cover rounded shadow-sm">
                                        @else
                                            <span class="text-gray-400 italic">No image</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer Close Button Sesuai PDF -->
            <div class="p-4 bg-[#3a3f51] flex justify-end">
                <a href="{{ route('dieset-status.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded text-sm hover:bg-gray-600 transition">Close</a>
            </div>
        </div>
    </div>
</x-app-layout>