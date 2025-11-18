@extends('layouts.app')

@section('title', 'Détails - ' . $recommandation->reference)

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center mb-2 space-x-3">
                <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-800 bg-blue-100 rounded-full">
                    {{ $recommandation->reference }}
                </span>
                <h1 class="text-2xl font-bold text-gray-900">{{ $recommandation->titre }}</h1>
            </div>
            <p class="text-gray-600">Détails complets de la recommandation et suivi</p>
        </div>

        <div class="flex space-x-3">
            <a href="{{ route('responsable.suivi.index') }}"
               class="px-4 py-2 text-white transition-colors bg-gray-500 rounded-lg hover:bg-gray-600">
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Colonne principale -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Description -->
            <div class="p-6 bg-white border border-gray-200 rounded-xl">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Description</h2>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $recommandation->description }}</p>
            </div>

            <!-- Plan d'action -->
            @if($recommandation->planAction)
            <div class="p-6 bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Plan d'Action</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($recommandation->planAction->statut_validation === 'valide_ig') bg-green-100 text-green-800
                        @elseif($recommandation->planAction->statut_validation === 'valide_responsable') bg-blue-100 text-blue-800
                        @elseif($recommandation->planAction->statut_validation === 'en_attente_responsable') bg-yellow-100 text-yellow-800
                        @elseif($recommandation->planAction->statut_validation === 'rejete_responsable') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $recommandation->planAction->statut_validation_label }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Actions</label>
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $recommandation->planAction->action ?? 'Non spécifié' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Indicateurs</label>
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $recommandation->indicateurs ?? 'Non spécifié' }}</p>
                        </div>
                    </div>

                    <!-- Avancement -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Avancement</label>
                        <div class="flex items-center space-x-3">
                            <div class="w-full h-3 bg-gray-200 rounded-full">
                                <div class="h-3 transition-all duration-300 bg-green-600 rounded-full"
                                     style="width: {{ $recommandation->planAction->pourcentage_avancement }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-700 whitespace-nowrap">
                                {{ $recommandation->planAction->pourcentage_avancement }}%
                            </span>
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            Statut: {{ $recommandation->planAction->statut_execution_label }}
                        </div>
                    </div>

                    @if($recommandation->planAction->commentaire_avancement)
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Commentaires du point focal</label>
                        <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                            <p class="text-blue-700 whitespace-pre-wrap">{{ $recommandation->planAction->commentaire_avancement }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="p-6 bg-white border border-gray-200 rounded-xl">
                <div class="py-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-4 text-gray-500">Aucun plan d'action créé pour le moment</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Informations générales -->
            <div class="p-6 bg-white border border-gray-200 rounded-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Informations Générales</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Priorité</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($recommandation->priorite === 'haute') bg-red-100 text-red-800
                            @elseif($recommandation->priorite === 'moyenne') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($recommandation->priorite) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-700">Date limite</p>
                        <p class="text-gray-900">{{ $recommandation->date_limite->format('d/m/Y') }}</p>
                        @if($recommandation->date_limite < now() && !in_array($recommandation->statut, ['cloturee', 'execution_terminee']))
                        <p class="mt-1 text-xs font-medium text-red-600">⚠️ En retard</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-700">Statut</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if(in_array($recommandation->statut, ['cloturee', 'execution_terminee', 'plan_valide_ig'])) bg-green-100 text-green-800
                            @elseif(in_array($recommandation->statut, ['en_execution', 'plan_valide_responsable'])) bg-blue-100 text-blue-800
                            @elseif(in_array($recommandation->statut, ['plan_en_redaction', 'plan_soumis_responsable'])) bg-yellow-100 text-yellow-800
                            @elseif($recommandation->statut === 'point_focal_assigne') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $recommandation->statut_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Point Focal -->
            <div class="p-6 bg-white border border-gray-200 rounded-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Point Focal</h3>

                @if($recommandation->pointFocal)
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                            <span class="font-semibold text-blue-600">
                                {{ substr($recommandation->pointFocal->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $recommandation->pointFocal->name }}</p>
                            <p class="text-sm text-gray-500">{{ $recommandation->pointFocal->email }}</p>
                        </div>
                    </div>

                    @if($recommandation->pointFocal->telephone)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $recommandation->pointFocal->telephone }}
                    </div>
                    @endif
                </div>
                @else
                <p class="text-sm text-gray-500">Aucun point focal assigné</p>
                @endif
            </div>

            <!-- Inspecteur ITS -->
            <div class="p-6 bg-white border border-gray-200 rounded-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Inspecteur ITS</h3>

                <div class="space-y-2">
                    <p class="text-sm text-gray-900">{{ $recommandation->its->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">{{ $recommandation->its->email ?? '' }}</p>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="p-6 bg-white border border-gray-200 rounded-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Actions</h3>

                <div class="space-y-2">
                    @if(!$recommandation->pointFocal)
                    <a href="{{ route('responsable.points_focaux.index') }}"
                       class="block w-full px-4 py-2 text-sm text-center text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                        Assigner un Point Focal
                    </a>
                    @endif

                    @if($recommandation->planAction && $recommandation->planAction->statut_validation === 'en_attente_responsable')
                    <a href="{{ route('responsable.validation_plans.show', $recommandation->planAction) }}"
                       class="block w-full px-4 py-2 text-sm text-center text-white transition-colors bg-orange-600 rounded-lg hover:bg-orange-700">
                        Valider le Plan
                    </a>
                    @endif

                    @if($recommandation->planAction && $recommandation->planAction->statut_validation === 'valide_responsable')
                    <form action="{{ route('responsable.validation_plans.transmettre', $recommandation->planAction) }}" method="POST">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Transmettre ce plan à l\\'Inspecteur Général ?')"
                                class="w-full px-4 py-2 text-sm text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                            Transmettre à l'IG
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
