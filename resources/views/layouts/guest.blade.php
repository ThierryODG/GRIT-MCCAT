<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SIGR-ITS' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="login-container">
        <!-- Partie gauche : Formulaire -->
        <div class="login-left">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>

        <!-- Partie droite : Image/Motif -->
        <div class="login-right"
            style="background-image: url('{{ asset('images/bg.jpg') }}'); background-size: cover; background-position: center;">
            <div class="background-pattern"></div>
            <div class="text-center relative z-10 max-w-md mx-auto p-8">
                <!-- Ici vous pouvez mettre votre image -->
                <div class="mb-6">
                    <img src="{{ asset('images/logo-mccat-300x300.jpg') }}" alt="SIGR-ITS Logo"
                        class="mx-auto h-24 opacity-90">
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Bienvenue sur SIGR-ITS</h2>
                <p class="text-gray-600">Plateforme de Gestion des Recommandations ITS</p>
            </div>
        </div>
    </div>
</body>

</html>