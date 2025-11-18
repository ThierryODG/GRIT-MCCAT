{{-- resources/views/inspecteur_general/validation_recommandations/dossier.blade.php --}}
@extends('layouts.app')

@section('title', $recommandation->reference . ' - Validation IG')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- En-tête avec navigation -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('inspecteur_general.validation_recommandations.index') }}" class="inline-flex items-center mb-2 text-purple-600 hover:text-purple-800">
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
                        <label class="text-sm font-medium text-gray-500">Responsable</label>
                        <p class="text-gray-900">{{ $recommandation->responsable->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">ITS (Inspecteur)</label>
                        <p class="text-gray-900">{{ $recommandation->its->name }}</p>
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

            <!-- Description de la recommandation -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Description de la recommandation</h2>
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

            <!-- Plans d'action validés par le responsable -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Plans d'action validés par le responsable</h2>

                @if($recommandation->plansAction->count() > 0)
                <div class="space-y-4">
                    @foreach($recommandation->plansAction as $plan)
                    <div class="p-4 border border-gray-200 rounded-lg bg-purple-50">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-medium text-gray-900">Action #{{ $loop->iteration }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                En attente validation IG
                            </span>
                        </div>

                        <p class="mb-3 text-gray-700">{{ $plan->action }}</p>

                        <!-- Commentaire du responsable si disponible -->
                        @if($plan->commentaire_validation_responsable)
                        <div class="p-3 mb-3 text-sm text-blue-700 border border-blue-200 rounded bg-blue-50">
                            <strong>Commentaire du responsable :</strong>
                            <p class="mt-1 whitespace-pre-line">{{ $plan->commentaire_validation_responsable }}</p>
                        </div>
                        @endif

                        <div class="text-sm text-gray-600">
                            <p><strong>Soumis le :</strong> {{ $plan->created_at->format('d/m/Y à H:i') }}</p>
                            <p><strong>Validé responsable le :</strong> {{ $plan->date_validation_responsable->format('d/m/Y à H:i') }}</p>
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

        <!-- Colonne latérale - Validation par l'IG -->
        <div class="space-y-6">
            <!-- Formulaire de validation -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Validation IG</h2>

                <!-- Valider -->
                <form method="POST" action="{{ route('inspecteur_general.validation_recommandations.valider', $recommandation) }}" class="mb-4">
                    @csrf
                    <div class="mb-4">
                        <label for="commentaire" class="block mb-2 text-sm font-medium text-gray-700">
                            Commentaires (optionnel)
                        </label>
                        <textarea name="commentaire" id="commentaire" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                  placeholder="Vos observations avant validation..."></textarea>
                    </div>
                    <button type="button" onclick="openConfirmModal('valider')" class="w-full px-4 py-2 text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                        <i class="mr-2 fas fa-check"></i>
                        Valider & Démarrer
                    </button>
                </form>

                <!-- Rejeter -->
                <form method="POST" action="{{ route('inspecteur_general.validation_recommandations.rejeter', $recommandation) }}" class="mb-4">
                    @csrf
                    <div class="mb-4">
                        <label for="motif" class="block mb-2 text-sm font-medium text-gray-700">
                            Raison du rejet <span class="text-red-500">*</span>
                        </label>
                        <textarea name="motif" id="motif" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                                  placeholder="Expliquez pourquoi ce plan n'est pas acceptable..."
                                  required></textarea>
                    </div>
                    <button type="button" onclick="openConfirmModal('rejeter')" class="w-full px-4 py-2 text-white transition bg-red-600 rounded-lg hover:bg-red-700">
                        <i class="mr-2 fas fa-times"></i>
                        Rejeter
                    </button>
                </form>
            </div>

            <!-- Aide -->
            <div class="p-4 border border-purple-200 rounded-lg bg-purple-50">
                <h3 class="mb-2 font-semibold text-purple-900">
                    <i class="mr-2 fas fa-info-circle"></i>
                    Guide de validation
                </h3>
                <ul class="space-y-1 text-sm text-purple-800">
                    <li>✓ <strong>Valider :</strong> Approuver le plan pour démarrage</li>
                    <li>✗ <strong>Rejeter :</strong> Demander des corrections</li>
                    <li>⚠️ <strong>Attention :</strong> Actions irréversibles</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modale de confirmation -->
<div id="confirmModal" class="fixed inset-0 z-50 items-center justify-center transition-opacity bg-black bg-opacity-50 opacity-0 pointer-events-none" style="display: none;">
    <div class="max-w-md mx-4 bg-white rounded-lg shadow-xl">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="mr-2 text-yellow-500 fas fa-exclamation-triangle"></i>
                Confirmer l'action
            </h3>
        </div>
        <div class="p-6" id="modalMessage">
            <!-- Message remplacé dynamiquement -->
        </div>
        <div class="flex justify-end gap-3 p-6 border-t border-gray-200">
            <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 text-gray-700 transition bg-gray-200 rounded-lg hover:bg-gray-300">
                Annuler
            </button>
            <button type="button" id="confirmBtn" class="px-4 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700" onclick="submitForm()">
                Confirmer
            </button>
        </div>
    </div>
</div>

<script>
let actionType = '';

function openConfirmModal(type) {
    actionType = type;
    const modal = document.getElementById('confirmModal');
    const messageDiv = document.getElementById('modalMessage');
    const confirmBtn = document.getElementById('confirmBtn');

    if (type === 'valider') {
        messageDiv.innerHTML = `<p class="mb-4 text-gray-700">Êtes-vous sûr de vouloir <strong>valider et démarrer l'exécution</strong> de cette recommandation ?</p><p class="p-3 text-sm text-gray-600 rounded bg-green-50"><strong>✓ Important :</strong> Une fois validée, l'exécution et le suivi commenceront immédiatement.</p>`;
        confirmBtn.className = 'px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition';
        confirmBtn.innerHTML = '<i class="mr-2 fas fa-check"></i>Oui, valider';
    } else if (type === 'rejeter') {
        messageDiv.innerHTML = `<p class="mb-4 text-gray-700">Êtes-vous sûr de vouloir <strong>rejeter</strong> cette recommandation ?</p><p class="p-3 text-sm text-gray-600 rounded bg-red-50"><strong>⚠️ Important :</strong> Le responsable sera notifié et devra apporter des corrections.</p>`;
        confirmBtn.className = 'px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition';
        confirmBtn.innerHTML = '<i class="mr-2 fas fa-times"></i>Oui, rejeter';
    }

    modal.style.display = 'flex';
    setTimeout(() => modal.classList.remove('opacity-0'), 10);
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('opacity-0');
    setTimeout(() => modal.style.display = 'none', 300);
}

function submitForm() {
    closeConfirmModal();
    if (actionType === 'valider') {
        document.querySelector('form[action*="valider"]').submit();
    } else if (actionType === 'rejeter') {
        document.querySelector('form[action*="rejeter"]').submit();
    }
}

document.getElementById('confirmModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfirmModal();
    }
});
</script>
@endsection
