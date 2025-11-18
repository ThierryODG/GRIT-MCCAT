{{-- resources/views/inspecteur_general/validation_recommandations/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Validation des Recommandations')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Validation des Recommandations</h1>
        <p class="mt-1 text-gray-600">Examinez et validez les plans soumis par les structures</p>
    </div>

    @if($structures->count() > 0)
        <!-- Liste des Structures -->
        <div class="space-y-6">
            @foreach($structures as $structure)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <!-- En-tête Structure -->
                <div class="p-4 bg-gradient-to-r from-purple-50 to-indigo-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">
                                <i class="mr-2 fas fa-building text-purple-600"></i>
                                {{ $structure->nom }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                <i class="mr-1 fas fa-map-marker-alt"></i>
                                {{ $structure->localisation ?? 'Localisation non définie' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                {{ $structure->recommandations->count() }} recommandation(s)
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Recommandations de la Structure -->
                <div class="divide-y divide-gray-200">
                    @foreach($structure->recommandations as $recommandation)
                    <div class="p-4 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $recommandation->reference }}</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ Str::limit($recommandation->titre, 100) }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                {{ $recommandation->priorite == 'haute' ? 'bg-red-100 text-red-800' :
                                   ($recommandation->priorite == 'moyenne' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($recommandation->priorite) }}
                            </span>
                        </div>

                        <!-- Information du Point Focal et Responsable -->
                        <div class="mt-3 text-sm text-gray-500 space-y-1">
                            <p>
                                <i class="mr-1 fas fa-user-circle"></i>
                                <strong>Point Focal :</strong> {{ $recommandation->pointFocal->name ?? 'N/A' }}
                            </p>
                            <p>
                                <i class="mr-1 fas fa-user"></i>
                                <strong>Responsable :</strong> {{ $recommandation->responsable->name ?? 'N/A' }}
                            </p>
                            <p>
                                <i class="mr-1 fas fa-user-tie"></i>
                                <strong>ITS :</strong> {{ $recommandation->its->name ?? 'N/A' }}
                            </p>
                            <p>
                                <i class="mr-1 fas fa-calendar"></i>
                                <strong>Date limite :</strong> {{ $recommandation->date_limite->format('d/m/Y') }}
                                @if($recommandation->estEnRetard())
                                <span class="ml-1 inline-block text-red-600 font-semibold">🚨 En retard</span>
                                @endif
                            </p>
                        </div>

                        <!-- Statut des Plans d'Action -->
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs font-medium text-gray-700 mb-2">Plans d'action en attente de validation :</p>
                            <div class="space-y-1">
                                @forelse($recommandation->plansAction as $plan)
                                <div class="flex items-start text-sm">
                                    <span class="inline-block w-2 h-2 mt-1.5 mr-2 bg-purple-500 rounded-full flex-shrink-0"></span>
                                    <span class="text-gray-700">{{ Str::limit($plan->action, 70) }}</span>
                                </div>
                                @empty
                                <p class="text-sm text-gray-500 italic">Aucun plan d'action</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Bouton Ouvrir le Dossier -->
                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('inspecteur_general.validation_recommandations.dossier', $recommandation) }}"
                               class="inline-flex items-center px-4 py-2 text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition">
                                <i class="mr-2 fas fa-folder-open"></i>
                                Ouvrir le dossier
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    @else
    <!-- Aucune recommandation -->
    <div class="py-12 text-center bg-white border border-gray-200 rounded-lg shadow-sm">
        <i class="text-4xl text-gray-400 fas fa-inbox"></i>
        <h2 class="mt-4 text-lg font-semibold text-gray-900">Aucune recommandation en attente</h2>
        <p class="mt-2 text-gray-600">Toutes les recommandations ont été validées.</p>
    </div>
    @endif
</div>
@endsection
