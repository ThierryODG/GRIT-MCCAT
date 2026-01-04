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
            <!-- Search Bar Removed as per user request (unused) -->

            <!-- Notifications Component -->
            <div class="relative" x-data="{ 
                open: false, 
                count: {{ Auth::user()->unreadNotifications->count() }}, 
                notifications: [],
                loading: false,
                init() {
                    // Optionnel: Polling ou chargement au clic
                },
                fetchNotifications() {
                    this.loading = true;
                    fetch('{{ route('notifications.list') }}')
                        .then(response => response.json())
                        .then(data => {
                            this.notifications = data;
                            this.loading = false;
                        });
                },
                markAsRead(id, url) {
                    fetch(`/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(() => {
                        this.count = Math.max(0, this.count - 1);
                        if(url) window.location.href = url;
                    });
                }
            }">
                <button @click="open = !open; if(open) fetchNotifications()"
                    class="relative p-2 text-gray-500 transition-colors rounded-lg hover:text-gray-700 hover:bg-gray-100 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-5 5v-5zM10.5 3.75a6 6 0 0 0-6 6v2.25l-2.47 2.47a.75.75 0 0 0 .53 1.28h15.88a.75.75 0 0 0 .53-1.28L16.5 12V9.75a6 6 0 0 0-6-6z" />
                    </svg>
                    <span x-show="count > 0" x-text="count"
                        class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full"></span>
                </button>

                <!-- Notification Dropdown -->
                <div x-show="open" @click.away="open = false" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute right-0 z-50 w-80 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden">

                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-800">Notifications</span>
                        <a href="{{ route('notifications.index') }}"
                            class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Voir tout</a>
                    </div>

                    <div class="max-h-96 overflow-y-auto">
                        <template x-if="loading">
                            <div class="p-4 text-center text-gray-500 text-sm">Chargement...</div>
                        </template>

                        <template x-if="!loading && notifications.length === 0">
                            <div class="p-4 text-center text-gray-500 text-sm">Aucune nouvelle notification</div>
                        </template>

                        <template x-for="notif in notifications" :key="notif.id">
                            <div @click="markAsRead(notif.id, notif.data.action_url)"
                                class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 cursor-pointer transition-colors duration-150 flex items-start gap-3">
                                <div :class="{
                                    'bg-blue-100 text-blue-600': notif.data.type === 'info', 
                                    'bg-green-100 text-green-600': notif.data.type === 'success',
                                    'bg-red-100 text-red-600': notif.data.type === 'error',
                                    'bg-indigo-100 text-indigo-600': true // default
                                }" class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center mt-1">
                                    <!-- Simple dot or icon based on type -->
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-800 font-medium" x-text="notif.data.message"></p>
                                    <p class="text-xs text-gray-500 mt-1" x-text="notif.created_at_human"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center px-3 py-2 space-x-3 text-sm transition-colors rounded-lg focus:outline-none hover:bg-gray-100">
                    <div
                        class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600">
                        <span
                            class="text-xs font-medium text-white">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false" x-cloak
                    class="absolute right-0 z-50 w-48 py-1 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Mon profil
                    </a>
                    <a href="{{ route('notifications.index') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-5 5v-5zM10.5 3.75a6 6 0 0 0-6 6v2.25l-2.47 2.47a.75.75 0 0 0 .53 1.28h15.88a.75.75 0 0 0 .53-1.28L16.5 12V9.75a6 6 0 0 0-6-6z" />
                        </svg>
                        Notifications
                    </a>
                    <div class="my-1 border-t border-gray-100"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full px-4 py-2 text-sm text-left text-red-700 transition-colors hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>