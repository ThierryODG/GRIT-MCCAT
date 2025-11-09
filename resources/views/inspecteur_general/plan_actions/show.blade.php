@extends('layouts.app')

@section('title', 'Détails Plan d\'Action - Inspecteur Général')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- En-tête -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Plan d'Action: {{ $planAction->titre }}</h2>
                        <p class="text-gray-600">Référence: {{ $planAction->reference }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('inspecteur.plan_actions.index') }}"
                           class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                            Retour
                        </a>
                        @if($planAction->statut_validation == 'en_attente')
                        <button onclick="openValidationModal()"
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                            Valider/Rejeter
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Informations générales -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-3">Informations Générales</h3>
                        <div class="space-y-2">
                            <p><strong>Point Focal:</strong> {{ $planAction->pointFocal->name ?? 'N/A' }}</p>
                            <p><strong>Responsable:</strong> {{ $planAction->responsable->name ?? 'N/A' }}</p>
                            <p><strong>Date de création:</strong> {{ $planAction->created_at->format('d/m/Y') }}</p>
                            <p><strong>Date limite:</strong> {{ $planAction->date_limite?->format('d/m/Y') ?? 'Non définie' }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-3">Statut</h3>
                        <div class="space-y-2">
                            <p>
                                <strong>Validation:</strong>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $planAction->statut_validation == 'valide' ? 'bg-green-100 text-green-800' :
                                       ($planAction->statut_validation == 'rejete' ? 'bg-red-100 text-red-800' :
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ $planAction->statut_validation == 'valide' ? 'Validé' :
                                      ($planAction->statut_validation == 'rejete' ? 'Rejeté' : 'En attente') }}
                                </span>
                            </p>
                            <p><strong>Avancement global:</strong> {{ $planAction->taux_avancement ?? 0 }}%</p>
                            @if($planAction->date_validation)
                            <p><strong>Date validation:</strong> {{ $planAction->date_validation->format('d/m/Y') }}</p>
                            @endif
                            @if($planAction->commentaire_validation)
                            <p><strong>Commentaire:</strong> {{ $planAction->commentaire_validation }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Description</h3>
                    <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">
                        {{ $planAction->description ?? 'Aucune description disponible.' }}
                    </p>
                </div>

                <!-- Activités -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Activités du Plan</h3>
                    @if($planAction->activites && $planAction->activites->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Activité</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Responsable</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date début</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date fin</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avancement</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($planAction->activites as $activite)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $activite->nom }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $activite->responsable }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $activite->date_debut?->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $activite->date_fin?->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $activite->taux_avancement }}%</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $activite->statut == 'termine' ? 'bg-green-100 text-green-800' :
                                               ($activite->statut == 'en_retard' ? 'bg-red-100 text-red-800' :
                                               'bg-blue-100 text-blue-800') }}">
                                            {{ $activite->statut == 'termine' ? 'Terminé' :
                                              ($activite->statut == 'en_retard' ? 'En retard' : 'En cours') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-4">Aucune activité définie pour ce plan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de validation -->
@if($planAction->statut_validation == 'en_attente')
<div id="validationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900">Validation du Plan d'Action</h3>
            <form method="POST" action="{{ route('inspecteur.plan_actions.validate', $planAction) }}">
                @csrf
                <div class="mt-2 px-7 py-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire</label>
                    <textarea name="commentaire" rows="3" class="w-full rounded-md border-gray-300 shadow-sm"
                              placeholder="Commentaire optionnel..."></textarea>
                </div>
                <div class="flex justify-center gap-4 mt-4">
                    <button type="button" onclick="closeValidationModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" name="action" value="valider"
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Valider
                    </button>
                    <button type="submit" name="action" value="rejeter"
                            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        Rejeter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openValidationModal() {
    document.getElementById('validationModal').classList.remove('hidden');
}

function closeValidationModal() {
    document.getElementById('validationModal').classList.add('hidden');
}
</script>
@endif
@endsection
