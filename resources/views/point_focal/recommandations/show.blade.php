{{-- resources/views/point-focal/recommandations/show.blade.php --}}
@extends('layouts.app')

@section('title', $recommandation->reference . ' - Détails')

@section('content')
    <div class="container px-4 py-6 mx-auto">
        <!-- En-tête avec navigation -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('point_focal.recommandations.index') }}"
                    class="inline-flex items-center mb-2 text-blue-600 hover:text-blue-800">
                    <i class="mr-2 fas fa-arrow-left"></i>
                    Retour à la liste
                </a>
                <h1 class="text-2xl font-bold text-gray-900">{{ $recommandation->reference }}</h1>
                <p class="mt-1 text-gray-600">{{ $recommandation->titre }}</p>
            </div>



            <!-- Actions -->
            <div class="flex space-x-3">
                @if(in_array($recommandation->statut, ['plan_valide_ig', 'en_execution', 'execution_terminee', 'demande_cloture']))
                    <a href="{{ route('point_focal.avancement.show', $recommandation) }}"
                        class="flex items-center px-4 py-2 text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                        <i class="mr-2 fas fa-tasks"></i>
                        Gérer l'exécution
                    </a>
                @endif

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
                    <!-- Soumettre la planification: n'afficher le bouton que si la planification est complète et il y a au moins un plan -->
                    @if($recommandation->peutEtreSoumiseParPointFocal())
                        <form method="POST"
                            action="{{ route('point_focal.recommandations.soumettre_planification', $recommandation) }}"
                            onsubmit="return confirm('Soumettre la planification au responsable ? Cela verrouillera l\'édition jusqu\'à réponse.');">
                            @csrf
                            <button type="submit"
                                class="flex items-center px-4 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                <i class="mr-2 fas fa-paper-plane"></i>
                                Soumettre la planification
                            </button>
                        </form>
                    @else
                        <button disabled class="flex items-center px-4 py-2 text-white bg-indigo-300 rounded-lg cursor-not-allowed"
                            title="Remplissez les informations de planification et créez au moins une action pour soumettre">
                            <i class="mr-2 fas fa-paper-plane"></i>
                            Soumettre la planification
                        </button>
                    @endif
                @endif
            </div>
        </div>

        <!-- Banner when rejected by Responsable -->
        @if(!empty($recommandation->motif_rejet_responsable))
            <div class="mb-6 p-4 rounded-lg shadow-sm bg-yellow-50 border border-yellow-200">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <i class="mt-1 text-yellow-600 fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <strong class="text-yellow-900 text-lg">Rejeté par le Responsable</strong>
                                <p class="mt-1 text-sm text-yellow-800">Le responsable a demandé des modifications — corrigez
                                    les éléments demandés puis soumettez de nouveau la planification.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('point_focal.recommandations.edit', $recommandation) }}"
                                    class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                    <i class="mr-2 fas fa-edit"></i>Modifier les informations
                                </a>
                            </div>
                        </div>

                        <div
                            class="mt-3 p-3 text-sm bg-white border border-yellow-200 rounded text-yellow-800 whitespace-pre-line">
                            {{ $recommandation->motif_rejet_responsable }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            @php
                // Utiliser le motif au niveau RECOMMANDATION (pas au niveau plan)
                $globalMotifResponsable = $recommandation->motif_rejet_responsable;
                // Priorité au motif stocké sur la recommandation (motif_rejet_ig), sinon tomber back sur le premier motif trouvé au niveau des plans
                $globalMotifIG = $recommandation->motif_rejet_ig ?: $recommandation->plansAction->pluck('motif_rejet_ig')->filter()->first();
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
                            <p
                                class="text-gray-900 {{ $recommandation->estEnRetard() ? 'text-red-600 font-semibold' : '' }}">
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
                    <div class="p-6 border rounded-lg shadow-sm bg-amber-50 border-amber-200">
                        <div class="flex items-start">
                            <i class="mt-1 mr-3 text-amber-500 fas fa-info-circle"></i>
                            <div>
                                <h3 class="font-semibold text-amber-900">Informations de planification manquantes</h3>
                                <p class="mt-1 text-sm text-amber-800">
                                    Vous devez d'abord remplir les informations de planification (indicateur, incidence, délai,
                                    dates)
                                    avant de créer des actions.
                                </p>
                                @if(in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction', 'plan_rejete_responsable']))
                                    <a href="{{ route('point_focal.recommandations.edit', $recommandation) }}"
                                        class="inline-block mt-2 font-medium text-blue-600 hover:text-blue-800">
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
                        @if(in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction', 'plan_rejete_responsable']))
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
                                                    @php
                                                        $recStatut = optional($plan->recommandation)->statut;
                                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                                        if (in_array($recStatut, ['plan_valide_ig', 'plan_valide_responsable'])) {
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                        } elseif (in_array($recStatut, ['plan_rejete_ig', 'plan_rejete_responsable'])) {
                                                            $statusClass = 'bg-red-100 text-red-800';
                                                        }
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                        {{ $plan->statut_validation_label }}
                                                    </span>
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                                                                                        {{ $plan->statut_execution == 'termine' ? 'bg-green-100 text-green-800' :
                                ($plan->statut_execution == 'en_cours' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                        {{ $plan->statut_execution_label }}
                                                    </span>
                                                </div>
                                            </div>

                                            <p class="mb-3 text-gray-700">{{ $plan->action }}</p>

                                            <!-- Motif de rejet responsable affiché au niveau global (colonne de droite) -->

                                            <!-- Motif de rejet IG (également affiché au niveau global si présent) -->
                                            @if(!empty($plan->motif_rejet_ig))
                                                <div class="p-3 mb-3 text-sm text-red-700 border border-red-300 rounded bg-red-50">
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
                                            @if(in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction', 'plan_rejete_responsable']))
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

                                                    <form id="delete-form-{{ $plan->id }}"
                                                        action="{{ route('point_focal.plans_action.destroy', $plan) }}" method="POST"
                                                        style="display:none;">
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
                            @if(in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction', 'plan_rejete_responsable']))
                                <a href="{{ route('point_focal.plans_action.create', $recommandation) }}"
                                    class="inline-block mt-2 text-blue-600 hover:text-blue-800">
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
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $recommandation->statut_color }}">
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
                                'plan_rejete_responsable' => 'Rejeté par le Responsable',
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
                                <div
                                    class="w-3 h-3 rounded-full {{ $recommandation->statut == $etape ? 'bg-blue-500' : 'bg-gray-300' }} mr-2">
                                </div>
                                <span
                                    class="text-sm {{ $recommandation->statut == $etape ? 'text-blue-600 font-medium' : 'text-gray-500' }}">
                                    {{ $label }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action de rappel -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Rappel</h2>
                    <p class="mb-4 text-sm text-gray-600">Envoyer un rappel aux autres acteurs pour attirer leur attention.
                    </p>
                    <button type="button" onclick="document.getElementById('modal-rappel').classList.remove('hidden')"
                        class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-amber-600 rounded-lg hover:bg-amber-700 transition-colors">
                        <i class="mr-2 fas fa-bell"></i>
                        Envoyer un rappel
                    </button>
                </div>

                <!-- Pièces jointes (Documents de la recommandation) -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Pièces Jointes</h2>
                    @if($recommandation->documents && $recommandation->documents->count() > 0)
                        <div class="space-y-3">
                            @foreach($recommandation->documents as $doc)
                                <div
                                    class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors group">
                                    <div class="flex items-center min-w-0">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded bg-blue-50 text-blue-600 mr-3">
                                            @php
                                                $ext = pathinfo($doc->file_path, PATHINFO_EXTENSION);
                                                $icon = match (strtolower($ext)) {
                                                    'pdf' => 'fa-file-pdf',
                                                    'doc', 'docx' => 'fa-file-word',
                                                    'xls', 'xlsx' => 'fa-file-excel',
                                                    'jpg', 'jpeg', 'png' => 'fa-file-image',
                                                    default => 'fa-file',
                                                };
                                            @endphp
                                            <i class="fas {{ $icon }}"></i>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-sm font-medium text-gray-900 truncate" title="{{ $doc->file_name }}">
                                                {{ $doc->file_name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $doc->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('its.recommandations.download', $doc) }}"
                                        class="p-2 text-gray-400 hover:text-blue-600 transition-colors" title="Télécharger">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                            <i class="fas fa-folder-open text-gray-300 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500">Aucune pièce jointe</p>
                        </div>
                    @endif
                </div>

                <!-- Modal de Rappel -->
                <div id="modal-rappel"
                    class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
                    <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-2xl mx-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Envoyer un rappel</h3>
                            <button onclick="document.getElementById('modal-rappel').classList.add('hidden')"
                                class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <form action="{{ route('point_focal.recommandations.rappel', $recommandation) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-medium text-gray-700">Destinataire</label>
                                <select name="destinataire" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="its">ITS ({{ $recommandation->its->name ?? 'Non assigné' }})</option>
                                    <option value="responsable">Responsable</option>
                                    <option value="inspecteur_general">Inspecteur Général</option>
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="block mb-2 text-sm font-medium text-gray-700">Message (Optionnel)</label>
                                <textarea name="message" rows="3" placeholder="Ex: Merci de valider la planification..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button"
                                    onclick="document.getElementById('modal-rappel').classList.add('hidden')"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                    Annuler
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                    Envoyer le rappel
                                </button>
                            </div>
                        </form>
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
                    <div class="p-6 rounded-lg shadow-sm bg-yellow-50 border border-yellow-200">
                        <h2 class="flex items-center gap-2 mb-2 text-lg font-semibold text-yellow-900">
                            <i class="fas fa-exclamation-triangle"></i>
                            Modifications demandées (Responsable)
                        </h2>
                        <p class="mb-3 text-sm italic text-yellow-800">La recommandation a été rejetée. Veuillez corriger votre
                            contribution (planification et/ou plans d'action) selon les indications ci-dessous :</p>
                        <p class="p-3 text-yellow-800 whitespace-pre-line bg-white border border-yellow-200 rounded">
                            {{ $globalMotifResponsable }}
                        </p>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('point_focal.recommandations.edit', $recommandation) }}"
                                class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                <i class="mr-2 fas fa-edit"></i>Modifier les informations
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Motif global de l'IG -->
                @if($globalMotifIG)
                    <div class="p-6 border-l-4 border-red-400 rounded-lg shadow-sm bg-red-50">
                        <h2 class="flex items-center gap-2 mb-2 text-lg font-semibold text-red-900">
                            <i class="fas fa-times-circle"></i>
                            Modifications demandées (Inspecteur Général)
                        </h2>
                        <p class="mb-3 text-sm italic text-red-800">L'Inspecteur Général a demandé des modifications. Veuillez
                            corriger votre contribution selon les indications suivantes :</p>
                        <p class="p-3 text-red-800 whitespace-pre-line bg-white border border-red-200 rounded">
                            {{ $globalMotifIG }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection