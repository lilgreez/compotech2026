<header class="h-16 flex items-center justify-between px-4 sm:px-6 bg-white shadow-sm border-b border-gray-100 z-10 shrink-0">
    <div class="flex items-center">
        <!-- Hamburger Button (Toggles Sidebar) -->
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none hover:text-gray-700 hover:bg-gray-100 p-2 rounded-md transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>

        <!-- Search Box -->
        <div class="hidden sm:flex items-center ml-6 bg-gray-50 border border-gray-200 rounded-full px-4 py-1.5 focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-500 transition-all">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Search..." class="bg-transparent border-none focus:ring-0 text-sm text-gray-700 ml-2 w-48 placeholder-gray-400">
        </div>
    </div>

    <div class="flex items-center space-x-4">
        <!-- User Profile Dropdown -->
        <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="flex items-center focus:outline-none space-x-3 p-1 rounded-full hover:bg-gray-50 transition-colors">
                <!-- Inisial Nama User -->
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shadow-sm ring-2 ring-indigo-50">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <!-- Nama & Role User -->
                <div class="hidden sm:flex flex-col text-left">
                    <span class="text-sm font-bold text-gray-700 leading-none">{{ auth()->user()->name }}</span>
                    <span class="text-[10px] text-gray-500 mt-1">{{ auth()->user()->role_name }}</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="dropdownOpen" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-lg py-2 ring-1 ring-black ring-opacity-5 z-50" style="display: none;">
                
                <div class="px-4 py-2 border-b border-gray-100 sm:hidden">
                    <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>

                <!-- ANDREW FIX: Menghapus 'block', menggunakan 'flex w-full' agar rapi dan tidak bentrok -->
                <a href="{{ route('profile.edit') }}" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profile Settings
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <!-- ANDREW FIX: Menghapus 'block', menggunakan 'flex w-full' -->
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors mt-1 border-t border-gray-100">
                        <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Log Out
                    </a>
                </form>
            </div>
        </div>
    </div>
</header>