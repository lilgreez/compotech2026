<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Compotech DAS') }} - Diecast Application System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans text-gray-900 bg-gray-50 selection:bg-blue-500 selection:text-white">
    
    <!-- Navbar -->
    <nav class="absolute top-0 w-full z-50 py-6 px-8 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/30 text-xl">
                C
            </div>
            <span class="text-2xl font-extrabold text-white tracking-wide">COMPOTECH</span>
        </div>
        <div>
            @if (Route::has('login'))
                <div class="space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-white hover:text-blue-200 transition">Go to Dashboard &rarr;</a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-full text-sm font-semibold transition backdrop-blur-sm">Log in</a>
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <!-- Hero Section (Dark Blue Industrial Theme) -->
    <div class="relative bg-[#1e2336] min-h-[75vh] flex items-center justify-center overflow-hidden">
        <!-- Background Pattern/Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#2a3042] via-[#1e2336] to-[#121626]"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9IiNmZmYiLz48L3N2Zz4=')]"></div>

        <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto mt-16">
            <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 text-blue-300 text-xs font-bold tracking-widest uppercase mb-6 border border-blue-500/30 backdrop-blur-sm">
                Internal Management System
            </span>
            <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold text-white tracking-tight mb-6 drop-shadow-md">
                Diecast Application <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">System</span>
            </h1>
            <p class="mt-4 text-lg sm:text-xl text-gray-400 max-w-3xl mx-auto mb-10 leading-relaxed">
                Platform terintegrasi untuk memonitor siklus produksi, manajemen master dieset, kontrol stok *spare parts*, dan historis inspeksi secara *real-time* dan presisi.
            </p>
            
            <div class="flex justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-8 py-3.5 bg-blue-600 hover:bg-blue-500 text-white rounded-full text-base font-bold shadow-lg shadow-blue-600/40 transition transform hover:-translate-y-1">
                        Masuk ke Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-3.5 bg-blue-600 hover:bg-blue-500 text-white rounded-full text-base font-bold shadow-lg shadow-blue-600/40 transition transform hover:-translate-y-1 flex items-center">
                        Login ke Sistem
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-white relative z-20 -mt-10 rounded-t-[3rem] shadow-[0_-10px_40px_rgba(0,0,0,0.1)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
                
                <!-- Feature 1 -->
                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-lg transition duration-300">
                    <div class="w-14 h-14 mx-auto bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-6 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Monitoring Asset</h3>
                    <p class="text-gray-500 text-sm">Pantau aktual shoot mesin, jadwal pergantian parts, dan manajemen status dieset dengan indikator cerdas.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-lg transition duration-300">
                    <div class="w-14 h-14 mx-auto bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 mb-6 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Master Data Control</h3>
                    <p class="text-gray-500 text-sm">Sistem terpusat untuk mengelola Katalog Parts dari Wings ERP, Master Dieset, dan Kamus Inspeksi.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-lg transition duration-300">
                    <div class="w-14 h-14 mx-auto bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Automated Reports</h3>
                    <p class="text-gray-500 text-sm">Otomatisasi pengiriman email untuk peringatan stok rendah ke Supervisor beserta lampiran laporan Excel.</p>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Compotech. All rights reserved. <br>
            <span class="text-xs text-gray-400 mt-1 block">Diecast Application System - Built for Enterprise Scale.</span>
        </div>
    </footer>

</body>
</html>