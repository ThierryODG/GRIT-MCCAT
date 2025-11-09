<!-- Sidebar Cabinet Ministre -->
<aside class="flex flex-col w-64 h-screen bg-white border-r border-gray-200 shadow-xl">
    <!-- Logo & Titre -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-center mb-4">
            <img src="{{ asset('images/logo-mccat-300x300.jpg') }}" alt="Logo MCCAT" class="object-contain w-20 h-20 opacity-90">
        </div>
        <div class="text-center">
            <h2 class="text-lg font-bold text-gray-800">Cabinet Ministre</h2>
            <p class="mt-1 text-xs text-gray-500">Vision Stratégique</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <!-- Vue d'Ensemble -->
        <a href="{{ route('cabinet_ministre.dashboard') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('cabinet_ministre.dashboard') ? 'bg-red-50 text-red-600 shadow-sm border-l-4 border-red-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="font-medium">Vue d'Ensemble</span>
        </a>

        <!-- Indicateurs Clés -->
        {{-- <a href="{{ route('cabinet_ministre.indicateurs') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('cabinet_ministre.indicateurs') ? 'bg-blue-50 text-blue-600 shadow-sm border-l-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="font-medium">Indicateurs Clés</span>
        </a> --}}

        <!-- Suivi Stratégique -->
        <a href="{{ route('cabinet_ministre.suivi.index') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('cabinet_ministre.suivi.*') ? 'bg-green-50 text-green-600 shadow-sm border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span class="font-medium">Suivi Stratégique</span>
        </a>

        <!-- Alertes & Urgences -->
        <a href="{{ route('cabinet_ministre.alertes') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('cabinet_ministre.alertes') ? 'bg-yellow-50 text-yellow-600 shadow-sm border-l-4 border-yellow-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span class="font-medium">Alertes & Urgences</span>
        </a>

        <!-- Décisions -->
        {{-- <a href="{{ route('cabinet_ministre.decisions') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('cabinet_ministre.decisions') ? 'bg-purple-50 text-purple-600 shadow-sm border-l-4 border-purple-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span class="font-medium">Décisions</span>
        </a> --}}

        <!-- Rapports Ministériels -->
        <a href="{{ route('cabinet_ministre.rapports.index') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('cabinet_ministre.rapports.*') ? 'bg-orange-50 text-orange-600 shadow-sm border-l-4 border-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="font-medium">Rapports Ministériels</span>
        </a>
    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-200 bg-gray-50">
        <div class="text-center">
            <p class="text-xs font-semibold text-gray-600">GRIT - MCCAT</p>
            <p class="mt-1 text-xs text-gray-400">Gestion des Recommandations ITS</p>
        </div>
    </div>
</aside>
