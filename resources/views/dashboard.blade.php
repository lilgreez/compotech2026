<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="bg-white border-b border-gray-200 px-6 py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl flex items-center justify-center text-white text-xl font-bold shadow-sm border border-indigo-200">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Welcome back, {{ auth()->user()->name }}</h1>
                
                <div class="flex items-center text-sm font-medium text-gray-500 mt-1">
                    <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span id="realtimeClock">Memuat waktu...</span>
                </div>
            </div>
        </div>
        <div class="hidden sm:block">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-sm font-bold bg-gray-50 text-gray-700 border border-gray-200 shadow-sm">
                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Role: {{ auth()->user()->role_name }}
            </span>
        </div>
    </div>

    <div class="p-6 max-w-[95rem] mx-auto space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Diesets</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 mt-2">{{ number_format($totalDiesets) }}</h3>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                </div>
                <div class="mt-5 flex items-center">
                    <a href="{{ route('master-diesets.index') }}" class="text-sm text-indigo-600 font-medium hover:text-indigo-800 flex items-center">
                        Kelola Dieset <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300 group relative overflow-hidden">
                @if($lowStockParts > 0)
                    <div class="absolute top-0 right-0 w-24 h-24 bg-red-100 rounded-bl-full opacity-40 animate-pulse pointer-events-none"></div>
                @endif
                
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Low Stock Parts</p>
                        <h3 class="text-3xl font-extrabold {{ $lowStockParts > 0 ? 'text-red-600' : 'text-gray-900' }} mt-2">{{ number_format($lowStockParts) }}</h3>
                    </div>
                    <div class="p-3 {{ $lowStockParts > 0 ? 'bg-red-50 text-red-600 group-hover:bg-red-100' : 'bg-gray-50 text-gray-400 group-hover:bg-gray-100' }} rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-5 flex items-center relative z-10">
                    <a href="{{ route('parts-stock.index',['tab' => 'low']) }}" class="text-sm {{ $lowStockParts > 0 ? 'text-red-600 hover:text-red-800' : 'text-gray-600 hover:text-gray-800' }} font-medium flex items-center">
                        Lihat Alert <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Inspections</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 mt-2">{{ number_format($totalInspections) }}</h3>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                </div>
                <div class="mt-5 flex items-center">
                    <a href="{{ route('monitoring') }}" class="text-sm text-emerald-600 font-medium hover:text-emerald-800 flex items-center">
                        Monitor Data <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Active Operators</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 mt-2">{{ number_format($totalOperators) }}</h3>
                    </div>
                    <div class="p-3 bg-orange-50 text-orange-600 rounded-lg group-hover:bg-orange-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-5 flex items-center">
                    <a href="{{ route('operators.index') }}" class="text-sm text-orange-600 font-medium hover:text-orange-800 flex items-center">
                        Kelola Akun <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Tren Inspeksi Harian</h3>
                        <p class="text-sm text-gray-500">Grafik frekuensi inspeksi dieset selama 7 hari terakhir.</p>
                    </div>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="inspectionChart" 
                            data-labels="{{ json_encode($chartDates) }}" 
                            data-values="{{ json_encode($chartData) }}">
                    </canvas>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                @if(auth()->user()->hasRole('Admin'))
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-md p-6 border border-gray-700 relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-white opacity-5 rounded-full pointer-events-none"></div>
                    <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-indigo-500 opacity-10 rounded-full pointer-events-none"></div>
                    
                    <h3 class="text-lg font-bold text-white mb-1.5 flex items-center relative z-10">
                        <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Admin Control Panel
                    </h3>
                    <p class="text-sm text-gray-400 mb-5 relative z-10">Akses manajerial superuser sistem.</p>
                    
                    <div class="space-y-3 relative z-10">
                        <a href="{{ route('audit-logs.index') }}" class="w-full flex items-center justify-between px-4 py-3 bg-gray-800/50 hover:bg-gray-700/80 text-white rounded-lg text-sm font-medium transition border border-gray-700 hover:border-indigo-500/50">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                System Audit Logs
                            </span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <a href="{{ route('email-reports.index') }}" class="w-full flex items-center justify-between px-4 py-3 bg-gray-800/50 hover:bg-gray-700/80 text-white rounded-lg text-sm font-medium transition border border-gray-700 hover:border-indigo-500/50">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Konfigurasi Email
                            </span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Laporan Cepat</h3>
                    <p class="text-sm text-gray-500 mb-4">Unduh rekapan data operasional.</p>
                    
                    <div class="space-y-3">
                        <a href="{{ route('dieset-status.export') }}" class="w-full flex items-center justify-between px-4 py-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 rounded-lg text-sm font-semibold transition border border-emerald-200">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Export Status Dieset
                            </span>
                            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Aktivitas Inspeksi Terbaru
                </h3>
                <a href="{{ route('monitoring') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center">
                    View All <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left whitespace-nowrap">
                    <thead class="text-xs text-gray-500 uppercase bg-white border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-100">Tanggal & Waktu</th>
                            <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-100">Dieset & Part</th>
                            <th scope="col" class="px-6 py-4 font-semibold border-r border-gray-100 text-center">Kondisi</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Mekanik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentInspections as $insp)
                            <tr class="bg-white hover:bg-indigo-50/30 transition-colors group">
                                <td class="px-6 py-4 border-r border-gray-100">
                                    <div class="font-medium text-gray-900">{{ $insp->inspection_date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $insp->inspection_date->format('H:i') }}</div>
                                </td>
                                
                                <td class="px-6 py-4 border-r border-gray-100">
                                    <p class="font-bold text-gray-900">{{ $insp->part->dieset->name ?? 'Unknown Dieset' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $insp->part->part_code ?? '-' }} - {{ $insp->part->name ?? '-' }}</p>
                                </td>
                                
                                <td class="px-6 py-4 text-center border-r border-gray-100">
                                    @php
                                        $condStyle = match($insp->condition) {
                                            'OK' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'Repair' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Replace' => 'bg-red-50 text-red-700 border-red-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-[11px] font-bold border tracking-wider uppercase {{ $condStyle }}">
                                        {{ $insp->condition }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 text-gray-800 font-medium flex items-center">
                                    <div class="h-6 w-6 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-[10px] mr-2.5 border border-gray-200">
                                        {{ substr($insp->operator->name ?? 'U', 0, 1) }}
                                    </div>
                                    {{ $insp->operator->name ?? 'Unknown' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-3 shadow-sm">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium text-sm">Belum ada data inspeksi yang direkam oleh sistem.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // --- 1. SCRIPT REALTIME CLOCK ---
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
                if(clockElement) {
                    clockElement.innerText = `${dayName}, ${day} ${month} ${year} | ${h}:${m}:${s}`;
                }
            }
            setInterval(updateClock, 1000);
            updateClock();

            // --- 2. SCRIPT CHART.JS TREN INSPEKSI ---
            const canvas = document.getElementById('inspectionChart');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                
                // Parsing data dari atribut HTML agar VS Code Linter tidak rewel
                const labels = JSON.parse(canvas.getAttribute('data-labels'));
                const data = JSON.parse(canvas.getAttribute('data-values'));

                // Menggunakan tema warna Indigo-600 Tailwind (#4f46e5)
                let gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(79, 70, 229, 0.4)'); 
                gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Inspeksi',
                            data: data,
                            borderColor: '#4f46e5', 
                            backgroundColor: gradient,
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#4f46e5',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4 
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleFont: { size: 13, family: "'Inter', sans-serif" },
                                bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                                padding: 12,
                                displayColors: false,
                                cornerRadius: 8,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0, color: '#64748b', font: { family: "'Inter', sans-serif" } },
                                grid: { color: '#f1f5f9', drawBorder: false }
                            },
                            x: {
                                ticks: { color: '#64748b', font: { family: "'Inter', sans-serif" } },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>