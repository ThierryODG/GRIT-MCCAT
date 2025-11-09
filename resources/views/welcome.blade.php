<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GRIT - Suivi des Recommandations ITS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css'])

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 to-purple-800">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="max-w-6xl w-full">

            <!-- Header -->
            <div class="text-center mb-16">
                <div class="flex justify-center mb-6">
                    <div class="bg-white p-5 rounded-2xl shadow-2xl">
                        <i class="fas fa-chart-line text-5xl text-blue-600"></i>
                    </div>
                </div>
                <h1 class="text-6xl font-bold text-white mb-4">
                    GRIT
                </h1>
                <p class="text-2xl text-blue-100 mb-3">
                    Plateforme de Suivi des Recommandations ITS
                </p>
                <p class="text-blue-200 text-lg">
                    Ministère de la Communication, de la Culture, des Arts et du Tourisme
                </p>
            </div>

            <!-- Rôles Section -->
            <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-10 mb-12 border border-white/20">
                <h2 class="text-4xl font-bold text-white text-center mb-12">
                    Fonctionnalités par Rôle
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Admin Card -->
                    @include('welcome.role-card', [
                        'icon' => 'cog',
                        'color' => 'blue',
                        'title' => 'Administrateur',
                        'features' => ['Gestion des utilisateurs', 'Configuration du système', 'Supervision globale', 'Génération de rapports']
                    ])

                    <!-- ITS Card -->
                    @include('welcome.role-card', [
                        'icon' => 'search',
                        'color' => 'green',
                        'title' => 'ITS',
                        'features' => ['Saisie des recommandations', 'Consultation du statut', 'Modification des recommandations', 'Suivi des actions']
                    ])

                    <!-- Inspecteur Card -->
                    @include('welcome.role-card', [
                        'icon' => 'check-circle',
                        'color' => 'yellow',
                        'title' => 'Inspecteur Général',
                        'features' => ['Validation des recommandations', 'Attribution aux points focaux', 'Supervision du processus']
                    ])
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center">
                <p class="text-white text-xl mb-8 font-semibold">
                    Accédez à votre espace de travail
                </p>
                <a href="{{ route('login') }}"
                   class="bg-white text-blue-700 px-10 py-4 rounded-2xl font-bold text-lg hover:bg-gray-100 transition-all duration-300 inline-flex items-center shadow-2xl hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-3"></i>
                    Se Connecter
                </a>

                <p class="text-blue-200 mt-8 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Seul l'administrateur peut créer des comptes et attribuer les rôles
                </p>
            </div>

        </div>
    </div>
</body>
</html>
