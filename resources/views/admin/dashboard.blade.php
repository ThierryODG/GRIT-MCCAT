@extends('layouts.app')

@section('title', 'Tableau de Bord Admin')

@section('content')
<div class="space-y-6">
    <!-- ==================== KPI CARDS ==================== -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <!-- Total Utilisateurs -->
        <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Utilisateurs</p>
                    <p class="mt-2 text-3xl font-bold text-blue-600">{{ number_format($stats['users']['total']) }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $stats['users']['monthly'] }} ce mois</p>
                </div>
                <div class="p-3 rounded-lg bg-blue-50">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Recommandations -->
        <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Recommandations</p>
                    <p class="mt-2 text-3xl font-bold text-purple-600">{{ number_format($stats['recommandations']['total']) }}</p>
                    <p class="mt-1 text-xs text-gray-500">Total créées</p>
                </div>
                <div class="p-3 rounded-lg bg-purple-50">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- En Retard -->
        <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">En Retard</p>
                    <p class="mt-2 text-3xl font-bold text-red-600">{{ number_format($stats['recommandations']['en_retard']) }}</p>
                    <p class="mt-1 text-xs text-gray-500">Dépassement délai</p>
                </div>
                <div class="p-3 rounded-lg bg-red-50">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Taux Validation -->
        <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Taux Validation</p>
                    <p class="mt-2 text-3xl font-bold text-green-600">{{ $stats['validation']['taux'] }}%</p>
                    <p class="mt-1 text-xs text-gray-500">Efficacité</p>
                </div>
                <div class="p-3 rounded-lg bg-green-50">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== GRAPHIQUES ==================== -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Évolution mensuelle -->
        <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Évolution des Recommandations</h3>
                <span class="text-sm text-gray-500">6 derniers mois</span>
            </div>
            <div class="h-64">
                <canvas id="evolutionChart"></canvas>
            </div>
        </div>

        <!-- Répartition par statut -->
        <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Répartition par Statut</h3>
                <span class="text-sm text-gray-500">Toutes recommandations</span>
            </div>
            <div class="h-64">
                <canvas id="statutChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ==================== ACTIVITÉS RÉCENTES ==================== -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recommandations récentes -->
        <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recommandations Récentes</h3>
                <span class="text-sm font-medium text-blue-600">{{ $recentActivity->count() }} activités</span>
            </div>
            <div class="space-y-4">
                @forelse($recentActivity as $recommandation)
                <div class="flex items-start p-3 space-x-3 transition-colors rounded-lg hover:bg-gray-50">
                    <div class="flex-shrink-0 w-2 h-2 mt-2 bg-blue-500 rounded-full"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $recommandation->titre ?? 'Recommandation sans titre' }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">
                            {{ $recommandation->its->name ?? 'Inspecteur inconnu' }}
                            @if($recommandation->structure)
                            • {{ $recommandation->structure->nom }}
                            @endif
                        </p>
                        <div class="flex items-center mt-2 space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($recommandation->statut == 'validee_ig') bg-green-100 text-green-800
                                @elseif($recommandation->statut == 'rejetee_ig') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ str_replace('_', ' ', $recommandation->statut) }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $recommandation->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-2">Aucune recommandation récente</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Top Structures -->
        <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Structures les Plus Actives</h3>
                <span class="text-sm font-medium text-purple-600">Top 5</span>
            </div>
            <div class="space-y-4">
                @forelse($topStructures as $item)
                <div class="flex items-center justify-between p-3 transition-colors rounded-lg hover:bg-gray-50">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 bg-purple-100 rounded-lg">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $item['structure']->nom ?? 'Structure inconnue' }}
                            </p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium text-purple-700 bg-purple-100 rounded-full">
                        {{ $item['total'] }} recommandations
                    </span>
                </div>
                @empty
                <div class="py-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="mt-2">Aucune donnée de structure</p>
                </div>
                @endforelse
            </div>
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
        danger: '#EF4444',
        warning: '#F59E0B',
        purple: '#8B5CF6',
        indigo: '#6366F1'
    };

    // Évolution mensuelle
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($charts['monthly_evolution'])) !!},
            datasets: [{
                label: 'Recommandations créées',
                data: {!! json_encode(array_values($charts['monthly_evolution'])) !!},
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
            labels: {!! json_encode(array_keys($charts['statut_repartition'])) !!},
            datasets: [{
                data: {!! json_encode(array_values($charts['statut_repartition'])) !!},
                backgroundColor: [
                    colors.success,
                    colors.danger,
                    colors.warning,
                    colors.primary,
                    colors.purple,
                    colors.indigo
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
                        padding: 20,
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
