@extends('layouts.app')

@section('title', 'Dashboard Point Focal')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Point Focal</h1>
        <p class="text-gray-600">Vue d'ensemble de vos recommandations et plans d'action</p>
    </div>

    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
        <!-- Carte Recommandations totales -->
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 text-blue-600 bg-blue-100 rounded-full">
                    <i class="text-xl fas fa-list-alt"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Recommandations totales</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalRecommandations }}</p>
                </div>
            </div>
        </div>

        <!-- Carte À traiter -->
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 text-purple-600 bg-purple-100 rounded-full">
                    <i class="text-xl fas fa-pencil-alt"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">À traiter</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $attentePlanCount }}</p>
                </div>
            </div>
        </div>

        <!-- Carte En exécution -->
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-sky-100 text-sky-600">
                    <i class="text-xl fas fa-play-circle"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">En exécution</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $enExecutionCount }}</p>
                </div>
            </div>
        </div>

        <!-- Carte En retard -->
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 text-red-600 bg-red-100 rounded-full">
                    <i class="text-xl fas fa-exclamation-triangle"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">En retard</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $enRetardCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Section Répartition par statut -->
        <div class="p-6 bg-white rounded-lg shadow">
            <h2 class="mb-4 text-lg font-semibold">Répartition par statut</h2>
            <div class="space-y-3">
                @foreach($statuts as $statut)
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statut->color_class }}">
                        {{ $statut->label }}
                    </span>
                    <span class="font-semibold">{{ $statut->count }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Section Alertes et échéances -->
        <div class="p-6 bg-white rounded-lg shadow">
            <h2 class="mb-4 text-lg font-semibold">Alertes et échéances</h2>

            <!-- Plans en retard -->
            @if($plansEnRetard->count() > 0)
            <div class="mb-4">
                <h3 class="mb-2 font-medium text-red-600">🚨 Plans en retard ({{ $plansEnRetard->count() }})</h3>
                <div class="space-y-2">
                    @foreach($plansEnRetard->take(3) as $recommandation)
                    <div class="pl-3 text-sm text-gray-700 border-l-4 border-red-500">
                        <a href="{{ route('point_focal.recommandations.show', $recommandation) }}" class="font-medium hover:text-blue-600">
                            {{ $recommandation->reference }}
                        </a>
                        <p class="text-gray-500">Échéance: {{ $recommandation->date_fin_prevue->format('d/m/Y') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Prochaines échéances -->
            @if($prochainesEcheances->count() > 0)
            <div>
                <h3 class="mb-2 font-medium text-amber-600">📅 Prochaines échéances (7 jours)</h3>
                <div class="space-y-2">
                    @foreach($prochainesEcheances as $recommandation)
                    <div class="pl-3 text-sm text-gray-700 border-l-4 border-amber-500">
                        <a href="{{ route('point_focal.recommandations.show', $recommandation) }}" class="font-medium hover:text-blue-600">
                            {{ $recommandation->reference }}
                        </a>
                        <p class="text-gray-500">Échéance: {{ $recommandation->date_fin_prevue->format('d/m/Y') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Recommandations récentes -->
    <div class="mt-8 bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">Recommandations récentes</h2>
        </div>
        <div class="p-6">
            @if($recentRecommandations->count() > 0)
            <div class="space-y-4">
                @foreach($recentRecommandations as $recommandation)
                <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                    <div>
                        <a href="{{ route('point_focal.recommandations.show', $recommandation) }}"
                           class="font-medium text-blue-600 hover:text-blue-800">
                            {{ $recommandation->reference }} - {{ $recommandation->titre }}
                        </a>
                        <p class="text-sm text-gray-600">{{ $recommandation->structure->nom }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $statuts->firstWhere('statut', $recommandation->statut)?->color_class ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statuts->firstWhere('statut', $recommandation->statut)?->label ?? $recommandation->statut }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <p class="py-4 text-center text-gray-500">Aucune recommandation assignée.</p>
            @endif
        </div>
    </div>
</div>
@endsection
