<div class="text-center mb-8">
    <div
        class="logo-animated w-20 h-20 bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl mx-auto mb-4 flex items-center justify-center text-2xl font-bold text-white shadow-lg">
        G
    </div>
    <h1 class="text-3xl font-bold text-gray-800">SIGR-ITS</h1>
    <p class="text-gray-600 mt-2">{{ $subtitle ?? 'Plateforme de Gestion des Recommandations ITS' }}</p>
</div>

<style>
    .logo-animated {
        animation: bounce 2s ease-in-out infinite;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-10px) rotate(5deg);
        }
    }
</style>