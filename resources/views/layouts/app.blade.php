<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SIGR-ITS - Gestion ITS')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Suppression du script dark mode -->

<body class="h-full font-sans antialiased transition-colors duration-200 bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dynamique par Rôle -->
        @auth
            @php
                /** @var \App\Models\User $user */
                $user = Auth::user();
            @endphp
            @includeWhen($user->isAdmin(), 'layouts.components.sidebar.admin-sidebar')
            @includeWhen($user->isITS(), 'layouts.components.sidebar.its-sidebar')
            @includeWhen($user->isInspecteurGeneral(), 'layouts.components.sidebar.inspecteur-general-sidebar')
            @includeWhen($user->isPointFocal(), 'layouts.components.sidebar.pointfocal-sidebar')
            @includeWhen($user->isResponsable(), 'layouts.components.sidebar.responsable-sidebar')
            @includeWhen($user->isCabinetMinistre(), 'layouts.components.sidebar.cabinet-ministre-sidebar')
        @endauth

        <!-- Contenu Principal -->
        <div class="flex flex-col flex-1 min-w-0">
            @include('layouts.components.topnav')

            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="container px-6 py-8 mx-auto">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 flex items-center"
                            role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 flex items-center"
                            role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 border border-yellow-200 flex items-center"
                            role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <div>{{ session('warning') }}</div>
                        </div>
                    @endif

                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Scripts pushed from child views --}}
    @stack('scripts')

</body>

</html>