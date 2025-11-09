@extends('layouts.app')

@section('title', 'Tableau de Bord - Responsable')

@section('content')
<div class="space-y-6">
    <!-- ==================== KPI CARDS ==================== -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <!-- Recommandations assignées -->
        <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Recommandations Assignées</p>
                    <p class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['recommandations_assignees'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">À votre structure</p>
                </div>
                <div class="p-3 rounded-lg bg-blue-50">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Plans en attente -->
        <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Plans en Attente</p>
                    <p class="mt-2 text-3xl font-bold text-orange-600">{{ $stats['plans_en_attente'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">Validation requise</p>
                </div>
                <div class="p-3 rounded-lg bg-orange-50">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- En retard -->
        <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">En Retard</p>
                    <p class="mt-2 text-3xl font-bold text-red-600">{{ $stats['recommandations_retard'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">Délai dépassé</p>
                </div>
                <div class="p-3 rounded-lg bg-red-50">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Taux validation -->
        <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Taux Validation</p>
                    <p class="mt-2 text-3xl font-bold text-green-600">{{ $stats['taux_validation'] }}%</p>
                    <p class="mt-1 text-xs text-gray-500">Plans validés</p>
                </div>
                <div class="p-3 rounded-lg bg-green-50">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== ACTIONS RAPIDES ==================== -->
    <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Actions Rapides</h3>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <a href="{{ route('responsable.validation_plans.index') }}"
               class="flex items-center justify-center p-4 transition-colors rounded-lg bg-orange-50 hover:bg-orange-100 group">
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto text-orange-600 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-2 font-medium text-orange-600">Valider Plans</p>
                    <p class="text-sm text-orange-500">{{ $stats['plans_en_attente'] }} en attente</p>
                </div>
            </a>

            <a href="{{ route('responsable.points_focaux.index') }}"
               class="flex items-center justify-center p-4 transition-colors rounded-lg bg-blue-50 hover:bg-blue-100 group">
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto text-blue-600 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <p class="mt-2 font-medium text-blue-600">Points Focaux</p>
                    <p class="text-sm text-blue-500">Gérer les affectations</p>
                </div>
            </a>

            <a href="{{ route('responsable.suivi.index') }}"
               class="flex items-center justify-center p-4 transition-colors rounded-lg bg-purple-50 hover:bg-purple-100 group">
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto text-purple-600 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="mt-2 font-medium text-purple-600">Suivi Global</p>
                    <p class="text-sm text-purple-500">Voir toutes les activités</p>
                </div>
            </a>
        </div>
    </div>

    <!-- ==================== ACTIVITÉS RÉCENTES ==================== -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Plans en attente -->
        <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Plans en Attente de Validation</h3>
                <span class="text-sm font-medium text-orange-600">{{ $plansEnAttente->count() }} plans</span>
            </div>
            <div class="space-y-4">
                @forelse($plansEnAttente as $plan)
                <div class="flex items-start p-3 space-x-3 transition-colors rounded-lg hover:bg-gray-50">
                    <div class="flex-shrink-0 w-2 h-2 mt-2 bg-orange-500 rounded-full"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $plan->recommandation->titre ?? 'Recommandation inconnue' }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">
                            Point focal: {{ $plan->pointFocal->name ?? 'Non assigné' }}
                        </p>
                        <div class="flex items-center mt-2 space-x-2">
                            <span class="text-xs text-gray-500">
                                Soumis le {{ $plan->created_at->format('d/m/Y') }}
                            </span>
                            <a href="{{ route('responsable.validation_plans.show', $plan) }}"
                               class="text-xs text-blue-600 hover:text-blue-900">
                                Examiner
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-2">Aucun plan en attente</p>
                </div>
                @endforelse
            </div>
        </div>

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
                            {{ $recommandation->titre }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">
                            {{ $recommandation->its->name ?? 'Inspecteur ITS' }}
                            @if($recommandation->pointFocal)
                            • PF: {{ $recommandation->pointFocal->name }}
                            @endif
                        </p>
                        <div class="flex items-center mt-2 space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($recommandation->statut == 'point_focal_assigne') bg-green-100 text-green-800
                                @elseif($recommandation->statut == 'plan_en_redaction') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ str_replace('_', ' ', $recommandation->statut) }}
                            </span>
                            @if($recommandation->date_limite)
                            <span class="text-xs text-gray-500">
                                Échéance: {{ $recommandation->date_limite->format('d/m/Y') }}
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
    </div>
</div>
@endsection
