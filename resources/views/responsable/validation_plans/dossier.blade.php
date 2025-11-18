{{-- resources/views/responsable/validation_plans/dossier.blade.php --}}
@extends('layouts.app')

@section('title', $recommandation->reference . ' - Validation')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- En-tête avec navigation -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('responsable.validation_plans.index') }}" class="inline-flex items-center mb-2 text-blue-600 hover:text-blue-800">
                <i class="mr-2 fas fa-arrow-left"></i>
                Retour à la liste
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $recommandation->reference }}</h1>
            <p class="mt-1 text-gray-600">{{ $recommandation->titre }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        @php
            $isComplete = $recommandation->indicateurs && $recommandation->incidence_financiere && $recommandation->delai_mois
                && $recommandation->date_debut_prevue && $recommandation->date_fin_prevue
                && $recommandation->plansAction->whereNotNull('action')->count() > 0;

            // Utiliser le motif au niveau RECOMMANDATION (pas au niveau plan)
            $globalMotif = $recommandation->motif_rejet_responsable;
            
            // Déterminer si la recommandation est rejetée
            $isRejectedResponsable = $globalMotif !== null;
        @endphp
        
        <!-- Banner d'alerte si rejet au niveau Responsable -->
        @if($isRejectedResponsable)
        <div class="col-span-1 lg:col-span-3 p-4 bg-red-50 border-l-4 border-red-500 rounded text-red-700 flex items-start gap-3">
            <i class="mt-0.5 fas fa-exclamation-circle flex-shrink-0"></i>
            <div>
                <h3 class="font-semibold mb-1">Recommandation rejetée par le Responsable</h3>
                <p class="text-sm">Cette recommandation a été rejetée. Le Point Focal doit corriger les points soulevés et resubmettre l'ensemble de la contribution (planification et plans d'action).</p>
            </div>
        </div>
        @endif
        
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
                        <p class="text-gray-900 {{ $recommandation->estEnRetard() ? 'text-red-600 font-semibold' : '' }}">
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
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Plans d'action à valider</h2>
                <p class="mb-4 text-sm text-gray-600 italic">La validation porte sur l'ensemble de la recommandation (planification + tous les plans). Lorsque rejetée, la recommandation complète doit être revue par le Point Focal.</p>

                @if($recommandation->plansAction->count() > 0)
                <div class="space-y-4">
                    @foreach($recommandation->plansAction as $plan)
                    <div class="p-4 border-2 border-gray-200 bg-gray-50 rounded-lg">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-medium text-gray-900">Action #{{ $loop->iteration }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $plan->statut_validation == 'valide_responsable' ? 'bg-green-100 text-green-800' :
                                   ($plan->statut_validation == 'rejete_responsable' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $plan->statut_validation_label }}
                            </span>
                        </div>

                        <p class="mb-3 text-gray-700">{{ $plan->action }}</p>

                        <!-- (Motif/Commentaire affichés au niveau global dans la colonne de droite) -->

                        <div class="text-sm text-gray-600">
                            <p><strong>Soumis le :</strong> {{ $plan->created_at->format('d/m/Y à H:i') }}</p>
                            @if($plan->date_validation_responsable)
                            <p><strong>Validé le :</strong> {{ $plan->date_validation_responsable->format('d/m/Y à H:i') }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="py-8 text-center text-gray-500">
                    <p>Aucun plan d'action à valider</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Colonne latérale - Validation -->
        <div class="space-y-6">
            <!-- Formulaire de validation -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Validation de la recommandation</h2>
                
                <!-- Statut de rejet global (visible par le Point Focal) -->
                @if($globalMotif)
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex gap-2 mb-2">
                        <i class="mt-0.5 fas fa-times-circle text-red-600 flex-shrink-0"></i>
                        <strong class="text-red-700">Recommandation rejetée</strong>
                    </div>
                    <p class="text-sm text-red-700 whitespace-pre-line bg-white p-2 rounded border border-red-100">{{ $globalMotif }}</p>
                    <p class="mt-2 text-xs text-red-600 italic">Veuillez corriger l'ensemble de votre contribution et resubmettre.</p>
                </div>
                @endif

                @if($isComplete)
                    <!-- Valider -->
                    <form method="POST" action="{{ route('responsable.validation_plans.valider_recommandation', $recommandation) }}" class="mb-4">
                        @csrf
                        <div class="mb-4">
                            <label for="commentaire" class="block mb-2 text-sm font-medium text-gray-700">
                                Observations (optionnel)
                            </label>
                            <textarea name="commentaire" id="commentaire" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                                      placeholder="Ajoutez vos observations sur l'ensemble de la recommandation..."></textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition font-medium">
                            <i class="mr-2 fas fa-check"></i>
                            Valider la recommandation
                        </button>
                    </form>

                    <!-- Rejeter -->
                    <form method="POST" action="{{ route('responsable.validation_plans.rejeter_recommandation', $recommandation) }}" class="mb-4">
                        @csrf
                        <div class="mb-4">
                            <label for="motif" class="block mb-2 text-sm font-medium text-gray-700">
                                Raison du rejet <span class="text-red-500">*</span>
                            </label>
                            <textarea name="motif" id="motif" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm"
                                      placeholder="Expliquez les corrections nécessaires pour l'ensemble de la recommandation..."
                                      required></textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition font-medium">
                            <i class="mr-2 fas fa-times"></i>
                            Rejeter la recommandation
                        </button>
                    </form>

                    <!-- Transmettre à l'IG -->
                    @if($recommandation->plansAction()->where('statut_validation', 'valide_responsable')->exists())
                    <button type="button" onclick="openConfirmModal()" class="w-full px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition font-medium">
                        <i class="mr-2 fas fa-arrow-right"></i>
                        Transmettre à l'IG
                    </button>
                    @endif
                @else
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                        <p class="font-semibold mb-2">Cette recommandation est incomplète</p>
                        <p>Avant validation, le Point Focal doit compléter :</p>
                        <ul class="mt-2 space-y-1 list-disc list-inside text-xs">
                            @if(!$recommandation->indicateurs)<li>Indicateur de résultat</li>@endif
                            @if(!$recommandation->incidence_financiere)<li>Incidence financière</li>@endif
                            @if(!$recommandation->delai_mois)<li>Délai total en mois</li>@endif
                            @if(!$recommandation->date_debut_prevue)<li>Date de début prévue</li>@endif
                            @if(!$recommandation->date_fin_prevue)<li>Date de fin prévue</li>@endif
                            @if($recommandation->plansAction->whereNotNull('action')->count() === 0)<li>Au moins un plan d'action avec description</li>@endif
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Aide -->
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="font-semibold text-blue-900 mb-2">
                    <i class="mr-2 fas fa-info-circle"></i>
                    Guide de validation
                </h3>
                <ul class="text-xs text-blue-800 space-y-1.5">
                    <li>✓ <strong>Valider :</strong> La recommandation complète (planification + plans) est acceptable</li>
                    <li>✗ <strong>Rejeter :</strong> Des corrections sont nécessaires dans la planification ou les plans. Le Point Focal doit revoir l'ensemble.</li>
                    <li>→ <strong>Transmettre :</strong> Une fois validée, envoyer à l'Inspecteur Général</li>
                    <li><em>Note :</em> Validation et rejet s'appliquent à la recommandation entière, pas à des plans individuels.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modale de confirmation pour transmission à l'IG -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity opacity-0 pointer-events-none" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md mx-4 pointer-events-auto">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="mr-2 fas fa-exclamation-triangle text-yellow-500"></i>
                Confirmer la transmission
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-4">
                Êtes-vous sûr de vouloir transmettre cette recommandation à l'Inspecteur Général ?
            </p>
            <p class="text-sm text-gray-600 bg-blue-50 p-3 rounded mb-4">
                <strong>⚠️ Attention :</strong> Une fois transmise, cette action ne peut pas être annulée. L'Inspecteur Général recevra la demande pour validation finale.
            </p>
        </div>
        <div class="flex justify-end gap-3 p-6 border-t border-gray-200">
            <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                Annuler
            </button>
            <form method="POST" action="{{ route('responsable.validation_plans.transmettre_ig', $recommandation) }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    <i class="mr-2 fas fa-check"></i>
                    Oui, transmettre
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function openConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.style.display = 'flex';
    modal.classList.remove('opacity-0', 'pointer-events-none');
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('opacity-0', 'pointer-events-none');
    setTimeout(() => modal.style.display = 'none', 300);
}

// Fermer la modale en cliquant en dehors
document.getElementById('confirmModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfirmModal();
    }
});
</script>
@endsection
