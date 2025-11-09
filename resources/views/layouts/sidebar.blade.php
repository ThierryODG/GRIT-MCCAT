<!-- Sidebar -->
<div class="w-64 bg-gray-800 text-white flex flex-col">
    <!-- Logo -->
    <div class="p-4 border-b border-gray-700">
        <div class="flex items-center">
            <div class="bg-white p-2 rounded-lg mr-3">
                <i class="fas fa-chart-line text-gray-800 text-xl"></i>
            </div>
            <span class="text-xl font-bold">GRIT</span>
        </div>
        <div class="text-sm text-gray-300 mt-1">Suivi ITS</div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-2">
        @auth
            <!-- Tableau de bord selon le rôle -->
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Tableau de bord</span>
                </a>
            @elseif(Auth::user()->isPointFocal())
                <a href="{{ route('pointfocal.dashboard') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('pointfocal.dashboard') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Tableau de bord</span>
                </a>
            @else
                <!-- Tableau de bord pour ITS et Inspecteur Général -->
                <a href="{{ route('dashboard') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Tableau de bord</span>
                </a>
            @endif

            <!-- === ADMINISTRATEUR === -->
            @if(Auth::user()->isAdmin())
            <div class="mt-4">
                <div class="text-xs uppercase text-gray-400 font-semibold mb-2">Administration</div>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-users mr-3"></i>
                    <span>Utilisateurs</span>
                </a>
            </div>
            @endif

            <!-- === ITS === -->
            @if(Auth::user()->isITS())
            <div class="mt-4">
                <div class="text-xs uppercase text-gray-400 font-semibold mb-2">ITS</div>

                <a href="{{ route('recommandations.index') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('recommandations.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-edit mr-3"></i>
                    <span>Recommandations</span>
                </a>

                <a href="{{ route('plan-actions.index') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('plan-actions.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-tasks mr-3"></i>
                    <span>Plans d'Action</span>
                </a>
            </div>
            @endif

            <!-- === INSPECTEUR GÉNÉRAL === -->
            @if(Auth::user()->isInspecteurGeneral())
            <div class="mt-4">
                <div class="text-xs uppercase text-gray-400 font-semibold mb-2">Inspection</div>

                <a href="{{ route('recommandations.index') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('recommandations.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-clipboard-check mr-3"></i>
                    <span>Validation Recommandations</span>
                </a>

                <a href="{{ route('plan-actions.index') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('plan-actions.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Suivi des Plans</span>
                </a>
            </div>
            @endif

            <!-- === POINT FOCAL === -->
            @if(Auth::user()->isPointFocal())
            <div class="mt-4">
                <div class="text-xs uppercase text-gray-400 font-semibold mb-2">Point Focal</div>

                <a href="{{ route('recommandations.index') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('recommandations.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-inbox mr-3"></i>
                    <span>Mes Recommandations</span>
                </a>

                <a href="{{ route('recommandations.sans-plan') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('recommandations.sans-plan') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-plus-circle mr-3"></i>
                    <span>Créer Plans d'Action</span>
                </a>

                <a href="{{ route('plan-actions.index') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('plan-actions.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-tasks mr-3"></i>
                    <span>Mes Plans d'Action</span>
                </a>

                <a href="{{ route('plan-actions.dashboard') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('plan-actions.dashboard') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Tableau de Bord</span>
                </a>
            </div>
            @endif

            <!-- === MENU COMMUN À TOUS LES RÔLES === -->
            <div class="mt-4">
                <div class="text-xs uppercase text-gray-400 font-semibold mb-2">Général</div>

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('profile.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-user-cog mr-3"></i>
                    <span>Mon Profil</span>
                </a>
            </div>

        @endauth
    </nav>

    <!-- User Info -->
    <div class="p-4 border-t border-gray-700">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-sm"></i>
            </div>
            <div class="ml-3">
                <div class="text-sm font-medium">{{ Auth::user()->name ?? 'Utilisateur' }}</div>
                <div class="text-xs text-gray-300 capitalize">
                    @if(Auth::user()->isAdmin())
                        Administrateur
                    @elseif(Auth::user()->isITS())
                        ITS
                    @elseif(Auth::user()->isInspecteurGeneral())
                        Inspecteur Général
                    @elseif(Auth::user()->isPointFocal())
                        Point Focal
                    @else
                        {{ Auth::user()->role ?? 'Rôle' }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
