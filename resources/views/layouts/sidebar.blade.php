<aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-0'" 
       class="fixed inset-y-0 left-0 z-30 flex flex-col transition-all duration-300 transform bg-[#2a3042] text-gray-300 lg:static lg:inset-0 shadow-xl overflow-y-auto scrollbar-hide shrink-0">
    
    <!-- Sidebar Header / Logo -->
    <div class="flex items-center justify-between px-6 h-16 bg-[#262b3c] border-b border-gray-700/50 shrink-0">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold shadow-lg">
                D
            </div>
            <span class="text-xl font-bold text-white tracking-wider" x-show="sidebarOpen">DieCast</span>
        </div>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="flex-1 px-3 py-6 space-y-1">
        
        <!-- MENU GROUP: DIECAST -->
        <div class="mb-6" x-show="sidebarOpen">
            <p class="px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-3">DieCast</p>
            
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-[#32394e] text-white border-l-4 border-blue-500' : 'hover:bg-[#32394e] hover:text-white border-l-4 border-transparent' }} flex items-center px-3 py-2.5 rounded-r-md transition-colors">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <!-- Placeholder untuk Monitoring Asset general (bisa dibiarkan # jika tidak dipakai) -->
            <a href="#" class="hover:bg-[#32394e] hover:text-white border-l-4 border-transparent flex items-center px-3 py-2.5 rounded-r-md transition-colors mt-1">
                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="text-sm font-medium">Monitoring Asset</span>
            </a>
        </div>

        <!-- MENU GROUP: DIESET -->
        <div class="mb-6" x-show="sidebarOpen">
            <p class="px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-3">DieSet</p>
            
            <a href="{{ route('dieset-status.index') }}" class="{{ request()->routeIs('dieset-status.*') ? 'bg-[#32394e] text-white border-l-4 border-blue-500' : 'hover:bg-[#32394e] hover:text-white border-l-4 border-transparent' }} flex items-center px-3 py-2.5 rounded-r-md transition-colors">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dieset-status.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="text-sm font-medium">Dieset Status</span>
            </a>

            <a href="{{ route('monitoring') }}" class="{{ request()->routeIs('monitoring*') ? 'bg-[#32394e] text-white border-l-4 border-blue-500' : 'hover:bg-[#32394e] hover:text-white border-l-4 border-transparent' }} flex items-center px-3 py-2.5 rounded-r-md transition-colors mt-1">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('monitoring*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                <span class="text-sm font-medium">Inspection Monitor</span>
            </a>

            <a href="{{ route('parts-stock.index') }}" class="{{ request()->routeIs('parts-stock.*') ? 'bg-[#32394e] text-white border-l-4 border-blue-500' : 'hover:bg-[#32394e] hover:text-white border-l-4 border-transparent' }} flex items-center px-3 py-2.5 rounded-r-md transition-colors mt-1">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('parts-stock.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="text-sm font-medium">Parts Stock</span>
            </a>

            <!-- Dropdown Master -->
            @php
                $isMasterActive = request()->routeIs('master-diesets.*') || request()->routeIs('master-parts.*') || request()->routeIs('master-inspections.*');
            @endphp
            <div x-data="{ masterOpen: {{ $isMasterActive ? 'true' : 'false' }} }" class="mt-1">
                <button @click="masterOpen = !masterOpen" class="w-full flex items-center justify-between px-3 py-2.5 rounded-r-md {{ $isMasterActive ? 'bg-[#32394e] text-white border-l-4 border-blue-500' : 'hover:bg-[#32394e] hover:text-white border-l-4 border-transparent' }} transition-colors focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 {{ $isMasterActive ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span class="text-sm font-medium">Master</span>
                    </div>
                    <svg :class="{'rotate-180': masterOpen}" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <!-- Submenu -->
                <div x-show="masterOpen" x-transition.opacity class="bg-[#222736] mt-1 py-2 space-y-1 rounded-md">
                    <a href="{{ route('master-diesets.index') }}" class="block px-11 py-2 text-sm {{ request()->routeIs('master-diesets.*') ? 'text-white bg-[#32394e]' : 'text-gray-400 hover:text-white hover:bg-[#32394e]' }} transition-colors">Master Dieset</a>
                    <a href="{{ route('master-parts.index') }}" class="block px-11 py-2 text-sm {{ request()->routeIs('master-parts.*') ? 'text-white bg-[#32394e]' : 'text-gray-400 hover:text-white hover:bg-[#32394e]' }} transition-colors">Master Parts</a>
                    <a href="{{ route('master-inspections.index') }}" class="block px-11 py-2 text-sm {{ request()->routeIs('master-inspections.*') ? 'text-white bg-[#32394e]' : 'text-gray-400 hover:text-white hover:bg-[#32394e]' }} transition-colors">Master Inspection</a>
                </div>
            </div>
        </div>

        <!-- MENU GROUP: ADMINISTRATOR -->
        @if(auth()->check() && auth()->user()->hasRole('Admin'))
        <div class="mb-6" x-show="sidebarOpen">
            <p class="px-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-3">Administrator</p>
            
            <a href="{{ route('operators.index') }}" class="{{ request()->routeIs('operators.*') ? 'bg-[#32394e] text-white border-l-4 border-blue-500' : 'hover:bg-[#32394e] hover:text-white border-l-4 border-transparent' }} flex items-center px-3 py-2.5 rounded-r-md transition-colors">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('operators.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="text-sm font-medium">Operator</span>
            </a>

            <a href="{{ route('audit-logs.index') }}" class="{{ request()->routeIs('audit-logs.*') ? 'bg-[#32394e] text-white border-l-4 border-blue-500' : 'hover:bg-[#32394e] hover:text-white border-l-4 border-transparent' }} flex items-center px-3 py-2.5 rounded-r-md transition-colors mt-1">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('audit-logs.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="text-sm font-medium">Audit Logs</span>
            </a>

            <!-- ANDREW FIX: Menu Email Report -->
            <a href="{{ route('email-reports.index') }}" class="{{ request()->routeIs('email-reports.*') ? 'bg-[#32394e] text-white border-l-4 border-blue-500' : 'hover:bg-[#32394e] hover:text-white border-l-4 border-transparent' }} flex items-center px-3 py-2.5 rounded-r-md transition-colors mt-1">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('email-reports.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span class="text-sm font-medium">Email Report</span>
            </a>
        </div>
        @endif
    </nav>
</aside>