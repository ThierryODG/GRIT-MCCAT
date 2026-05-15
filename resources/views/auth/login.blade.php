<x-guest-layout>
    <div class="mb-10 text-center lg:text-left animate-in fade-in slide-in-from-bottom-4 duration-700">
        <div class="lg:hidden mb-6 flex justify-center">
            <img src="{{ asset('images/logo-mccat-300x300.jpg') }}" alt="SIGR-ITS" class="h-16 rounded-xl shadow-md">
        </div>
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">{{ __('Connexion') }}</h1>
        <p class="text-gray-500 mt-3 font-medium">{{ __('Accédez à votre espace de travail SIGR-ITS') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}"
        class="space-y-6 animate-in fade-in slide-in-from-bottom-8 duration-1000">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Adresse Email')"
                class="font-bold text-xs uppercase tracking-widest text-gray-600" />
            <div class="relative group">
                <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-gray-900 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    autocomplete="username" placeholder="votre@email.gouv"
                    class="block w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-300 text-gray-900 font-medium placeholder:text-gray-400" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Mot de Passe')"
                    class="font-bold text-xs uppercase tracking-widest text-gray-600" />
            </div>
            <div class="relative group">
                <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-gray-900 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    placeholder="••••••••"
                    class="block w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-300 text-gray-900 font-medium placeholder:text-gray-400" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between py-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox"
                    class="rounded-md border-gray-300 text-gray-900 focus:ring-gray-900 h-4 w-4 transition duration-150"
                    name="remember">
                <span
                    class="ms-2 text-sm text-gray-500 group-hover:text-gray-900 transition-colors font-medium">{{ __('Se souvenir de moi') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-gray-600 hover:text-gray-900 transition duration-150 decoration-gray-200 underline-offset-4 hover:underline"
                    href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit"
                class="w-full inline-flex items-center justify-center bg-gray-900 hover:bg-black text-white px-6 py-4 rounded-xl font-bold text-sm tracking-widest uppercase transition-all duration-200 transform hover:translate-y-[-2px] hover:shadow-xl active:scale-95">
                {{ __('Se connecter') }}
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </form>

    <div class="mt-12 pt-8 border-t border-gray-100 italic text-center text-xs text-gray-400 font-medium">
        &copy; {{ date('Y') }} Ministère de la Culture, des Arts et du Tourisme. <br> Tous droits réservés.
    </div>
</x-guest-layout>