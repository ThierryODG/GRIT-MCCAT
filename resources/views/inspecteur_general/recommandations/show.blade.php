@extends('layouts.app')

@section('title', 'Détails Recommandation - Inspecteur Général')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- En-tête -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Détails de la Recommandation</h2>
                        <p class="text-gray-600">Référence: {{ $recommandation->reference }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('inspecteur_general.validation.index') }}"
                           class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                            Retour
                        </a>
                        @if($recommandation->statut == 'en_attente_validation')
                        <form method="POST" action="{{ route('inspecteur_general.validation.valider', $recommandation) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                Valider
                            </button>
                        </form>
                        <form method="POST" action="{{ route('inspecteur_general.validation.rejeter', $recommandation) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                Rejeter
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <!-- Informations principales -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-3">Informations Générales</h3>
                        <div class="space-y-2">
                            <p><strong>Référence:</strong> {{ $recommandation->reference }}</p>
                            <p><strong>Priorité:</strong>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $recommandation->priorite == 'haute' ? 'bg-red-100 text-red-800' :
                                       ($recommandation->priorite == 'moyenne' ? 'bg-yellow-100 text-yellow-800' :
                                       'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($recommandation->priorite) }}
                                </span>
                            </p>
                            <p><strong>Date création:</strong> {{ $recommandation->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Créée par:</strong> {{ $recommandation->its->name ?? 'Système' }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-3">Statut de Validation</h3>
                        <div class="space-y-2">
                            <p>
                                <strong>Statut:</strong>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $recommandation->statut == 'validee' ? 'bg-green-100 text-green-800' :
                                       ($recommandation->statut == 'rejetee' ? 'bg-red-100 text-red-800' :
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ $recommandation->statut == 'validee' ? 'Validée' :
                                      ($recommandation->statut == 'rejetee' ? 'Rejetée' : 'En attente') }}
                                </span>
                            </p>
                            @if($recommandation->date_validation)
                            <p><strong>Date validation:</strong> {{ $recommandation->date_validation->format('d/m/Y H:i') }}</p>
                            @endif
                            @if($recommandation->valide_par)
                            <p><strong>Validé par:</strong> {{ $recommandation->validePar->name ?? 'N/A' }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description complète -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Description Complète</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700 whitespace-pre-line">{{ $recommandation->description }}</p>
                    </div>
                </div>

                <!-- Commentaire de validation -->
                @if($recommandation->commentaire_validation)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Commentaire de Validation</h3>
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <p class="text-gray-700 whitespace-pre-line">{{ $recommandation->commentaire_validation }}</p>
                    </div>
                </div>
                @endif

                <!-- Actions de validation (si en attente) -->
                @if($recommandation->statut == 'en_attente_validation')
                <div class="mt-8 p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-yellow-800">Actions de Validation</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <form method="POST" action="{{ route('inspecteur_general.validation.valider', $recommandation) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire (optionnel)</label>
                                <textarea name="commentaire" rows="3" class="w-full rounded-md border-gray-300 shadow-sm"
                                          placeholder="Commentaire pour la validation..."></textarea>
                            </div>
                            <button type="submit"
                                    class="w-full bg-green-600 text-white px-4 py-3 rounded-md hover:bg-green-700 font-semibold">
                                ✅ Valider la Recommandation
                            </button>
                        </form>

                        <form method="POST" action="{{ route('inspecteur_general.validation.rejeter', $recommandation) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire (optionnel)</label>
                                <textarea name="commentaire" rows="3" class="w-full rounded-md border-gray-300 shadow-sm"
                                          placeholder="Raison du rejet..."></textarea>
                            </div>
                            <button type="submit"
                                    class="w-full bg-red-600 text-white px-4 py-3 rounded-md hover:bg-red-700 font-semibold">
                                ❌ Rejeter la Recommandation
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
