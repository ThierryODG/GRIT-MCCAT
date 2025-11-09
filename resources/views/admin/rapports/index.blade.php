@extends('layouts.app')

@section('title', 'Rapports & Statistiques')

@section('content')
<div class="space-y-6">
    {{-- En-tête --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rapports & Statistiques</h1>
            <p class="text-gray-600">Génération et analyse des données du système</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</span>
        </div>
    </div>

    {{-- Filtres --}}
    @include('admin.rapports.partials.filters', ['filters' => $filters ?? []])

    {{-- Cartes de Statistiques --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
        @include('admin.rapports.partials.stat-card', [
            'title' => 'Recommandations',
            'value' => $stats['total_recommandations'] ?? 0,
            'icon' => 'chart-bar',
            'color' => 'blue',
            'description' => 'Total sur la période'
        ])

        @include('admin.rapports.partials.stat-card', [
            'title' => 'Taux Avancement',
            'value' => ($stats['taux_avancement'] ?? 0) . '%',
            'icon' => 'check-circle',
            'color' => 'green',
            'description' => 'Recommandations terminées'
        ])

        @include('admin.rapports.partials.stat-card', [
            'title' => 'Temps Moyen',
            'value' => $stats['moyenne_temps_traitement'] ?? 'N/A',
            'icon' => 'clock',
            'color' => 'purple',
            'description' => 'Traitement moyen'
        ])

        @include('admin.rapports.partials.stat-card', [
            'title' => 'Urgentes',
            'value' => $stats['recommandations_urgentes'] ?? 0,
            'icon' => 'exclamation-triangle',
            'color' => 'red',
            'description' => 'Priorité haute en cours'
        ])
    </div>

    {{-- Graphiques --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Répartition par Statut --}}
        <div class="p-6 bg-white rounded-lg shadow">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Répartition par Statut</h3>
            <div class="h-64">
                <canvas id="statutChart"></canvas>
            </div>
        </div>

        {{-- Répartition par Priorité --}}
        <div class="p-6 bg-white rounded-lg shadow">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Répartition par Priorité</h3>
            <div class="h-64">
                <canvas id="prioriteChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Tableau des Données --}}
    @if(isset($donnees) && $donnees->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Détail des Recommandations</h3>
                <div class="flex space-x-3">
                    <form action="{{ route('admin.rapports.pdf') }}" method="GET" class="inline">
                        @foreach($filters as $key => $value)
                            @if($value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">
                            <i class="mr-2 fas fa-file-pdf"></i>
                            PDF
                        </button>
                    </form>
                    <form action="{{ route('admin.rapports.excel') }}" method="GET" class="inline">
                        @foreach($filters as $key => $value)
                            @if($value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                            <i class="mr-2 fas fa-file-excel"></i>
                            Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Titre</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Priorité</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ITS</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date Création</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Plans Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($donnees as $recommandation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">#{{ $recommandation->id }}</td>
                        <td class="max-w-xs px-6 py-4 text-sm text-gray-900 truncate">{{ $recommandation->titre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($recommandation->statut == 'en_attente_validation') bg-yellow-100 text-yellow-800
                                @elseif($recommandation->statut == 'validee_ig') bg-green-100 text-green-800
                                @elseif($recommandation->statut == 'en_analyse_structure') bg-blue-100 text-blue-800
                                @elseif($recommandation->statut == 'cloturee') bg-gray-100 text-gray-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $recommandation->statut }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            <span class="inline-flex items-center">
                                <i class="fas
                                    @if($recommandation->priorite == 'haute') fa-fire text-red-500
                                    @elseif($recommandation->priorite == 'moyenne') fa-exclamation-circle text-yellow-500
                                    @else fa-info-circle text-blue-500 @endif mr-1">
                                </i>
                                {{ $recommandation->priorite }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $recommandation->its->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $recommandation->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-center text-gray-900 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $recommandation->plan_actions_count }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <p class="text-sm text-gray-600">
                Affichage de {{ $donnees->count() }} recommandation(s)
            </p>
        </div>
    </div>
    @elseif(isset($donnees))
    <div class="p-8 text-center bg-white rounded-lg shadow">
        <i class="mb-4 text-4xl text-gray-300 fas fa-chart-bar"></i>
        <h3 class="mb-2 text-lg font-medium text-gray-900">Aucune donnée trouvée</h3>
        <p class="text-gray-500">Aucune recommandation ne correspond à vos critères de recherche.</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique par statut
    const statutCtx = document.getElementById('statutChart').getContext('2d');
    new Chart(statutCtx, {
        type: 'pie',
        data: {
            labels: @json(array_keys($stats['par_statut'] ?? [])),
            datasets: [{
                data: @json(array_values($stats['par_statut'] ?? [])),
                backgroundColor: ['#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#8b5cf6']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Graphique par priorité
    const prioriteCtx = document.getElementById('prioriteChart').getContext('2d');
    new Chart(prioriteCtx, {
        type: 'bar',
        data: {
            labels: @json(array_keys($stats['par_priorite'] ?? [])),
            datasets: [{
                label: 'Nombre de recommandations',
                data: @json(array_values($stats['par_priorite'] ?? [])),
                backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
