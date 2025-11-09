@extends('layouts.app')


@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span class="ml-1 text-gray-700">Rapports - Statistiques</span>
        </div>
    </li>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête du rapport -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Rapport Statistiques des Recommandations</h1>
                        <p class="text-gray-600">Généré le {{ now()->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="window.print()"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 flex items-center no-print">
                            📄 Imprimer
                        </button>
                        <a href="{{ route('its.rapports.generer') }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 no-print">
                            Nouveau Rapport
                        </a>
                    </div>
                </div>

                <!-- Informations du rapport -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                    <div>
                        <strong>Période :</strong>
                        {{ \Carbon\Carbon::parse($filters['date_debut'])->format('d/m/Y') }} -
                        {{ \Carbon\Carbon::parse($filters['date_fin'])->format('d/m/Y') }}
                    </div>
                    <div>
                        <strong>Type :</strong> {{ ucfirst($filters['type_rapport']) }}
                    </div>
                    <div>
                        <strong>Total recommandations :</strong> {{ $recommandations->count() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques résumées -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] ?? 0 }}</p>
                <p class="text-sm text-gray-600">Total</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
                <p class="text-2xl font-bold text-green-600">{{ $stats['validees'] ?? 0 }}</p>
                <p class="text-sm text-gray-600">Validées</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['en_cours'] ?? 0 }}</p>
                <p class="text-sm text-gray-600">En Cours</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
                <p class="text-2xl font-bold text-purple-600">{{ $stats['cloturees'] ?? 0 }}</p>
                <p class="text-sm text-gray-600">Clôturées</p>
            </div>
        </div>

        <!-- Répartition par statut -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Tableau des statuts -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Répartition par Statut</h3>
                    <div class="space-y-3">
                        @foreach($statsParStatut as $statut => $count)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $statut) }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full"
                                         style="width: {{ $recommandations->count() > 0 ? ($count / $recommandations->count() * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Répartition par priorité -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Répartition par Priorité</h3>
                    <div class="space-y-3">
                        @foreach($statsParPriorite as $priorite => $count)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 capitalize">{{ $priorite }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full
                                        {{ $priorite == 'haute' ? 'bg-red-600' :
                                           ($priorite == 'moyenne' ? 'bg-yellow-600' : 'bg-green-600') }}"
                                         style="width: {{ $recommandations->count() > 0 ? ($count / $recommandations->count() * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des recommandations -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Liste des Recommandations</h3>

                @if($recommandations->count() > 0)
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
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date Limite
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date Création
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recommandations as $recommandation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $recommandation->reference }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ Str::limit($recommandation->titre, 60) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $recommandation->priorite == 'haute' ? 'bg-red-100 text-red-800' :
                                           ($recommandation->priorite == 'moyenne' ? 'bg-yellow-100 text-yellow-800' :
                                           'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($recommandation->priorite) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ in_array($recommandation->statut, ['validee_ig', 'terminee', 'cloturee']) ? 'bg-green-100 text-green-800' :
                                           (in_array($recommandation->statut, ['rejetee_ig', 'plan_action_rejete']) ? 'bg-red-100 text-red-800' :
                                           'bg-yellow-100 text-yellow-800') }}">
                                        {{ str_replace('_', ' ', ucfirst($recommandation->statut)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $recommandation->date_limite->format('d/m/Y') }}
                                    @if($recommandation->date_limite->isPast() && !in_array($recommandation->statut, ['terminee', 'cloturee']))
                                    <span class="ml-1 text-red-500" title="Dépassée">⚠️</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $recommandation->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune donnée trouvée</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Aucune recommandation ne correspond aux critères sélectionnés.
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Analyse et commentaires -->
        @if($recommandations->count() > 0)
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">Analyse du Rapport</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
                <div>
                    <p><strong>Taux de validation :</strong>
                        {{ $recommandations->count() > 0 ? round(($stats['validees'] / $recommandations->count() * 100), 1) : 0 }}%
                    </p>
                    <p><strong>Taux de clôture :</strong>
                        {{ $recommandations->count() > 0 ? round(($stats['cloturees'] / $recommandations->count() * 100), 1) : 0 }}%
                    </p>
                </div>
                <div>
                    <p><strong>Recommandations en retard :</strong> {{ $stats['en_retard'] ?? 0 }}</p>
                    <p><strong>Priorités hautes :</strong> {{ $statsParPriorite['haute'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Pied de page -->
        <div class="mt-6 text-center text-sm text-gray-500">
            <p>Rapport généré par le système GRIT - {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    .bg-white {
        background: white !important;
    }
    .shadow-sm {
        box-shadow: none !important;
    }
    .border {
        border: 1px solid #e5e7eb !important;
    }
}
</style>
@endsection
