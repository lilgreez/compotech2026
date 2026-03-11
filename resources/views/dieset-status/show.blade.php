<x-app-layout>
    <div class="px-6 py-5 bg-white border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('dieset-status.index') }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors border border-transparent hover:border-indigo-200 tooltip" title="Kembali ke list">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            
            <div class="p-2.5 bg-indigo-600 rounded-lg text-white shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Detail Dieset Status</h2>
                <p class="text-sm text-gray-500 mt-0.5">Rincian status dan stok part untuk dieset ini</p>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-7xl mx-auto space-y-6">
        
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50/50 border-b border-gray-200 px-6 py-5 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $dieset->name }}</h3>
                    <div class="mt-2 flex flex-wrap items-center text-sm text-gray-600 gap-y-2">
                        <span class="flex items-center font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 mr-3">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> 
                            {{ $dieset->product_code ?? '-' }}
                        </span>
                        <span class="mr-3">{{ $dieset->description ?? '-' }}</span>
                        <span class="text-gray-300 mr-3">|</span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Tahun: <strong class="ml-1 text-gray-800">{{ $dieset->production_year ?? '-' }}</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden flex flex-col">
            
            <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white">
                <h4 class="font-semibold text-gray-800">Daftar Komponen (Parts)</h4>
                
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 py-2 pr-3 transition-shadow" placeholder="Cari Part di dieset ini...">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left whitespace-nowrap">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-200">Kategori</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200 w-20">Cavity</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200">Kode Parts</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left border-r border-gray-200">Nama Parts</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left border-r border-gray-200">Desc Parts</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-right border-r border-gray-200 w-28">Actual Shoot</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-right border-r border-gray-200 w-28">Max Shoot</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center border-r border-gray-200 w-24">Stock</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-28">Parts Image</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($groupedParts as $category => $parts)
                            @php
                                // Mengganti hardcode hex dengan standard Tailwind (Zebra stripe per grup Kategori)
                                $bgRow = $loop->iteration % 2 == 0 ? 'bg-emerald-50/30' : 'bg-white';
                            @endphp
                            
                            @foreach($parts as $index => $part)
                                <tr class="{{ $bgRow }} hover:bg-blue-50/50 transition-colors border-b border-gray-100">
                                    
                                    @if($index === 0)
                                        <td class="px-6 py-4 font-bold text-gray-900 border-r border-gray-200 align-top bg-white" rowspan="{{ count($parts) }}">
                                            {{ $category ?? '-' }}
                                        </td>
                                    @endif
                                    
                                    <td class="px-6 py-4 text-center border-r border-gray-100">
                                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-gray-200">{{ $part->cavity_number ?? '-' }}</span>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center border-r border-gray-100">
                                        <span class="font-semibold text-indigo-600 cursor-pointer hover:underline">{{ $part->part_code ?? '-' }}</span>
                                    </td>
                                    
                                    <td class="px-6 py-4 border-r border-gray-100 text-gray-800 font-medium">
                                        {{ $part->name }}
                                    </td>
                                    
                                    <td class="px-6 py-4 border-r border-gray-100 text-gray-600 truncate max-w-[200px]" title="{{ $part->description }}">
                                        {{ $part->description ?? '-' }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-right border-r border-gray-100 font-bold text-gray-800">
                                        {{ number_format($part->actual_shoot) }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-right border-r border-gray-100 text-gray-600">
                                        {{ number_format($part->max_shoot) }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center border-r border-gray-100 font-bold {{ $part->current_stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $part->current_stock }}
                                    </td>
                                    
                                    <td class="px-6 py-3 text-center flex justify-center items-center">
                                        @if($part->image_path)
                                            <div class="relative group cursor-pointer">
                                                <img src="{{ asset('storage/'.$part->image_path) }}" alt="part" class="h-12 w-16 object-cover rounded-md border border-gray-200 shadow-sm transition-transform transform group-hover:scale-110">
                                            </div>
                                        @else
                                            <div class="flex flex-col items-center justify-center h-12 w-16 bg-gray-50 rounded-md border border-gray-200 border-dashed">
                                                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4 shadow-sm">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium text-base">Belum ada Parts di Dieset ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                <a href="{{ route('dieset-status.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 transition shadow-sm flex items-center">
                    Tutup & Kembali
                </a>
            </div>
            
        </div>
    </div>
</x-app-layout>