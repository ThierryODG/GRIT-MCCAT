<!-- Sidebar Cabinet Ministre (Premium Royal Theme) -->
<aside
    class="flex flex-col w-64 h-screen bg-slate-900 border-r border-slate-800 shadow-2xl transition-all duration-300">
    <!-- Logo & Titre -->
    <div class="h-28 flex items-center justify-center border-b border-slate-800 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 to-slate-800"></div>
        <div class="relative z-10 flex items-center space-x-4">
            <div
                class="w-20 h-20 bg-white rounded-lg flex items-center justify-center shadow-lg shadow-amber-500/20 p-2">
                <img src="{{ asset('images/logo-mccat-300x300.jpg') }}" alt="Logo MCCAT"
                    class="object-contain w-full h-full opacity-90">
            </div>
            <div>
                <h1 class="font-black text-lg tracking-tight text-white leading-none">CABINET</h1>
                <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest">Ministre</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-6 space-y-1 px-3">

        <!-- Section: PILOTAGE (No Header label requested) -->

        <a href="{{ route('cabinet_ministre.dashboard') }}"
            class="group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 
           {{ request()->routeIs('cabinet_ministre.dashboard') ? 'bg-slate-800 text-white border-l-4 border-amber-500 shadow-lg' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('cabinet_ministre.dashboard') ? 'text-amber-500' : 'text-slate-500 group-hover:text-amber-500' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            Vue d'Ensemble
        </a>

        <a href="{{ route('cabinet_ministre.suivi.index') }}"
            class="group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200
           {{ request()->routeIs('cabinet_ministre.suivi.*') ? 'bg-slate-800 text-white border-l-4 border-amber-500 shadow-lg' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('cabinet_ministre.suivi.*') ? 'text-amber-500' : 'text-slate-500 group-hover:text-amber-500' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Suivi & Performance
        </a>

        <!-- Section: URGENCES -->
        <div class="px-3 mb-2 mt-6">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Urgences</p>
        </div>

        <a href="{{ route('cabinet_ministre.alertes') }}"
            class="group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200
           {{ request()->routeIs('cabinet_ministre.alertes*') ? 'bg-slate-800 text-white border-l-4 border-red-500 shadow-lg' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('cabinet_ministre.alertes*') ? 'text-red-500' : 'text-slate-500 group-hover:text-red-500' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Alertes & Retards
        </a>

        <!-- Section: SYNTHESES -->
        <div class="px-3 mb-2 mt-6">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Synthèses</p>
        </div>

        <a href="{{ route('cabinet_ministre.rapports.index') }}"
            class="group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200
           {{ request()->routeIs('cabinet_ministre.rapports.*') ? 'bg-slate-800 text-white border-l-4 border-blue-500 shadow-lg' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('cabinet_ministre.rapports.*') ? 'text-blue-500' : 'text-slate-500 group-hover:text-blue-500' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Rapports & Synthèse
        </a>

    </nav>

    <!-- User Profile (Bottom) -->
    <div class="border-t border-slate-800 p-4 bg-slate-900/50">
        <div class="flex items-center">
            <div
                class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white font-bold text-xs ring-2 ring-slate-800">
                {{ substr(Auth::user()->name ?? 'M', 0, 1) }}
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white group-hover:text-amber-400 transition-colors line-clamp-1">
                    {{ Auth::user()->name ?? 'Ministre' }}
                </p>
                <p class="text-xs text-slate-500">Cabinet</p>
            </div>
        </div>
    </div>
</aside>