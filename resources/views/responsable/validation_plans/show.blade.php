@extends('layouts.app')

@section('title', 'Examen du Plan d\'Action - ' . $planAction->recommandation->reference)

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center mb-2 space-x-3">
                <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-800 bg-blue-100 rounded-full">
                    {{ $planAction->recommandation->reference }}
                </span>
                <h1 class="text-2xl font-bold text-gray-900">{{ $planAction->recommandation->titre }}</h1>
            </div>
            <p class="text-gray-600">Examinez le plan d'action proposé par le point focal</p>
        </div>

        <div class="flex space-x-3">
            <a href="{{ route('responsable.validation_plans.index') }}"
               class="px-4 py-2 text-white transition-colors bg-gray-500 rounded-lg hover:bg-gray-600">
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Colonne principale -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Plan d'action -->
            <div class="p-6 bg-white border border-gray-200 rounded-xl">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Plan d'Action Proposé</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Actions détaillées</label>
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $planAction->action ?? 'Non spécifié' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Indicateurs de suivi</label>
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $planAction->recommandation->indicateurs ?? 'Non spécifié' }}</p>
                        </div>
                    </div>

                    @if($planAction->commentaire_avancement)
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Commentaires du point focal</label>
                        <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                            <p class="text-blue-700 whitespace-pre-wrap">{{ $planAction->commentaire_avancement }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="p-6 bg-white border border-gray-200 rounded-xl">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Informations Complémentaires</h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Incidence financière</label>
                        <p class="mt-1 text-gray-900">
                            @if($planAction->recommandation->incidence_financiere)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($planAction->recommandation->incidence_financiere === 'eleve') bg-red-100 text-red-800
                                @elseif($planAction->recommandation->incidence_financiere === 'moyen') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($planAction->recommandation->incidence_financiere) }}
                            </span>
                            @else
                            <span class="text-gray-500">Non spécifié</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Délai estimé</label>
                        <p class="mt-1 text-gray-900">
                            {{ $planAction->recommandation->delai_mois ? $planAction->recommandation->delai_mois . ' mois' : 'Non spécifié' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de début prévue</label>
                        <p class="mt-1 text-gray-900">
                            {{ $planAction->recommandation->date_debut_prevue ? $planAction->recommandation->date_debut_prevue->format('d/m/Y') : 'Non spécifié' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de fin prévue</label>
                        <p class="mt-1 text-gray-900">
                            {{ $planAction->recommandation->date_fin_prevue ? $planAction->recommandation->date_fin_prevue->format('d/m/Y') : 'Non spécifié' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Actions de validation -->
            <div class="p-6 bg-white border border-gray-200 rounded-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Validation du Plan</h3>

                <div class="space-y-4">
                    <!-- Validation -->
                    <form action="{{ route('responsable.validation_plans.valider', $planAction) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Commentaire (optionnel)</label>
                            <textarea name="commentaire" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Commentaires sur la validation..."></textarea>
                        </div>
                        <button type="submit"
                                onclick="return confirm('Êtes-vous sûr de vouloir valider ce plan d\'action ?')"
                                class="w-full px-4 py-2 text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                            ✅ Valider le plan
                        </button>
                    </form>

                    <!-- Rejet -->
                    <form action="{{ route('responsable.validation_plans.rejeter', $planAction) }}" method="POST" id="rejectForm">
                        @csrf
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Motif du rejet</label>
                            <textarea name="motif" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Expliquez les raisons du rejet..."></textarea>
                        </div>
                        <button type="submit"
                                onclick="return confirm('Êtes-vous sûr de vouloir rejeter ce plan d\'action ?')"
                                class="w-full px-4 py-2 text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700">
                            ❌ Rejeter le plan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Informations de contact -->
            <div class="p-6 bg-white border border-gray-200 rounded-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Point Focal</h3>

                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                            <span class="font-semibold text-blue-600">
                                {{ substr($planAction->pointFocal->name ?? 'PF', 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $planAction->pointFocal->name ?? 'Non assigné' }}</p>
                            <p class="text-sm text-gray-500">{{ $planAction->pointFocal->email ?? '' }}</p>
                        </div>
                    </div>

                    @if($planAction->pointFocal && $planAction->pointFocal->telephone)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $planAction->pointFocal->telephone }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recommandation originale -->
            <div class="p-6 bg-white border border-gray-200 rounded-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Recommandation Originale</h3>

                <div class="space-y-2 text-sm">
                    <div>
                        <p class="font-medium text-gray-700">Inspecteur ITS</p>
                        <p class="text-gray-600">{{ $planAction->recommandation->its->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="font-medium text-gray-700">Date limite</p>
                        <p class="text-gray-600">{{ $planAction->recommandation->date_limite->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <p class="font-medium text-gray-700">Priorité</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($planAction->recommandation->priorite === 'haute') bg-red-100 text-red-800
                            @elseif($planAction->recommandation->priorite === 'moyenne') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($planAction->recommandation->priorite) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
