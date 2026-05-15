@extends('layouts.app')

@section('title', $recommandation->reference . ' - Validation')

@section('content')
    <div class="container px-4 py-6 mx-auto">
        <!-- En-tête avec navigation -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('responsable.validation_plans.index') }}"
                    class="inline-flex items-center mb-2 text-blue-600 hover:text-blue-800">
                    <i class="mr-2 fas fa-arrow-left"></i>
                    Retour à la liste
                </a>
                <h1 class="text-2xl font-bold text-gray-900">{{ $recommandation->reference }}</h1>
                <p class="mt-1 text-gray-600">{{ $recommandation->titre }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
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
                            <label class="text-sm font-medium text-gray-500">Point Focal</label>
                            <p class="text-gray-900">{{ $recommandation->pointFocal->name }}</p>
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
                    </div>
                </div>

                <!-- Description -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Description</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $recommandation->description }}</p>
                </div>

                <!-- Pièces jointes (Documents de la recommandation) -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Pièces Jointes</h2>
                    @if($recommandation->documents && $recommandation->documents->count() > 0)
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
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
                @endif

                <!-- Plans d'action -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Plans d'action</h2>

                    @if($recommandation->plansAction->count() > 0)
                        <div class="space-y-4">
                            @foreach($recommandation->plansAction as $plan)
                                <div class="p-4 border-2 border-gray-200 rounded-lg bg-gray-50">
                                    <div class="flex items-start justify-between mb-2">
                                        <h3 class="font-medium text-gray-900">Action #{{ $loop->iteration }}</h3>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $plan->statut_validation_label }}
                                        </span>
                                    </div>

                                    <p class="mb-3 text-gray-700 whitespace-pre-line">{{ $plan->action }}</p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                        <div class="text-sm">
                                            <span class="block font-medium text-gray-500 italic">Exécutant :</span>
                                            @if($plan->executant_type === 'autre')
                                                <p class="text-gray-900">
                                                    <i class="fas fa-user-tie mr-1 text-gray-400"></i>
                                                    {{ $plan->executant_nom }}
                                                    @if($plan->executant_role)
                                                        <span class="text-xs text-gray-500">({{ $plan->executant_role }})</span>
                                                    @endif
                                                </p>
                                            @else
                                                <p class="text-gray-900">
                                                    <i class="fas fa-user-check mr-1 text-gray-400"></i>
                                                    Point Focal ({{ $recommandation->pointFocal->name ?? 'Moi-même' }})
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-sm">
                                            <span class="block font-medium text-gray-500 italic">Période d'exécution :</span>
                                            <p class="text-gray-900">
                                                <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>
                                                Du {{ $plan->date_debut_prevue ? $plan->date_debut_prevue->format('d/m/Y') : '?' }}
                                                au {{ $plan->date_fin_prevue ? $plan->date_fin_prevue->format('d/m/Y') : '?' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div
                                        class="text-xs border-t border-gray-200 pt-2 flex justify-between items-center bg-white/50 -mx-4 -mb-4 px-4 py-2 rounded-b-lg mt-2">
                                        <div class="text-gray-500">
                                            <strong>Soumis le :</strong> {{ $plan->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center text-gray-500">
                            <p>Aucun plan d'action</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Colonne latérale - Validation -->
            <div class="space-y-6">
                <!-- Statut actuel -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Statut de la recommandation</h2>

                    <div class="mb-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $recommandation->statut_color }}">
                            {{ $recommandation->statut_label }}
                        </span>
                    </div>

                    <!-- Affichage selon le statut -->
                    @if($estEnAttente)
                        <!-- EN ATTENTE DE VALIDATION -->
                        @if($estComplete)
                            <!-- Valider -->
                            <form method="POST"
                                action="{{ route('responsable.validation_plans.valider_recommandation', $recommandation) }}"
                                class="mb-4">
                                @csrf
                                <div class="mb-4">
                                    <label for="commentaire" class="block mb-2 text-sm font-medium text-gray-700">
                                        Observations (optionnel)
                                    </label>
                                    <textarea name="commentaire" id="commentaire" rows="3"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                        placeholder="Ajoutez vos observations sur l'ensemble de la recommandation..."></textarea>
                                </div>
                                <button type="submit"
                                    onclick="return confirm('Valider cette recommandation ? Elle sera automatiquement transmise à l\'Inspecteur Général.')"
                                    class="w-full px-4 py-2 font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                                    <i class="mr-2 fas fa-check"></i>
                                    Valider et transmettre à l'IG
                                </button>
                            </form>

                            <!-- Rejeter -->
                            <form method="POST"
                                action="{{ route('responsable.validation_plans.rejeter_recommandation', $recommandation) }}"
                                class="mb-4">
                                @csrf
                                <div class="mb-4">
                                    <label for="motif" class="block mb-2 text-sm font-medium text-gray-700">
                                        Raison du rejet <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="motif" id="motif" rows="3"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                                        placeholder="Expliquez les corrections nécessaires pour l'ensemble de la recommandation..."
                                        required></textarea>
                                </div>
                                <button type="submit"
                                    onclick="return confirm('Rejeter cette recommandation ? Elle retournera au point focal pour correction.')"
                                    class="w-full px-4 py-2 font-medium text-white transition bg-red-600 rounded-lg hover:bg-red-700">
                                    <i class="mr-2 fas fa-times"></i>
                                    Rejeter (retour au point focal)
                                </button>
                            </form>
                        @else
                            <!-- INCOMPLETE -->
                            <div class="p-4 text-sm text-yellow-800 border border-yellow-200 rounded-lg bg-yellow-50">
                                <p class="mb-2 font-semibold">Cette recommandation est incomplète</p>
                                <p>Avant validation, le Point Focal doit compléter :</p>
                                <ul class="mt-2 space-y-1 text-xs list-disc list-inside">
                                    @if(!$recommandation->indicateurs)
                                    <li>Indicateur de résultat</li>@endif
                                    @if(!$recommandation->incidence_financiere)
                                    <li>Incidence financière</li>@endif
                                    @if(!$recommandation->delai_mois)
                                    <li>Délai total en mois</li>@endif
                                    @if(!$recommandation->date_debut_prevue)
                                    <li>Date de début prévue</li>@endif
                                    @if(!$recommandation->date_fin_prevue)
                                    <li>Date de fin prévue</li>@endif
                                    @if($recommandation->plansAction->whereNotNull('action')->count() === 0)
                                    <li>Au moins un plan d'action avec description</li>@endif
                                </ul>
                            </div>
                        @endif

                    @elseif($estValidee || $estSoumiseIG)
                        <!-- DÉJÀ VALIDÉE OU SOUMISE À L'IG -->
                        <div class="p-4 border border-green-200 rounded-lg bg-green-50">
                            <div class="flex items-center mb-2">
                                <i class="mr-2 text-green-600 fas fa-check-circle"></i>
                                <h3 class="font-semibold text-green-800">
                                    @if($estValidee)
                                        Recommandation validée
                                    @else
                                        Soumise à l'Inspecteur Général
                                    @endif
                                </h3>
                            </div>

                            @if($recommandation->date_validation_responsable_formatee)
                                <p class="text-sm text-green-700">
                                    <strong>Validé le :</strong> {{ $recommandation->date_validation_responsable_formatee }}
                                </p>
                            @endif

                            @if($recommandation->commentaire_validation_responsable)
                                <div class="p-3 mt-3 bg-white border border-green-100 rounded">
                                    <p class="text-sm text-gray-700 whitespace-pre-line">
                                        {{ $recommandation->commentaire_validation_responsable }}
                                    </p>
                                </div>
                            @endif

                            <p class="mt-3 text-xs italic text-green-600">
                                Cette recommandation a été traitée. Aucune action supplémentaire n'est possible.
                            </p>
                        </div>

                    @elseif($estRejetee)
                        <!-- REJETÉE -->
                        <div class="p-4 border border-yellow-200 rounded-lg bg-yellow-50">
                            <div class="flex items-center mb-2">
                                <i class="mr-2 text-yellow-600 fas fa-exclamation-triangle"></i>
                                <h3 class="font-semibold text-yellow-800">Recommandation rejetée</h3>
                            </div>

                            @if($recommandation->date_rejet_responsable_formatee)
                                <p class="text-sm text-yellow-700">
                                    <strong>Rejeté le :</strong> {{ $recommandation->date_rejet_responsable_formatee }}
                                </p>
                            @endif

                            @if($recommandation->motif_rejet_responsable)
                                <div class="p-3 mt-3 bg-white border border-yellow-100 rounded">
                                    <p class="text-sm text-gray-700 whitespace-pre-line">
                                        {{ $recommandation->motif_rejet_responsable }}
                                    </p>
                                </div>
                            @endif

                            @if($recommandation->motif_rejet_ig)
                                <div class="p-3 mt-3 bg-white border border-red-100 rounded">
                                    <h4 class="mb-1 text-sm font-medium text-red-700">Motif de rejet (Inspecteur Général)</h4>
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $recommandation->motif_rejet_ig }}</p>
                                </div>
                            @endif

                            <p class="mt-3 text-xs italic text-yellow-600">
                                Le point focal doit corriger et resoumettre cette recommandation.
                            </p>

                            <!-- Bouton pour transmettre au point focal si la recommandation a été rejetée par l'IG -->
                            @if($recommandation->statut == 'plan_rejete_ig')
                                <form method="POST"
                                    action="{{ route('responsable.validation_plans.transmettre_pf', $recommandation) }}"
                                    class="mt-4" onsubmit="return confirm('Transmettre les corrections au Point Focal ?');">
                                    @csrf
                                    <button type="submit"
                                        class="w-full px-4 py-2 font-medium text-white transition bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                        <i class="mr-2 fas fa-share"></i>
                                        Transmettre au Point Focal
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Aide -->
                <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                    <h3 class="mb-2 font-semibold text-blue-900">
                        <i class="mr-2 fas fa-info-circle"></i>
                        Guide de validation
                    </h3>
                    <ul class="text-xs text-blue-800 space-y-1.5">
                        <li>✓ <strong>Valider :</strong> Transmet automatiquement à l'IG</li>
                        <li>✗ <strong>Rejeter :</strong> Retourne au point focal pour correction</li>
                        <li>📝 <strong>Commentaire :</strong> Optionnel pour validation, obligatoire pour rejet</li>
                        <li><em>Note :</em> La validation porte sur l'ensemble de la recommandation</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection