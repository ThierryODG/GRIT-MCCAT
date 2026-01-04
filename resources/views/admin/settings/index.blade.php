@extends('layouts.app')

@section('title', 'Paramètres Système')

@section('content')
    <div class="space-y-6">
        {{-- En-tête --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Paramètres Système</h1>
                <p class="text-gray-600">Gestion de la configuration et maintenance</p>
            </div>
        </div>

        {{-- Alertes --}}
        @if(session('success'))
            <div class="p-4 border border-green-200 rounded-md bg-green-50">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="text-green-400 fas fa-check-circle"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 border border-red-200 rounded-md bg-red-50">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="text-red-400 fas fa-exclamation-circle"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Paramètres Application --}}
            <div class="p-6 bg-white rounded-lg shadow">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Paramètres Application</h3>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700">Nom de
                                l'application</label>
                            <input type="text" name="app_name" id="app_name"
                                value="{{ old('app_name', $appSettings['app_name'] ?? '') }}"
                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="app_env" class="block text-sm font-medium text-gray-700">Environnement</label>
                            <select name="app_env" id="app_env"
                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="local" {{ ($appSettings['app_env'] ?? '') == 'local' ? 'selected' : '' }}>Local
                                </option>
                                <option value="staging" {{ ($appSettings['app_env'] ?? '') == 'staging' ? 'selected' : '' }}>
                                    Staging</option>
                                <option value="production" {{ ($appSettings['app_env'] ?? '') == 'production' ? 'selected' : '' }}>Production</option>
                            </select>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="app_debug" id="app_debug" value="1" {{ ($appSettings['app_debug'] ?? false) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="app_debug" class="ml-2 text-sm text-gray-700">Mode Debug</label>
                        </div>

                        <div>
                            <label for="email_from_address" class="block text-sm font-medium text-gray-700">Email
                                expéditeur</label>
                            <input type="email" name="email_from_address" id="email_from_address"
                                value="{{ old('email_from_address', $appSettings['email_from_address'] ?? '') }}"
                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="email_from_name" class="block text-sm font-medium text-gray-700">Nom
                                expéditeur</label>
                            <input type="text" name="email_from_name" id="email_from_name"
                                value="{{ old('email_from_name', $appSettings['email_from_name'] ?? '') }}"
                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Mettre à jour les paramètres
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Informations Système --}}
            <div class="space-y-6">
                {{-- Paramètres Métier --}}
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Paramètres Métier (Règles de Gestion)</h3>
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="setting_type" value="business">

                        <div class="space-y-4">
                            <div>
                                <label for="default_deadline_months" class="block text-sm font-medium text-gray-700">Délai
                                    par défaut Recommandation (Mois)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="default_deadline_months" id="default_deadline_months"
                                        value="{{ old('default_deadline_months', $businessSettings['default_deadline_months'] ?? 3) }}"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-12">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">mois</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="alert_deadline_1_days" class="block text-sm font-medium text-gray-700">Premier
                                    rappel avant échéance</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="alert_deadline_1_days" id="alert_deadline_1_days"
                                        value="{{ old('alert_deadline_1_days', $businessSettings['alert_deadline_1_days'] ?? 7) }}"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-12">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">jours</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="alert_deadline_2_days" class="block text-sm font-medium text-gray-700">Deuxième
                                    rappel avant échéance</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="alert_deadline_2_days" id="alert_deadline_2_days"
                                        value="{{ old('alert_deadline_2_days', $businessSettings['alert_deadline_2_days'] ?? 3) }}"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-12">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">jours</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Doit être inférieur au premier rappel.</p>
                            </div>

                            <div class="pt-4">
                                <button type="submit"
                                    class="px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <i class="fas fa-save mr-2"></i> Enregistrer Paramètres Métier
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Maintenance --}}
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Maintenance</h3>
                    <div class="space-y-3">
                        <form action="{{ route('admin.settings.clearCache') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 text-white bg-yellow-600 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                <i class="mr-2 fas fa-broom"></i>Vider tous les caches
                            </button>
                        </form>

                        <form action="{{ route('admin.settings.reset') }}" method="POST" class="block">
                            @csrf
                            <div class="flex space-x-2">
                                <select name="reset_type"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="cache">Vider le cache</option>
                                    <option value="logs">Vider les logs</option>
                                    <option value="sessions">Vider les sessions</option>
                                    <option value="statistics">Réinitialiser statistiques</option>
                                </select>
                                <button type="submit"
                                    class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Exécuter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Informations Techniques --}}
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Informations Système</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">PHP Version:</span>
                            <span class="font-medium">{{ $systemInfo['php_version'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Laravel Version:</span>
                            <span class="font-medium">{{ $systemInfo['laravel_version'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Environnement:</span>
                            <span class="font-medium">{{ $systemInfo['app_env'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Timezone:</span>
                            <span class="font-medium">{{ $systemInfo['timezone'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Upload Max Size:</span>
                            <span class="font-medium">{{ $systemInfo['upload_max_size'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Memory Limit:</span>
                            <span class="font-medium">{{ $systemInfo['memory_limit'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection