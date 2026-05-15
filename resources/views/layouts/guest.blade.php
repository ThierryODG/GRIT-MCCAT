<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SIGR-ITS' }}</title>

    <!-- Fonts: Inter for a modern look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
            background-color: #f9fafb;
        }

        .login-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            z-index: 10;
            background: white;
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.02);
        }

        .login-right {
            flex: 1.2;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .login-right {
                display: none;
            }
        }

        .glass-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(25, 25, 43, 0.85), rgba(72, 72, 80, 0.75));
            backdrop-filter: blur(1px);
        }

        .content-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            max-width: 420px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 20;
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-glow {
            filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.5));
        }
    </style>
</head>

<body class="antialiased text-gray-900">
    <div class="login-container">
        <!-- Section Gauche : Formulaire -->
        <div class="login-left">
            <div class="w-full max-w-sm px-4">
                {{ $slot }}
            </div>
        </div>

        <!-- Section Droite : Visuel Premium -->
        <div class="login-right" style="background-image: url('{{ asset('images/bg.jpg') }}');">
            <div class="glass-overlay"></div>

            <div class="content-card">
                <div class="mb-8">
                    <img src="{{ asset('images/logo-mccat-300x300.jpg') }}" alt="SIGR-ITS Logo"
                        class="mx-auto h-24 rounded-2xl logo-glow transition-transform duration-500 hover:scale-110">
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-4 tracking-tight">SIGR-ITS</h2>
                <div class="h-1 w-20 bg-gray-900 mx-auto mb-6 rounded-full"></div>
                <p class="text-gray-600 font-medium leading-relaxed">
                    Plateforme de Gestion des Recommandations <br>
                    <span class="text-gray-900 font-bold italic">Inspection Technique des Services</span>
                </p>
                <div class="mt-8 flex justify-center space-x-2">
                    <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                    <span class="h-2 w-2 rounded-full bg-gray-900"></span>
                    <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>