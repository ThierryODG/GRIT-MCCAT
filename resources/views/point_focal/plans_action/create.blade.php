{{-- resources/views/point-focal/plans-action/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Créer un plan d\'action - ' . $recommandation->reference)

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- Navigation -->
    <div class="mb-6">
        <a href="{{ route('point_focal.recommandations.show', $recommandation) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="mr-2 fas fa-arrow-left"></i>
            Retour à la recommandation
        </a>
    </div>

    <!-- En-tête -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Créer un plan d'action</h1>
        <p class="mt-1 text-gray-600">Pour la recommandation : <strong>{{ $recommandation->reference }}</strong></p>
        <p class="text-gray-500">{{ $recommandation->titre }}</p>
    </div>

    <div class="max-w-4xl">
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <form method="POST" action="{{ route('point_focal.plans_action.store', $recommandation) }}">
                @csrf

                <!-- Informations de la recommandation -->
                <div class="p-4 mb-6 bg-blue-50 border border-blue-200 rounded-lg">
                    <h3 class="font-semibold text-blue-900">Contexte</h3>
                    <div class="mt-2 grid grid-cols-1 gap-2 text-sm text-blue-800 md:grid-cols-3">
                        <div>
                            <span class="font-medium">Indicateur :</span> {{ $recommandation->indicateurs }}
                        </div>
                        <div>
                            <span class="font-medium">Incidence :</span> {{ ucfirst($recommandation->incidence_financiere ?? 'Non définie') }}
                        </div>
                        <div>
                            <span class="font-medium">Délai :</span> {{ $recommandation->delai_mois ?? 'Non défini' }} mois
                        </div>
                    </div>
                </div>

                <!-- Action/Mesure à prendre -->
                <div class="mb-6">
                    <label for="action" class="block mb-2 text-sm font-medium text-gray-700">
                        Action ou mesure à prendre <span class="text-red-500">*</span>
                    </label>
                    <p class="mb-3 text-sm text-gray-600">
                        Décrivez une action spécifique qui contribuera à atteindre l'indicateur défini plus haut.
                        Vous pouvez créer plusieurs actions/mesures pour la même recommandation.
                    </p>
                    <textarea name="action" id="action" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                        @error('action') border-red-500 @enderror"
                        placeholder="Ex: Former le personnel en audit interne, documenter le processus, mettre en place le suivi..."
                        required>{{ old('action') }}</textarea>
                    @error('action')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end pt-6 space-x-4 border-t border-gray-200">
                    <a href="{{ route('point_focal.recommandations.show', $recommandation) }}"
                       class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit"
                            class="flex items-center px-6 py-3 text-white bg-green-600 rounded-lg hover:bg-green-700">
                        <i class="mr-2 fas fa-plus"></i>
                        Créer cette action
                    </button>
                </div>
            </form>
        </div>

        <!-- Aide -->
        <div class="p-6 mt-6 bg-amber-50 border border-amber-200 rounded-lg">
            <h3 class="font-semibold text-amber-900">💡 Conseil</h3>
            <ul class="mt-2 ml-4 text-sm text-amber-800 list-disc">
                <li>Une action = une mesure concrète et précise</li>
                <li>Vous pouvez en créer plusieurs jusqu'à couvrir l'indicateur</li>
                <li>Après création, vous pourrez les valider/modifier</li>
            </ul>
        </div>
    </div>
</div>
@endsection
