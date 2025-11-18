{{-- resources/views/point-focal/recommandations/show.blade.php --}}
@extends('layouts.app')

@section('title', $recommandation->reference . ' - Détails')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- En-tête avec navigation -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('point_focal.recommandations.index') }}" class="inline-flex items-center mb-2 text-blue-600 hover:text-blue-800">
                <i class="mr-2 fas fa-arrow-left"></i>
                Retour à la liste
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $recommandation->reference }}</h1>
            <p class="mt-1 text-gray-600">{{ $recommandation->titre }}</p>
        </div>

        <!-- Actions -->
        <div class="flex space-x-3">
            @if(in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction']))
            <a href="{{ route('point_focal.recommandations.edit', $recommandation) }}"
               class="flex items-center px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                <i class="mr-2 fas fa-edit"></i>
                Compléter les informations
            </a>
            <a href="{{ route('point_focal.plans_action.create', $recommandation) }}"
               class="flex items-center px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                <i class="mr-2 fas fa-plus"></i>
                Créer un plan d'action
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        @php
            // Utiliser le motif au niveau RECOMMANDATION (pas au niveau plan)
            $globalMotifResponsable = $recommandation->motif_rejet_responsable;
            $globalMotifIG = $recommandation->plansAction->pluck('motif_rejet_ig')->filter()->first();
        @endphp

        <!-- Colonne principale -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Informations générales -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Informations générales</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Structure</label>
                        <p class="text-gray-900">{{ $recommandation->structure->nom }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Priorité</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $recommandation->priorite == 'haute' ? 'bg-red-100 text-red-800' :
                               ($recommandation->priorite == 'moyenne' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($recommandation->priorite) }}
                        </span>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Date limite</label>
                        <p class="text-gray-900 {{ $recommandation->estEnRetard() ? 'text-red-600 font-semibold' : '' }}">
                            {{ $recommandation->date_limite->format('d/m/Y') }}
                            @if($recommandation->estEnRetard())
                            <span class="ml-2 text-red-500">🚨 En retard</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">ITS</label>
                        <p class="text-gray-900">{{ $recommandation->its->name ?? 'Non assigné' }}</p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Description</h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $recommandation->description }}</p>
            </div>

            <!-- Informations de planification -->
            @if($recommandation->indicateurs || $recommandation->incidence_financiere || $recommandation->delai_mois)
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Informations de planification</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @if($recommandation->indicateurs)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Indicateur de résultat</label>
                        <p class="mt-1 text-gray-700 whitespace-pre-line">{{ $recommandation->indicateurs }}</p>
                    </div>
                    @endif

                    @if($recommandation->incidence_financiere)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Incidence financière</label>
                        <p class="mt-1 text-gray-700">{{ ucfirst($recommandation->incidence_financiere) }}</p>
                    </div>
                    @endif

                    @if($recommandation->delai_mois)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Délai total</label>
                        <p class="mt-1 text-gray-700">{{ $recommandation->delai_mois }} mois</p>
                    </div>
                    @endif

                    @if($recommandation->date_debut_prevue)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Date de début prévue</label>
                        <p class="mt-1 text-gray-700">{{ $recommandation->date_debut_prevue->format('d/m/Y') }}</p>
                    </div>
                    @endif

                    @if($recommandation->date_fin_prevue)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Date de fin prévue</label>
                        <p class="mt-1 text-gray-700">{{ $recommandation->date_fin_prevue->format('d/m/Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="p-6 bg-amber-50 border border-amber-200 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <i class="mt-1 mr-3 text-amber-500 fas fa-info-circle"></i>
                    <div>
                        <h3 class="font-semibold text-amber-900">Informations de planification manquantes</h3>
                        <p class="mt-1 text-sm text-amber-800">
                            Vous devez d'abord remplir les informations de planification (indicateur, incidence, délai, dates)
                            avant de créer des actions.
                        </p>
                        @if(in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction']))
                        <a href="{{ route('point_focal.recommandations.edit', $recommandation) }}"
                           class="inline-block mt-2 text-blue-600 hover:text-blue-800 font-medium">
                            Compléter les informations →
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Plans d'action -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Plans d'action</h2>
                    @if(in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction']))
                    <a href="{{ route('point_focal.plans_action.create', $recommandation) }}"
                       class="flex items-center px-3 py-1 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                        <i class="mr-1 fas fa-plus"></i>
                        Ajouter un plan
                    </a>
                    @endif
                </div>

                @if($recommandation->plansAction->count() > 0)
                <div class="space-y-4">
                    @foreach($recommandation->plansAction as $plan)
                    <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="font-medium text-gray-900">Action #{{ $loop->iteration }}</h3>
                            <div class="flex space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $plan->statut_validation == 'valide_ig' ? 'bg-green-100 text-green-800' :
                                       ($plan->statut_validation == 'rejete_ig' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $plan->statut_validation_label }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $plan->statut_execution == 'termine' ? 'bg-green-100 text-green-800' :
                                       ($plan->statut_execution == 'en_cours' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $plan->statut_execution_label }}
                                </span>
                            </div>
                        </div>

                        <p class="mb-3 text-gray-700">{{ $plan->action }}</p>

                        <!-- Motif de rejet responsable affiché au niveau global (colonne de droite) -->

                        <!-- Motif de rejet IG (également affiché au niveau global si présent) -->
                        @if($plan->statut_validation == 'rejete_ig' && $plan->motif_rejet_ig)
                        <div class="mb-3 p-3 bg-red-50 border border-red-300 rounded text-red-700 text-sm">
                            <strong>📋 Demande de modification de l'Inspecteur Général :</strong>
                            <p class="mt-1 whitespace-pre-line">{{ $plan->motif_rejet_ig }}</p>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">
                            <div>
                                <label class="text-gray-500">Avancement</label>
                                <p class="text-gray-700">{{ $plan->pourcentage_avancement }}%</p>
                            </div>
                        </div>

                        <!-- Boutons d'actions -->
                        @if(in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction']))
                        <div class="flex mt-4 space-x-2">
                            <a href="{{ route('point_focal.plans_action.edit', $plan) }}"
                               class="flex items-center px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                                <i class="mr-1 fas fa-edit"></i>
                                Modifier
                            </a>
                            <button type="button"
                                    onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette action ?')) { document.getElementById('delete-form-{{ $plan->id }}').submit(); }"
                                    class="flex items-center px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700">
                                <i class="mr-1 fas fa-trash"></i>
                                Supprimer
                            </button>

                            <form id="delete-form-{{ $plan->id }}" action="{{ route('point_focal.plans_action.destroy', $plan) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="py-8 text-center text-gray-500">
                    <i class="mb-3 text-3xl fas fa-tasks"></i>
                    <p>Aucun plan d'action créé pour le moment</p>
                    @if(in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction']))
                    <a href="{{ route('point_focal.plans_action.create', $recommandation) }}" class="inline-block mt-2 text-blue-600 hover:text-blue-800">
                        Créer le premier plan d'action
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Statut et workflow -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Statut</h2>
                <div class="text-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $recommandation->statut_color }}">
                        {{ $recommandation->statut_label }}
                    </span>
                </div>

                <!-- Workflow simplifié -->
                <div class="mt-4 space-y-2">
                    @php
                        $etapes = [
                            'point_focal_assigne' => 'Assigné au point focal',
                            'plan_en_redaction' => 'Plan en rédaction',
                            'plan_soumis_responsable' => 'Soumis au responsable',
                            'plan_valide_responsable' => 'Validé par le responsable',
                            'plan_soumis_ig' => 'Soumis à l\'IG',
                            'plan_valide_ig' => 'Validé par l\'IG',
                            'en_execution' => 'En exécution',
                            'execution_terminee' => 'Exécution terminée',
                            'demande_cloture' => 'Demande de clôture',
                            'cloturee' => 'Clôturée'
                        ];
                    @endphp

                    @foreach($etapes as $etape => $label)
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full {{ $recommandation->statut == $etape ? 'bg-blue-500' : 'bg-gray-300' }} mr-2"></div>
                        <span class="text-sm {{ $recommandation->statut == $etape ? 'text-blue-600 font-medium' : 'text-gray-500' }}">
                            {{ $label }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Commentaires IG -->
            @if($recommandation->commentaire_ig)
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Commentaire de l'IG</h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $recommandation->commentaire_ig }}</p>
                @if($recommandation->date_validation_ig)
                <p class="mt-2 text-sm text-gray-500">
                    Le {{ $recommandation->date_validation_ig->format('d/m/Y à H:i') }}
                </p>
                @endif
            </div>
            @endif

            <!-- Motif global du Responsable -->
            @if($globalMotifResponsable)
            <div class="p-6 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg shadow-sm">
                <h2 class="mb-2 text-lg font-semibold text-yellow-900 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    Modifications demandées (Responsable)
                </h2>
                <p class="text-sm text-yellow-800 mb-3 italic">La recommandation a été rejetée. Veuillez corriger l'ensemble de votre contribution (planification et/ou plans d'action) selon les indications ci-dessous :</p>
                <p class="text-yellow-800 whitespace-pre-line bg-white p-3 rounded border border-yellow-200">{{ $globalMotifResponsable }}</p>
            </div>
            @endif

            <!-- Motif global de l'IG -->
            @if($globalMotifIG)
            <div class="p-6 bg-red-50 border-l-4 border-red-400 rounded-lg shadow-sm">
                <h2 class="mb-2 text-lg font-semibold text-red-900 flex items-center gap-2">
                    <i class="fas fa-times-circle"></i>
                    Modifications demandées (Inspecteur Général)
                </h2>
                <p class="text-sm text-red-800 mb-3 italic">L'Inspecteur Général a demandé des modifications. Veuillez corriger votre contribution selon les indications suivantes :</p>
                <p class="text-red-800 whitespace-pre-line bg-white p-3 rounded border border-red-200">{{ $globalMotifIG }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
