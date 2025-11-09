<x-guest-layout>
    <!-- En-tête sobre -->
    <div class="text-center mb-8">
        <!-- Logo - Remplacez par votre image -->
        <div class="mb-6">
            <img src="{{ asset('images/logo-mccat-300x300.jpg') }}" alt="GRIT" class="mx-auto h-16">
        </div>
        <h1 class="text-2xl font-semibold text-gray-800">Connexion</h1>
        <p class="text-gray-600 text-sm mt-2">Accédez à votre compte</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Adresse Email')" class="text-sm font-medium text-gray-700" />
            <x-text-input
                id="email"
                class="block mt-2 w-full border-gray-300 focus:border-gray-400 focus:ring-gray-400 transition-colors duration-200"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="votre@email.gouv"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mot de Passe')" class="text-sm font-medium text-gray-700" />
            <x-text-input
                id="password"
                class="block mt-2 w-full border-gray-300 focus:border-gray-400 focus:ring-gray-400 transition-colors duration-200"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Votre mot de passe"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-gray-600 focus:ring-gray-500 transition duration-150" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 hover:text-gray-800 transition duration-150 font-medium" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <x-primary-button class="w-full justify-center bg-gray-800 hover:bg-gray-900 focus:bg-gray-900 active:bg-gray-950 text-white font-medium py-3 transition-colors duration-200">
                {{ __('Se connecter') }}
            </x-primary-button>
        </div>
    </form>

    <style>
        /* Animations très discrètes */
        .form-input {
            transition: all 0.2s ease;
        }

        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
        }

        /* Effet de survol très subtil */
        .btn-login:hover {
            transform: translateY(-1px);
        }
    </style>
</x-guest-layout>
