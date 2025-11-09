@extends('layouts.app')

@section('title', 'ITS')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span class="ml-1 text-gray-700">Gestion de Clôture</span>
        </div>
    </li>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Gestion de Clôture des Recommandations</h2>
                </div>

                <!-- Informations -->
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-blue-700 text-sm">
                            Seules les recommandations validées peuvent être clôturées. La clôture indique que la recommandation a été complètement traitée.
                        </p>
                    </div>
                </div>

                <!-- Tableau des recommandations à clôturer -->
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
                                    Priorité
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date validation
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recommandations as $recommandation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $recommandation->reference }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ Str::limit($recommandation->titre, 50) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $recommandation->priorite == 'haute' ? 'bg-red-100 text-red-800' :
                                           ($recommandation->priorite == 'moyenne' ? 'bg-yellow-100 text-yellow-800' :
                                           'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($recommandation->priorite) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $recommandation->date_validation?->format('d/m/Y') ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('its.recommandations.show', $recommandation) }}"
                                       class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                    <button onclick="openClotureModal({{ $recommandation->id }})"
                                            class="text-purple-600 hover:text-purple-900">Clôturer</button>
                                </td>
                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            @if($recommandations->total() > 0)
                                                Aucune recommandation validée disponible pour la clôture.
                                            @else
                                                Aucune recommandation validée trouvée.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $recommandations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de clôture -->
<div id="clotureModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900">Clôturer la Recommandation</h3>
            <form id="clotureForm" method="POST">
                @csrf
                <div class="mt-2 px-7 py-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire de clôture *</label>
                    <textarea name="commentaire_cloture" rows="4" class="w-full rounded-md border-gray-300 shadow-sm"
                              placeholder="Décrivez les actions entreprises et les résultats obtenus..." required></textarea>
                    <p class="text-xs text-gray-500 mt-1">Ce commentaire sera visible par tous les acteurs concernés.</p>
                </div>
                <div class="flex justify-center gap-4 mt-4">
                    <button type="button" onclick="closeClotureModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">
                        Confirmer la clôture
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openClotureModal(recommandationId) {
    const form = document.getElementById('clotureForm');
    form.action = `/its/cloture/${recommandationId}`;
    document.getElementById('clotureModal').classList.remove('hidden');
}

function closeClotureModal() {
    document.getElementById('clotureModal').classList.add('hidden');
}
</script>
@endsection
