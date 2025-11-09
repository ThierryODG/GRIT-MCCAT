@extends('layouts.app')

@section('title', 'Détails Recommandation - ' . $recommandation->reference)

@section('content')
<div class="container p-6 mx-auto">
    <!-- En-tête avec navigation -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('inspecteur_general.validation.index') }}"
               class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour à la liste
            </a>
            <div class="text-sm text-gray-500">
                Recommandation • {{ $recommandation->reference }}
            </div>
        </div>

        <!-- Badge statut -->
        <div class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg">
            {{ $recommandation->statut === 'soumise_ig' ? 'En attente de validation' :
               ($recommandation->statut === 'validee_ig' ? 'Validée' : 'Rejetée') }}
        </div>
    </div>

    <!-- Grid principal -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Colonne principale -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Carte informations générales -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-xl">
                    <h2 class="text-lg font-semibold text-gray-800">Informations Générales</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-600">Référence</label>
                                <div class="flex items-center text-lg font-semibold text-gray-900">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ $recommandation->reference }}
                                </div>
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-600">Priorité</label>
                                @php
                                    $priorityConfig = [
                                        'haute' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'dot' => 'bg-red-400'],
                                        'moyenne' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'dot' => 'bg-yellow-400'],
                                        'basse' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'dot' => 'bg-green-400'],
                                    ];
                                    $config = $priorityConfig[$recommandation->priorite] ?? $priorityConfig['basse'];
                                @endphp
                                <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                    <span class="w-3 h-3 rounded-full {{ $config['dot'] }} mr-2"></span>
                                    {{ ucfirst($recommandation->priorite) }}
                                </span>
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-600">Date de création</label>
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $recommandation->created_at->format('d/m/Y à H:i') }}
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-600">Structure concernée</label>
                                <div class="flex items-center text-gray-900">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ $recommandation->structure->nom ?? 'Structure non spécifiée' }}
                                </div>
                            </div>

                            @if($recommandation->service_concerne)
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-600">Service concerné</label>
                                <div class="px-3 py-2 text-gray-900 border border-gray-200 rounded-lg bg-gray-50">
                                    {{ $recommandation->service_concerne }}
                                </div>
                            </div>
                            @endif

                            @if($recommandation->date_limite)
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-600">Date limite</label>
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $recommandation->date_limite->format('d/m/Y') }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte contenu de la recommandation -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-xl">
                    <h2 class="text-lg font-semibold text-gray-800">Contenu de la Recommandation</h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Titre -->
                    <div>
                        <label class="block mb-3 text-sm font-semibold text-gray-700">Titre</label>
                        <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                            <p class="text-lg leading-relaxed text-gray-900">{{ $recommandation->titre }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block mb-3 text-sm font-semibold text-gray-700">Description détaillée</label>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 min-h-[120px]">
                            <p class="leading-relaxed text-gray-900 whitespace-pre-line">{{ $recommandation->description }}</p>
                        </div>
                    </div>

                    <!-- Contexte -->
                    @if($recommandation->contexte)
                    <div>
                        <label class="block mb-3 text-sm font-semibold text-gray-700">Contexte</label>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 min-h-[100px]">
                            <p class="leading-relaxed text-gray-900 whitespace-pre-line">{{ $recommandation->contexte }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Objectif -->
                    @if($recommandation->objectif)
                    <div>
                        <label class="block mb-3 text-sm font-semibold text-gray-700">Objectif visé</label>
                        <div class="p-4 bg-green-50 rounded-lg border border-green-200 min-h-[100px]">
                            <p class="leading-relaxed text-gray-900 whitespace-pre-line">{{ $recommandation->objectif }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Carte inspecteur ITS -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50 rounded-t-xl">
                    <h2 class="text-lg font-semibold text-gray-800">Inspecteur ITS</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-purple-100 rounded-xl">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">
                                {{ $recommandation->its->name ?? 'Non spécifié' }}
                            </p>
                            <p class="text-sm text-gray-500 truncate">
                                {{ $recommandation->its->email ?? 'Email non disponible' }}
                            </p>
                            @if($recommandation->its->telephone)
                            <p class="text-sm text-gray-500">
                                📞 {{ $recommandation->its->telephone }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte actions -->
            @if($recommandation->statut === 'soumise_ig')
            <div class="bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50 rounded-t-xl">
                    <h2 class="text-lg font-semibold text-gray-800">Actions de Validation</h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Validation -->
                    <form action="{{ route('inspecteur_general.validation.valider', $recommandation) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Commentaire (optionnel)</label>
                            <textarea name="commentaire" rows="3"
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                      placeholder="Ajouter un commentaire pour la structure..."></textarea>
                        </div>
                        <button type="submit"
                                class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition-colors duration-150 bg-green-600 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                onclick="return confirm('Confirmer la validation de cette recommandation ?')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Valider la recommandation
                        </button>
                    </form>

                    <!-- Rejet -->
                    <form action="{{ route('inspecteur_general.validation.rejeter', $recommandation) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-red-700">Motif du rejet <span class="text-red-500">*</span></label>
                            <textarea name="motif" rows="4" required
                                      class="w-full px-3 py-2 text-sm border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                      placeholder="Expliquez de manière détaillée les raisons du rejet..."></textarea>
                        </div>
                        <button type="submit"
                                class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition-colors duration-150 bg-red-600 rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                onclick="return confirm('Confirmer le rejet de cette recommandation ?')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Rejeter la recommandation
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Historique de validation -->
            @if($recommandation->statut !== 'soumise_ig')
            <div class="bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-blue-gray-50 rounded-t-xl">
                    <h2 class="text-lg font-semibold text-gray-800">Décision de Validation</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Statut:</span>
                            <span class="px-3 py-1 text-sm font-medium text-white rounded-lg
                                {{ $recommandation->statut === 'validee_ig' ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $recommandation->statut === 'validee_ig' ? 'Validée' : 'Rejetée' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Date:</span>
                            <span class="text-sm text-gray-900">{{ $recommandation->date_validation_ig->format('d/m/Y H:i') }}</span>
                        </div>

                        @if($recommandation->motif_rejet_ig)
                        <div>
                            <span class="block mb-2 text-sm font-medium text-gray-600">Motif du rejet:</span>
                            <div class="p-3 border border-red-200 rounded-lg bg-red-50">
                                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $recommandation->motif_rejet_ig }}</p>
                            </div>
                        </div>
                        @endif

                        @if($recommandation->commentaire_ig)
                        <div>
                            <span class="block mb-2 text-sm font-medium text-gray-600">Commentaire:</span>
                            <div class="p-3 border border-blue-200 rounded-lg bg-blue-50">
                                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $recommandation->commentaire_ig }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
