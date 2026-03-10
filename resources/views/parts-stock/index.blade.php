<x-app-layout>
    <div class="px-6 py-4 bg-white border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-500 rounded text-white shadow">
                <!-- Icon Parts Stock -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight uppercase">STOCK PARTS</h2>
                <p class="text-xs text-gray-500">Wings Stock Parts</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if (session('success'))
            <div class="mb-4 bg-green-50 text-green-700 p-4 rounded text-sm font-semibold border border-green-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm rounded border border-gray-200">
            
            <!-- TABS NAVIGATION (Sesuai PDF Hal 15) -->
            <div class="border-b border-gray-200 flex px-4 pt-2 space-x-6">
                <a href="{{ route('parts-stock.index', ['tab' => 'all']) }}" 
                   class="pb-3 text-sm font-semibold border-b-2 {{ $tab === 'all' ? 'border-yellow-400 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    All Stock
                </a>
                <a href="{{ route('parts-stock.index', ['tab' => 'low']) }}" 
                   class="pb-3 text-sm font-semibold border-b-2 {{ $tab === 'low' ? 'border-yellow-400 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Low Stock
                </a>
                <a href="{{ route('parts-stock.index', ['tab' => 'safe']) }}" 
                   class="pb-3 text-sm font-semibold border-b-2 {{ $tab === 'safe' ? 'border-yellow-400 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Safe Stock
                </a>
            </div>

            <div class="p-6">
                <!-- Toolbar Sesuai PDF -->
                <form method="GET" action="{{ route('parts-stock.index') }}" class="flex justify-between items-center mb-4 text-sm text-gray-600">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <div class="flex items-center">
                        <span>Show</span>
                        <select name="per_page" class="mx-2 border-gray-300 rounded py-1 px-2 focus:ring-blue-500" onchange="this.form.submit()">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div class="flex items-center">
                        <label class="mr-2">Search:</label>
                        <input type="text" name="search" value="{{ $search }}" class="border-gray-300 rounded py-1 px-3 w-48 focus:ring-blue-500" placeholder="Cari parts..." onchange="this.form.submit()">
                    </div>
                </form>

                <!-- TABLE DATA -->
                <div class="overflow-x-auto border border-gray-200 rounded">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 font-semibold text-center border-r w-12">No.</th>
                                <th class="px-4 py-3 font-semibold border-r">Parts Code</th>
                                <th class="px-4 py-3 font-semibold border-r">Parts Name</th>
                                <th class="px-4 py-3 font-semibold border-r">Parts Desc</th>
                                <th class="px-4 py-3 font-semibold text-center w-24">Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($parts as $index => $part)
                                <tr class="bg-white hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-center text-gray-700 border-r">{{ $parts->firstItem() + $index }}</td>
                                    <td class="px-4 py-3 text-gray-800 border-r font-medium">{{ $part->part_code ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-800 border-r">{{ $part->name }}</td>
                                    <td class="px-4 py-3 text-gray-600 border-r">{{ $part->description ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center font-bold {{ $part->current_stock <= 2 ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ $part->current_stock }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada data parts di kategori ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer (Pagination & Button Mail SPV) -->
                <div class="mt-4 flex items-center justify-between text-sm">
                    <!-- Sesuai PDF Halaman 16: Tombol Mail To SPV muncul saat Tab Low Stock -->
                    <div class="flex items-center space-x-4 w-1/3">
                        @if($tab === 'low' && $parts->count() > 0)
                            <form method="POST" action="{{ route('parts-stock.mail') }}">
                                @csrf
                                <button type="submit" class="flex items-center px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 shadow-sm border border-dashed border-yellow-400 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    Mail To SPV
                                </button>
                            </form>
                        @else
                            <div class="text-gray-500">Showing {{ $parts->firstItem() ?? 0 }} to {{ $parts->lastItem() ?? 0 }} of {{ $parts->total() }} entries</div>
                        @endif
                    </div>

                    <div class="w-1/3 flex justify-center text-gray-500">
                        @if($tab === 'low' && $parts->count() > 0)
                            Showing {{ $parts->firstItem() ?? 0 }} to {{ $parts->lastItem() ?? 0 }} of {{ $parts->total() }} entries
                        @endif
                    </div>

                    <div class="w-1/3 flex justify-end">
                        {{ $parts->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>