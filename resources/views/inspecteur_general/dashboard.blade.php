@extends('layouts.app')

@section('title', 'Tableau de Bord - Inspecteur Général')

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
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Recommandations en attente -->
        <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Recommandations en Attente</p>
                    <p class="mt-2 text-3xl font-bold text-orange-600">{{ $statsRecommandations['en_attente_validation'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">En attente de validation</p>
                </div>
                <div class="p-3 rounded-lg bg-orange-50">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Recommandations validées -->
        <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Recommandations Validées</p>
                    <p class="mt-2 text-3xl font-bold text-green-600">{{ $statsRecommandations['validees_ig'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">Par vous</p>
                </div>
                <div class="p-3 rounded-lg bg-green-50">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Plans d'action en attente -->
        <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Plans d'Action en Attente</p>
                    <p class="mt-2 text-3xl font-bold text-blue-600">{{ $statsPlansAction['en_attente_validation'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">En attente de validation</p>
                </div>
                <div class="p-3 rounded-lg bg-blue-50">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== STATISTIQUES DÉTAILLÉES ==================== -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Stats Recommandations -->
        <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Validation des Recommandations</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 text-center rounded-lg bg-green-50">
                    <p class="text-2xl font-bold text-green-600">{{ $statsRecommandations['validees_ig'] }}</p>
                    <p class="mt-1 text-sm font-medium text-green-600">Validées</p>
                </div>
                <div class="p-4 text-center rounded-lg bg-red-50">
                    <p class="text-2xl font-bold text-red-600">{{ $statsRecommandations['rejetees_ig'] }}</p>
                    <p class="mt-1 text-sm font-medium text-red-600">Rejetées</p>
                </div>
                <div class="col-span-2 p-4 text-center rounded-lg bg-orange-50">
                    <p class="text-2xl font-bold text-orange-600">{{ $statsRecommandations['en_attente_validation'] }}</p>
                    <p class="mt-1 text-sm font-medium text-orange-600">En Attente de Validation</p>
                </div>
            </div>
        </div>

        <!-- Stats Plans d'Action -->
        <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Validation des Plans d'Action</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 text-center rounded-lg bg-green-50">
                    <p class="text-2xl font-bold text-green-600">{{ $statsPlansAction['valides'] }}</p>
                    <p class="mt-1 text-sm font-medium text-green-600">Validés</p>
                </div>
                <div class="p-4 text-center rounded-lg bg-red-50">
                    <p class="text-2xl font-bold text-red-600">{{ $statsPlansAction['rejetes'] }}</p>
                    <p class="mt-1 text-sm font-medium text-red-600">Rejetés</p>
                </div>
                <div class="col-span-2 p-4 text-center rounded-lg bg-blue-50">
                    <p class="text-2xl font-bold text-blue-600">{{ $statsPlansAction['en_attente_validation'] }}</p>
                    <p class="mt-1 text-sm font-medium text-blue-600">En Attente de Validation</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== GRAPHIQUES ==================== -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Évolution des validations -->
        <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Évolution des Validations</h3>
                <span class="text-sm text-gray-500">6 derniers mois</span>
            </div>
            <div class="h-64">
                <canvas id="validationsChart"></canvas>
            </div>
        </div>

        <!-- Répartition des actions -->
        <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Répartition des Actions</h3>
                <span class="text-sm text-gray-500">Vos décisions</span>
            </div>
            <div class="h-64">
                <canvas id="repartitionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ==================== ACTIVITÉS RÉCENTES ==================== -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recommandations récentes -->
        <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recommandations Récentes</h3>
                <span class="text-sm font-medium text-blue-600">{{ $recommandationsRecentes->count() }} activités</span>
            </div>
            <div class="space-y-4">
                @forelse($recommandationsRecentes as $recommandation)
                <div class="flex items-start p-3 space-x-3 transition-colors rounded-lg hover:bg-gray-50">
                    <div class="flex-shrink-0 w-2 h-2 mt-2 bg-blue-500 rounded-full"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $recommandation->titre ?? 'Recommandation sans titre' }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">
                            {{ $recommandation->its->name ?? 'Inspecteur inconnu' }}
                            <!-- Afficher la structure depuis la recommandation directement -->
                            @if($recommandation->structure)
                            • {{ $recommandation->structure->nom }}
                            @endif
                        </p>
                        <div class="flex items-center mt-2 space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($recommandation->statut == 'validee_ig') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ str_replace('_', ' ', $recommandation->statut) }}
                            </span>
                            @if($recommandation->date_validation_ig)
                            <span class="text-xs text-gray-500">
                                {{ $recommandation->date_validation_ig->format('d/m/Y') }}
                            </span>
                            @endif
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

        <!-- Plans d'action récents -->
        <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Plans d'Action Récents</h3>
                <span class="text-sm font-medium text-purple-600">{{ $plansActionsRecents->count() }} activités</span>
            </div>
            <div class="space-y-4">
                @forelse($plansActionsRecents as $plan)
                <div class="flex items-start p-3 space-x-3 transition-colors rounded-lg hover:bg-gray-50">
                    <div class="flex-shrink-0 w-2 h-2 mt-2 bg-purple-500 rounded-full"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            Plan d'action - {{ $plan->recommandation->titre ?? 'Recommandation inconnue' }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">
                            {{ $plan->recommandation->its->name ?? 'Inspecteur inconnu' }}
                            <!-- Afficher la structure depuis la recommandation -->
                            @if($plan->recommandation->structure)
                            • {{ $plan->recommandation->structure->nom }}
                            @endif
                        </p>
                        <div class="flex items-center mt-2 space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($plan->statut_validation == 'valide_ig') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ str_replace('_', ' ', $plan->statut_validation) }}
                            </span>
                            @if($plan->date_validation_ig)
                            <span class="text-xs text-gray-500">
                                {{ $plan->date_validation_ig->format('d/m/Y') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-2">Aucun plan d'action récent</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ==================== ACTIONS RAPIDES ==================== -->
    <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Actions Rapides</h3>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <a href="{{ route('inspecteur_general.recommandations.index') }}"
               class="flex items-center justify-center p-4 transition-colors rounded-lg bg-blue-50 hover:bg-blue-100 group">
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto text-blue-600 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-2 font-medium text-blue-600">Valider Recommandations</p>
                    <p class="text-sm text-blue-500">{{ $statsRecommandations['en_attente_validation'] }} en attente</p>
                </div>
            </a>

            <a href="{{ route('inspecteur_general.plan_actions.index') }}"
               class="flex items-center justify-center p-4 transition-colors rounded-lg bg-green-50 hover:bg-green-100 group">
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto text-green-600 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-2 font-medium text-green-600">Valider Plans d'Action</p>
                    <p class="text-sm text-green-500">{{ $statsPlansAction['en_attente_validation'] }} en attente</p>
                </div>
            </a>

            <a href="{{ route('inspecteur_general.suivi.index') }}"
               class="flex items-center justify-center p-4 transition-colors rounded-lg bg-purple-50 hover:bg-purple-100 group">
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto text-purple-600 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="mt-2 font-medium text-purple-600">Suivi Global</p>
                    <p class="text-sm text-purple-500">Voir toutes les recommandations</p>
                </div>
            </a>
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
        purple: '#8B5CF6'
    };

    // Évolution des validations
    const validationsCtx = document.getElementById('validationsChart').getContext('2d');
    new Chart(validationsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($validationsParMois->toArray())) !!},
            datasets: [{
                label: 'Validations',
                data: {!! json_encode(array_values($validationsParMois->toArray())) !!},
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

    // Répartition des actions
    const repartitionCtx = document.getElementById('repartitionChart').getContext('2d');
    new Chart(repartitionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Recommandations Validées', 'Recommandations Rejetées', 'Plans Validés', 'Plans Rejetés'],
            datasets: [{
                data: [
                    {{ $statsRecommandations['validees_ig'] }},
                    {{ $statsRecommandations['rejetees_ig'] }},
                    {{ $statsPlansAction['valides'] }},
                    {{ $statsPlansAction['rejetes'] }}
                ],
                backgroundColor: [
                    colors.success,
                    colors.danger,
                    colors.primary,
                    colors.warning
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
