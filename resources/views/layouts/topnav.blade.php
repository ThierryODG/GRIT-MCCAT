<!-- Top Navigation -->
<header class="bg-white shadow-sm border-b">
    <div class="flex justify-between items-center px-6 py-4">
        <!-- Page Title -->
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                @yield('page-title', 'GRIT - Suivi ITS')
            </h1>
        </div>

        <!-- User Menu -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="p-2 text-gray-500 hover:text-gray-700">
                <i class="fas fa-bell"></i>
            </button>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <span>{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user-edit mr-2"></i>Mon Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
