<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'GRIT - Gestion ITS')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Suppression du script dark mode -->
<body class="h-full font-sans antialiased transition-colors duration-200 bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dynamique par Rôle -->
        @auth
            @includeWhen(Auth::user()->isAdmin(), 'layouts.components.sidebar.admin-sidebar')
            @includeWhen(Auth::user()->isITS(), 'layouts.components.sidebar.its-sidebar')
            @includeWhen(Auth::user()->isInspecteurGeneral(), 'layouts.components.sidebar.inspecteur-general-sidebar')
            @includeWhen(Auth::user()->isPointFocal(), 'layouts.components.sidebar.pointfocal-sidebar')
            @includeWhen(Auth::user()->isResponsable(), 'layouts.components.sidebar.responsable-sidebar')
            @includeWhen(Auth::user()->isCabinetMinistre(), 'layouts.components.sidebar.cabinet-ministre-sidebar')
        @endauth

        <!-- Contenu Principal -->
        <div class="flex flex-col flex-1 min-w-0">
            @include('layouts.components.topnav')

            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="container px-6 py-8 mx-auto">
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
