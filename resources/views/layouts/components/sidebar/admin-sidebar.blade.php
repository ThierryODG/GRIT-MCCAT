<!-- Sidebar Administrateur -->
<aside class="flex flex-col w-64 h-screen bg-white border-r border-gray-200 shadow-xl">
    <!-- Logo & Titre -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-center mb-4">
            <img src="{{ asset('images/logo-mccat-300x300.jpg') }}" alt="Logo MCCAT" class="object-contain w-20 h-20 opacity-90">
        </div>
        <div class="text-center">
            <h2 class="text-lg font-bold text-gray-800">Administration</h2>
            <p class="mt-1 text-xs text-gray-500">Gestion Système</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 shadow-sm border-l-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="font-medium">Tableau de bord</span>
        </a>

        <!-- Gestion Utilisateurs -->
        <a href="{{ route('admin.users.index') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-green-50 text-green-600 shadow-sm border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span class="font-medium">Utilisateurs</span>
        </a>

        <!-- Gestion des Rôles -->
        <a href="{{ route('admin.roles.index') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.roles.*') ? 'bg-yellow-50 text-yellow-600 shadow-sm border-l-4 border-yellow-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span class="font-medium">Rôles & Permissions</span>
        </a>

    <!-- Rapports Globaux -->
    {{-- <a href="{{ route('admin.rapports.index') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.rapports.*') ? 'bg-purple-50 text-purple-600 shadow-sm border-l-4 border-purple-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="font-medium">Rapports Globaux</span>
        </a> --}}

        <!-- Paramètres Système -->
        <a href="{{ route('admin.settings.index') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-red-50 text-red-600 shadow-sm border-l-4 border-red-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="font-medium">Paramètres Système</span>
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
