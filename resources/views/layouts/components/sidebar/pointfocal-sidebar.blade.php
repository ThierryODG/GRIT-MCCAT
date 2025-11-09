<!-- Sidebar Point Focal -->
<aside class="flex flex-col w-64 h-screen bg-white border-r border-gray-200 shadow-xl">
    <!-- Logo & Titre -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-center mb-4">
            <img src="{{ asset('images/logo-mccat-300x300.jpg') }}" alt="Logo MCCAT" class="object-contain w-20 h-20 opacity-90">
        </div>
        <div class="text-center">
            <h2 class="text-lg font-bold text-gray-800">Point Focal</h2>
            <p class="mt-1 text-xs text-gray-500">Exécution Terrain</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <!-- Tableau de bord -->
        <a href="{{ route('point_focal.dashboard') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('point_focal.dashboard') ? 'bg-orange-50 text-orange-600 shadow-sm border-l-4 border-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="font-medium">Tableau de bord</span>
        </a>

        <!-- Mes Recommandations -->
        <a href="{{ route('point_focal.recommandations.index') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('point_focal.recommandations.*') ? 'bg-blue-50 text-blue-600 shadow-sm border-l-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="font-medium">Mes Recommandations</span>
        </a>

        <!-- Plans d'Action -->
        <a href="{{ route('point_focal.plans_action.index') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('point_focal.plans_action.*') ? 'bg-green-50 text-green-600 shadow-sm border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="font-medium">Plans d'Action</span>
        </a>

        <!-- Avancement -->
        <a href="{{ route('point_focal.avancement.index') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('point_focal.avancement.*') ? 'bg-purple-50 text-purple-600 shadow-sm border-l-4 border-purple-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="font-medium">Avancement</span>
        </a>

        <!-- Demande de Clôture -->
        <a href="{{ route('point_focal.cloture.index') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('point_focal.cloture.*') ? 'bg-red-50 text-red-600 shadow-sm border-l-4 border-red-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium">Demande Clôture</span>
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
