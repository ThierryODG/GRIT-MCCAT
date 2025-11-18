@extends('layouts.app')

@section('title', 'Plans d\'Action - Inspecteur Général')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Liste des Plans d'Action</h2>
                </div>

                <!-- Filtres -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Statut</label>
                            <select name="statut" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Tous</option>
                                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                                <option value="rejete" {{ request('statut') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de début</label>
                            <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de fin</label>
                            <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                Filtrer
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tableau des plans d'action -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Référence
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Titre
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Point Focal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date création
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($planActions as $plan)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $plan->reference }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ Str::limit($plan->titre, 50) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $plan->pointFocal->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $plan->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $status = $plan->statut_validation;
                                        $statusLabel = $plan->statut_validation_label;
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                        if (str_contains($status, 'valide')) {
                                            $statusClass = 'bg-green-100 text-green-800';
                                        } elseif (str_contains($status, 'rejete')) {
                                            $statusClass = 'bg-red-100 text-red-800';
                                        }
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('inspecteur_general.plan_actions.show', $plan) }}"
                                       class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                        @if($plan->statut_validation == 'en_attente_ig')
                                        <button onclick="openValidationModal({{ $plan->id }})"
                                            class="text-green-600 hover:text-green-900">Valider</button>
                                        @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aucun plan d'action trouvé.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $planActions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de validation -->
<div id="validationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900">Validation du Plan d'Action</h3>
            <form id="validationForm" method="POST">
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
function openValidationModal(planId) {
    const form = document.getElementById('validationForm');
    form.action = `/inspecteur_general/plan-actions/${planId}/validate`;
    document.getElementById('validationModal').classList.remove('hidden');
}

function closeValidationModal() {
    document.getElementById('validationModal').classList.add('hidden');
}
</script>
@endsection
