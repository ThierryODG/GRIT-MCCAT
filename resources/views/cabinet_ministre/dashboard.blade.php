@extends('layouts.app')

@section('title', 'Cabinet Ministre')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span class="ml-1 text-gray-700">Tableau de Bord</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ==================== KPI CARDS ==================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Recommandations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Recommandations</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_recommandations']) }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Taux de Mise en Œuvre -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Taux de Mise en Œuvre</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $tauxMiseEnOeuvre }}%</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['cloturees'] }} recommandations clôturées</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- En Retard -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">En Retard Critique</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $enRetard }}</p>
                    <p class="text-xs text-gray-500 mt-1">Dépassement de délai</p>
                </div>
                <div class="p-3 bg-red-50 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Délai Moyen -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Délai Moyen</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $delaiMoyen }}j</p>
                    <p class="text-xs text-gray-500 mt-1">Temps de traitement moyen</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== GRAPHS & CHARTS ==================== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Évolution sur 6 mois -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Évolution des Recommandations</h3>
                <span class="text-sm text-gray-500">6 derniers mois</span>
            </div>
            <div class="h-64">
                <canvas id="evolutionChart"></canvas>
            </div>
        </div>

        <!-- Répartition par statut -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Répartition par Statut</h3>
                <span class="text-sm text-gray-500">État global</span>
            </div>
            <div class="h-64">
                <canvas id="statutChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ==================== STRUCTURES & ACTIVITÉS ==================== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Structures Performantes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Structures les Plus Performantes</h3>
                <span class="text-sm text-blue-600 font-medium">{{ $topStructures->count() }} structures</span>
            </div>
            <div class="space-y-4">
                @forelse($topStructures as $index => $structure)
                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-bold">{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $structure->its->name ?? 'Structure inconnue' }}</p>
                            <p class="text-xs text-gray-500">{{ $structure->its->direction ?? 'Direction non spécifiée' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">{{ $structure->total }}</p>
                        <p class="text-xs text-green-600">recommandations traitées</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-2">Aucune donnée disponible</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Dernières Activités -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Activités Récentes</h3>
                <span class="text-sm text-blue-600 font-medium">{{ $dernieresActivites->count() }} activités</span>
            </div>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($dernieresActivites as $activite)
                <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $activite->titre ?? 'Recommandation sans titre' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $activite->its->name ?? 'Structure inconnue' }} •
                            {{ $activite->created_at->diffForHumans() }}
                        </p>
                        <div class="flex items-center space-x-2 mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($activite->statut == 'cloturee') bg-green-100 text-green-800
                                @elseif($activite->statut == 'en_cours') bg-blue-100 text-blue-800
                                @elseif($activite->statut == 'en_attente_validation') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ str_replace('_', ' ', $activite->statut) }}
                            </span>
                            @if($activite->priorite == 'haute')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Priorité haute
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-2">Aucune activité récente</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ==================== STATISTIQUES DÉTAILLÉES ==================== -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Statistiques Détaillées par Statut</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($stats as $key => $value)
            @if($key != 'total_recommandations')
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
                <p class="text-sm text-gray-600 mt-1 capitalize">
                    {{ str_replace('_', ' ', $key) }}
                </p>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart colors
    const colors = {
        primary: '#3B82F6',
        success: '#10B981',
        warning: '#F59E0B',
        danger: '#EF4444',
        purple: '#8B5CF6',
        gray: '#6B7280'
    };

    // Evolution sur 6 mois
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($recommandationsParMois->toArray())) !!},
            datasets: [{
                label: 'Recommandations',
                data: {!! json_encode(array_values($recommandationsParMois->toArray())) !!},
                borderColor: colors.primary,
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Répartition par statut
    const statutCtx = document.getElementById('statutChart').getContext('2d');
    new Chart(statutCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($repartitionStatut)) !!},
            datasets: [{
                data: {!! json_encode(array_values($repartitionStatut)) !!},
                backgroundColor: [
                    colors.primary,
                    colors.success,
                    colors.warning,
                    colors.danger,
                    colors.purple
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
