<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $pageTitle = $title ?? null;
            if (!$pageTitle) {
                $pageTitle = match(true) {
                    request()->routeIs('dashboard') => 'Dashboard',
                    request()->routeIs('dieset-status.show') => 'Detail Mold Status',
                    request()->routeIs('dieset-status.*') => 'Mold Status',
                    request()->routeIs('monitoring.show') => 'Detail Inspection Monitor',
                    request()->routeIs('monitoring*') => 'Inspection Monitor',
                    request()->routeIs('export') => 'Export Inspection Monitor',
                    request()->routeIs('parts-stock.*') => 'Parts Stock',
                    request()->routeIs('master-diesets.show') => 'Detail Master Mold',
                    request()->routeIs('master-diesets.*') => 'Master Mold',
                    request()->routeIs('master-parts.*') => 'Master Parts',
                    request()->routeIs('master-inspections.*') => 'Master Inspection',
                    request()->routeIs('operators.*') => 'Operator',
                    request()->routeIs('audit-logs.show') => 'Detail Audit Log',
                    request()->routeIs('audit-logs.*') => 'Audit Logs',
                    request()->routeIs('email-reports.*') => 'Email Report',
                    request()->routeIs('mold-requests.*') => 'SPK Mold Baru',
                    request()->routeIs('repair-tickets.*') => 'Tiket Perbaikan Mold',
                    request()->routeIs('rbac.*') => 'Role & Permission (RBAC)',
                    request()->routeIs('profile.*') => 'Profile',
                    request()->routeIs('inspections.*') => 'Inspections',
                    default => null,
                };
            }
            $fullTitle = $pageTitle 
                ? (\Illuminate\Support\Str::startsWith($pageTitle, 'DAS - ') ? $pageTitle : 'DAS - ' . $pageTitle)
                : 'DAS - Diecast Application System';
        @endphp
        <title>{{ $fullTitle }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 overflow-hidden">
        
        <!-- Alpine.js Global State for Layout -->
        <div x-data="{ sidebarOpen: true, isMobile: window.innerWidth < 1024 }" 
             @resize.window="isMobile = window.innerWidth < 1024; if(!isMobile) sidebarOpen = true"
             class="flex h-screen w-full bg-gray-50">

            <!-- Mobile Overlay -->
            <div x-cloak x-show="sidebarOpen && isMobile" 
                 x-transition.opacity 
                 @click="sidebarOpen = false"
                 class="fixed inset-0 z-20 bg-gray-900/60 lg:hidden backdrop-blur-sm">
            </div>

            <!-- Include Sidebar Component -->
            @include('layouts.sidebar')

            <!-- Main Content Wrapper -->
            <div class="flex-1 flex flex-col min-w-0 bg-gray-50 overflow-hidden">
                
                <!-- Include Topbar Component -->
                @include('layouts.topbar')

                <!-- Scrollable Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                    
                    <!-- Page Heading (Optional) -->
                    @isset($header)
                        <div class="bg-white border-b border-gray-200 shadow-sm px-6 py-4">
                            {{ $header }}
                        </div>
                    @endisset

                    <!-- Page Body -->
                    <div class="p-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>