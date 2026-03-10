<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900 flex items-center justify-between">
                    <div>
                        <span class="text-gray-500">Welcome back,</span> 
                        <span class="font-bold text-lg ml-1">{{ auth()->user()->name }}</span>
                    </div>
                    <div>
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                            Role: {{ auth()->user()->role_name }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Admin Only Section -->
            @if(auth()->user()->hasRole('Admin'))
                <div class="bg-indigo-50 border-l-4 border-indigo-500 p-6 sm:rounded-lg shadow-sm">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <h3 class="text-xl font-bold text-indigo-900">Admin Control Panel</h3>
                    </div>
                    <p class="text-indigo-700 mb-5">You have full superuser access to master data management and security audit logs.</p>
                    <div class="flex space-x-3">
                        <a href="{{ route('master-diesets.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 transition ease-in-out duration-150">
                            Manage Diesets
                        </a>
                        <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-indigo-300 rounded-md font-semibold text-xs text-indigo-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            View Audit Logs
                        </a>
                    </div>
                </div>
            @endif

            <!-- Supervisor Section -->
            @if(auth()->user()->hasRole('Supervisor'))
                <div class="bg-teal-50 border-l-4 border-teal-500 p-6 sm:rounded-lg shadow-sm">
                    <h3 class="text-xl font-bold text-teal-900 mb-2">Supervisor Panel</h3>
                    <p class="text-teal-700 mb-5">Monitor all production activities and generate reports.</p>
                    <div class="flex space-x-3">
                        <a href="{{ route('monitoring') }}" class="px-4 py-2 bg-teal-600 text-white rounded-md text-xs font-bold uppercase hover:bg-teal-700 transition">Live Monitoring</a>
                        <a href="{{ route('export') }}" class="px-4 py-2 bg-white text-teal-700 border border-teal-300 rounded-md text-xs font-bold uppercase hover:bg-teal-50 transition">Export Reports</a>
                    </div>
                </div>
            @endif

            <!-- Maintenance Section -->
            @if(auth()->user()->hasRole('Maintenance'))
                <div class="bg-amber-50 border-l-4 border-amber-500 p-6 sm:rounded-lg shadow-sm">
                    <h3 class="text-xl font-bold text-amber-900 mb-2">Maintenance Panel</h3>
                    <p class="text-amber-700 mb-5">Manage and update all machine inspection records.</p>
                    <div class="flex space-x-3">
                        <a href="{{ route('inspections.index') }}" class="px-4 py-2 bg-amber-500 text-white rounded-md text-xs font-bold uppercase hover:bg-amber-600 transition">View Inspections</a>
                        <a href="{{ route('inspections.create') }}" class="px-4 py-2 bg-white text-amber-700 border border-amber-300 rounded-md text-xs font-bold uppercase hover:bg-amber-50 transition">Create Inspection</a>
                    </div>
                </div>
            @endif

            <!-- Operator Section -->
            @if(auth()->user()->hasRole('Operator'))
                <div class="bg-slate-50 border-l-4 border-slate-500 p-6 sm:rounded-lg shadow-sm">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Operator Panel</h3>
                    <p class="text-slate-700 mb-5">Submit new inspection requests securely.</p>
                    <div class="flex space-x-3">
                        <a href="{{ route('inspections.create') }}" class="px-4 py-2 bg-slate-800 text-white rounded-md text-xs font-bold uppercase hover:bg-slate-900 transition">Create Inspection</a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>