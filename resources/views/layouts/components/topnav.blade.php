<!-- Top Navigation -->
<header class="bg-white border-b border-red-200 shadow-sm">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Page Title & Breadcrumb -->
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                @yield('title', 'Tableau de Bord')
            </h1>
            @hasSection('breadcrumb')
                <nav class="flex mt-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            @endif
        </div>

        <!-- User Menu & Actions -->
        <div class="flex items-center space-x-4">
            {{-- <!-- Search Bar --> --}}
            <div class="relative">
                <input type="text" placeholder="Rechercher..." class="w-64 py-2 pl-10 pr-4 transition-colors duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <svg class="absolute w-5 h-5 text-gray-400 transform -translate-y-1/2 left-3 top-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Notifications -->
            <button class="relative p-2 text-gray-500 transition-colors rounded-lg hover:text-gray-700 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.5 3.75a6 6 0 0 0-6 6v2.25l-2.47 2.47a.75.75 0 0 0 .53 1.28h15.88a.75.75 0 0 0 .53-1.28L16.5 12V9.75a6 6 0 0 0-6-6z"/>
                </svg>
                <span class="absolute w-2 h-2 bg-red-500 rounded-full top-1 right-1"></span>
            </button>

            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center px-3 py-2 space-x-3 text-sm transition-colors rounded-lg focus:outline-none hover:bg-gray-100">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600">
                        <span class="text-xs font-medium text-white">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                    </div>
                    <div class="hidden text-left md:block">
                        <p class="font-medium text-gray-700">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">
                            @auth
                                @if(Auth::user()->hasRole('administrateur')) Administrateur
                                @elseif(Auth::user()->hasRole('its')) ITS
                                @elseif(Auth::user()->hasRole('inspecteur_general')) Inspecteur Général
                                @elseif(Auth::user()->hasRole('point_focal')) Point Focal
                                @elseif(Auth::user()->hasRole('responsable')) Responsable
                                @elseif(Auth::user()->hasRole('cabinet_ministre')) Cabinet Ministre
                                @else Utilisateur
                                @endif
                            @endauth
                        </p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false" x-cloak
                     class="absolute right-0 z-50 w-48 py-1 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Mon profil
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.5 3.75a6 6 0 0 0-6 6v2.25l-2.47 2.47a.75.75 0 0 0 .53 1.28h15.88a.75.75 0 0 0 .53-1.28L16.5 12V9.75a6 6 0 0 0-6-6z"/>
                        </svg>
                        Notifications
                    </a>
                    <div class="my-1 border-t border-gray-100"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-left text-red-700 transition-colors hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
