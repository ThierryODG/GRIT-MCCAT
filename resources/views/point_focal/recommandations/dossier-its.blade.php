@extends('layouts.app')

@section('title', 'Dossier ITS - ' . $its->name)

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- Navigation -->
    <div class="mb-6">
        <a href="{{ route('point_focal.recommandations.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="mr-2 fas fa-arrow-left"></i>
            Retour aux dossiers
        </a>
    </div>

    <!-- En-tête du dossier -->
    <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full">
                    <span class="text-xl font-bold text-white">{{ substr($its->name, 0, 1) }}</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $its->name }}</h1>
                    <p class="text-gray-600">Dossier complet de l'inspecteur ITS</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-blue-600">{{ $recommandations->count() }}</div>
                <div class="text-sm text-gray-500">recommandations au total</div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4">
        <div class="p-4 text-center bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="text-2xl font-bold text-blue-600">
                {{ $recommandations->where('statut', 'point_focal_assigne')->count() }}
            </div>
            <div class="text-sm text-gray-500">À traiter</div>
        </div>
        <div class="p-4 text-center bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="text-2xl font-bold text-orange-600">
                {{ $recommandations->where('statut', 'plan_en_redaction')->count() }}
            </div>
            <div class="text-sm text-gray-500">En cours</div>
        </div>
        <div class="p-4 text-center bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="text-2xl font-bold text-green-600">
                {{ $recommandations->whereIn('statut', ['plan_soumis_responsable', 'plan_valide_responsable'])->count() }}
            </div>
            <div class="text-sm text-gray-500">Validées</div>
        </div>
        <div class="p-4 text-center bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="text-2xl font-bold text-gray-600">
                {{ $recommandations->whereIn('statut', ['cloturee', 'en_execution'])->count() }}
            </div>
            <div class="text-sm text-gray-500">Finalisées</div>
        </div>
    </div>

    <!-- Liste des recommandations -->
    <div class="space-y-4">
        @foreach($recommandations as $recommandation)
        <div class="p-6 transition-shadow bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $recommandation->reference }}</h3>
                    <p class="mt-1 text-gray-600">{{ $recommandation->titre }}</p>
                </div>
                <div class="flex space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        {{ $recommandation->priorite == 'haute' ? 'bg-red-100 text-red-800' :
                           ($recommandation->priorite == 'moyenne' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                        {{ ucfirst($recommandation->priorite) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        {{ $recommandation->statut == 'point_focal_assigne' ? 'bg-purple-100 text-purple-800' :
                           ($recommandation->statut == 'plan_en_redaction' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ $recommandation->statut_label }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 mb-4 text-sm text-gray-600 md:grid-cols-3">
                <div>
                    <span class="font-medium">Structure:</span> {{ $recommandation->structure->nom }}
                </div>
                <div>
                    <span class="font-medium">Date limite:</span> {{ $recommandation->date_limite->format('d/m/Y') }}
                    @if($recommandation->estEnRetard())
                    <span class="ml-2 text-red-500">🚨 Retard</span>
                    @endif
                </div>
                <div>
                    <span class="font-medium">Plans d'action:</span> {{ $recommandation->plansAction->count() }}
                </div>
            </div>

            <div class="flex space-x-2">
                <a href="{{ route('point_focal.recommandations.show', $recommandation) }}"
                   class="flex items-center px-4 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="mr-2 fas fa-folder-open"></i>
                    Ouvrir la recommandation
                </a>

                @if(in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction', 'plan_rejete_responsable']))
                <a href="{{ route('point_focal.plans_action.create', $recommandation) }}"
                   class="flex items-center px-4 py-2 text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                    <i class="mr-2 fas fa-edit"></i>
                    Renseigner les plans
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
