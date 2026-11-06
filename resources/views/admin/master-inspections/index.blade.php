<x-app-layout>
    @php
        // Translasi nama tab untuk UI
        $tabNames = [
            'kerusakan' => 'Jenis Kerusakan',
            'tindakan' => 'Tindakan Perbaikan',
            'alasan' => 'Alasan / Reasons'
        ];
        $currentTabName = $tabNames[$tab] ?? 'Jenis Kerusakan';
    @endphp

    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        formData: { id: '', name: '' }
    }">

        <!-- Mission Control Header -->
        <div class="px-6 py-5 bg-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center space-x-3.5">
                    <div class="p-2.5 bg-slate-900 rounded-xl text-white shadow-sm ring-1 ring-slate-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h2 class="font-extrabold text-xl text-slate-900 tracking-tight leading-tight">Master Inspection</h2>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                                </span>
                                Kamus Standar : {{ number_format($stats['total']) }} Definisi
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Database kamus standarisasi inspeksi mold, jenis kerusakan cacat, tindakan perbaikan, dan identifikasi akar masalah</p>
                    </div>
                </div>
                
                <button @click="showAddModal = true" class="w-full md:w-auto px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold inline-flex items-center justify-center gap-2 shadow-xs transition-colors focus:ring-4 focus:ring-indigo-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
                    Tambah {{ $currentTabName }}
                </button>
            </div>
        </div>

        <div class="p-6 max-w-7xl mx-auto space-y-6">
            
            <!-- Quick Summary KPI Cards (Slate & Steel) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Total Dictionary Items -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Item Kamus</p>
                        <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 font-mono tabular-nums tracking-tight">{{ number_format($stats['total']) }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Akumulasi kosakata inspeksi</p>
                    </div>
                </div>

                <!-- Card 2: Jenis Kerusakan -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs hover:border-rose-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Jenis Kerusakan</p>
                        <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-2xl sm:text-3xl font-black text-rose-600 font-mono tabular-nums tracking-tight">{{ number_format($stats['kerusakan']) }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Kamus cacat & deviasi mold</p>
                    </div>
                </div>

                <!-- Card 3: Tindakan Perbaikan -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs hover:border-blue-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Tindakan Perbaikan</p>
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-2xl sm:text-3xl font-black text-blue-600 font-mono tabular-nums tracking-tight">{{ number_format($stats['tindakan']) }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Standar corrective action</p>
                    </div>
                </div>

                <!-- Card 4: Alasan / Root Causes -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-2xs hover:border-indigo-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Alasan / Reasons</p>
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-2xl sm:text-3xl font-black text-indigo-600 font-mono tabular-nums tracking-tight">{{ number_format($stats['alasan']) }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Kamus akar masalah (5 Why)</p>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            @if (session('success'))
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl text-xs font-semibold border border-emerald-200 flex items-center shadow-2xs">
                    <svg class="w-5 h-5 mr-3 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Main Table Card (Slate & Steel) -->
            <div class="bg-white shadow-2xs rounded-xl border border-slate-200 overflow-hidden">
                
                <!-- Category Tab Strip with Counters -->
                <div class="bg-slate-50/80 border-b border-slate-200 px-6 pt-3">
                    <nav class="flex space-x-6 overflow-x-auto" aria-label="Tabs">
                        <a href="{{ route('master-inspections.index', ['tab' => 'kerusakan']) }}" 
                           class="whitespace-nowrap pb-3.5 px-1 border-b-2 font-bold text-xs transition-colors inline-flex items-center gap-2 {{ $tab === 'kerusakan' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                            Jenis Kerusakan
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono {{ $tab === 'kerusakan' ? 'bg-indigo-100 text-indigo-700 font-bold' : 'bg-slate-200 text-slate-600' }}">
                                {{ number_format($stats['kerusakan']) }}
                            </span>
                        </a>
                        <a href="{{ route('master-inspections.index', ['tab' => 'tindakan']) }}" 
                           class="whitespace-nowrap pb-3.5 px-1 border-b-2 font-bold text-xs transition-colors inline-flex items-center gap-2 {{ $tab === 'tindakan' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                            Tindakan Perbaikan
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono {{ $tab === 'tindakan' ? 'bg-indigo-100 text-indigo-700 font-bold' : 'bg-slate-200 text-slate-600' }}">
                                {{ number_format($stats['tindakan']) }}
                            </span>
                        </a>
                        <a href="{{ route('master-inspections.index', ['tab' => 'alasan']) }}" 
                           class="whitespace-nowrap pb-3.5 px-1 border-b-2 font-bold text-xs transition-colors inline-flex items-center gap-2 {{ $tab === 'alasan' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                            Alasan / Reasons
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono {{ $tab === 'alasan' ? 'bg-indigo-100 text-indigo-700 font-bold' : 'bg-slate-200 text-slate-600' }}">
                                {{ number_format($stats['alasan']) }}
                            </span>
                        </a>
                    </nav>
                </div>

                <div>
                    <!-- Controls Bar -->
                    <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-3 bg-white">
                        <form method="GET" action="{{ route('master-inspections.index') }}" class="w-full flex flex-col sm:flex-row justify-between items-center gap-3">
                            <input type="hidden" name="tab" value="{{ $tab }}">
                            
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-bold uppercase text-slate-500 whitespace-nowrap">Show:</span>
                                <select name="per_page" class="w-20 border-slate-300 rounded-lg py-1.5 px-3 text-xs font-semibold focus:ring-indigo-500 focus:border-indigo-500 text-slate-700 bg-white shadow-2xs" onchange="this.form.submit()">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                </select>
                            </div>

                            <div class="flex items-center gap-2 w-full sm:w-auto">
                                <div class="relative w-full sm:w-72">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" name="search" value="{{ $search }}" class="bg-white border border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-9 py-2 pr-8 shadow-2xs transition" placeholder="Cari {{ strtolower($currentTabName) }}..." onkeydown="if(event.key === 'Enter') this.form.submit()">
                                    @if($search)
                                        <a href="{{ route('master-inspections.index', ['tab' => $tab, 'per_page' => $perPage]) }}" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 hover:text-slate-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </a>
                                    @endif
                                </div>
                                @if($search)
                                    <a href="{{ route('master-inspections.index', ['tab' => $tab]) }}" class="px-2.5 py-2 text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition border border-rose-200 whitespace-nowrap">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Table Content (Slate & Steel) -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left whitespace-nowrap">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th scope="col" class="px-5 py-3.5 font-bold text-center border-r border-slate-200 w-16">No.</th>
                                    <th scope="col" class="px-5 py-3.5 font-bold border-r border-slate-200">Nama {{ $currentTabName }}</th>
                                    <th scope="col" class="px-5 py-3.5 font-bold text-center border-r border-slate-200 w-36">Kategori</th>
                                    <th scope="col" class="px-5 py-3.5 font-bold text-center border-r border-slate-200 w-28">Status</th>
                                    <th scope="col" class="px-5 py-3.5 font-bold text-center min-w-[120px] w-32">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($inspections as $index => $item)
                                    <tr class="bg-white hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-5 py-3.5 text-center text-slate-400 border-r border-slate-100 font-mono tabular-nums">
                                            {{ $inspections->firstItem() + $index }}
                                        </td>
                                        
                                        <td class="px-5 py-3.5 text-slate-900 border-r border-slate-100 font-bold text-xs">
                                            {{ $item->name }}
                                        </td>

                                        <td class="px-5 py-3.5 text-center border-r border-slate-100">
                                            @if($item->type === 'kerusakan')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                    Kerusakan
                                                </span>
                                            @elseif($item->type === 'tindakan')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                    Tindakan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                    Alasan
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-3.5 text-center border-r border-slate-100">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                                Aktif
                                            </span>
                                        </td>
                                        
                                        <td class="px-5 py-3.5 text-center">
                                            <div class="flex justify-center items-center space-x-1.5">
                                                <button @click="formData = { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}' }; showEditModal = true" 
                                                        class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors border border-transparent hover:border-amber-200" title="Edit Item">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </button>
                                                <button @click="formData = { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}' }; showDeleteModal = true" 
                                                        class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors border border-transparent hover:border-rose-200" title="Hapus Item">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-14 text-center">
                                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-3 border border-slate-200">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                                </div>
                                                <h4 class="text-sm font-bold text-slate-900">Belum ada data {{ $currentTabName }}</h4>
                                                <p class="text-xs text-slate-500 mt-1 mb-3">
                                                    @if($search)
                                                        Tidak ditemukan hasil dengan kata kunci "{{ $search }}".
                                                    @else
                                                        Kamus {{ strtolower($currentTabName) }} belum diisi. Silakan tambahkan entri pertama.
                                                    @endif
                                                </p>
                                                @if($search)
                                                    <a href="{{ route('master-inspections.index', ['tab' => $tab]) }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                                                        Reset Pencarian
                                                    </a>
                                                @else
                                                    <button type="button" @click="showAddModal = true" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                                                        + Tambah {{ $currentTabName }}
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($inspections->hasPages() || $inspections->total() > 0)
                        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/70 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                            <div>
                                Menampilkan <span class="font-bold text-slate-900 font-mono tabular-nums">{{ $inspections->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-900 font-mono tabular-nums">{{ $inspections->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-slate-900 font-mono tabular-nums">{{ number_format($inspections->total()) }}</span> entri
                            </div>
                            <div>
                                {{ $inspections->appends(request()->query())->links('pagination::tailwind') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 1: TAMBAH DATA KAMUS                                -->
        <!-- ======================================================== -->
        <div x-show="showAddModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all flex flex-col border border-slate-200" 
                 @click.away="showAddModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-slate-900 font-bold text-base leading-tight">Tambah {{ $currentTabName }}</h3>
                            <p class="text-xs text-slate-500">Kamus standarisasi formulir inspeksi mold</p>
                        </div>
                    </div>
                    <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('master-inspections.store') }}" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="type" value="{{ $tab }}">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama {{ $currentTabName }} <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required placeholder="Contoh nama {{ strtolower($currentTabName) }}..." class="w-full border-slate-300 rounded-lg text-xs py-2.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-2xs transition">
                            <p class="text-[10px] text-slate-400 mt-1">Item ini akan langsung muncul pada pilihan dropdown formulir inspeksi harian.</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-slate-200 flex justify-end space-x-2.5">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-50 transition shadow-2xs">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold flex items-center hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition shadow-2xs">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 2: EDIT DATA KAMUS                                  -->
        <!-- ======================================================== -->
        <div x-show="showEditModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all flex flex-col border border-slate-200" 
                 @click.away="showEditModal = false">
                
                <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-slate-900 font-bold text-base leading-tight">Edit {{ $currentTabName }}</h3>
                            <p class="text-xs text-slate-500">Perbarui definisi item dalam kamus</p>
                        </div>
                    </div>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="`/master-inspections/${formData.id}`" method="POST" class="p-6">
                    @csrf @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama {{ $currentTabName }} <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="formData.name" required class="w-full border-slate-300 rounded-lg text-xs py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-2xs transition">
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-slate-200 flex justify-end space-x-2.5">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-50 transition shadow-2xs">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold flex items-center hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition shadow-2xs">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 3: DELETE KONFIRMASI                                -->
        <!-- ======================================================== -->
        <div x-show="showDeleteModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all border border-slate-200" 
                 @click.away="showDeleteModal = false">
                
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center w-14 h-14 rounded-full bg-rose-100 mb-4">
                        <svg class="text-rose-600 w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1.5">Hapus Data Kamus?</h3>
                    <p class="mb-5 text-xs text-slate-500">Anda yakin ingin menghapus <span class="font-bold text-slate-800" x-text="formData.name"></span>? Tindakan ini dapat dibatalkan melalui audit log.</p>
                    
                    <div class="flex justify-center space-x-2.5">
                        <button @click="showDeleteModal = false" class="text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg text-xs font-bold px-4 py-2 transition shadow-2xs">Batal</button>
                        <form :action="`/master-inspections/${formData.id}`" method="POST" class="inline-flex">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-white bg-rose-600 hover:bg-rose-700 rounded-lg text-xs font-bold px-4 py-2 transition shadow-2xs">
                                Ya, Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>