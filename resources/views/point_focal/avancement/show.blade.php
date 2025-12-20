@extends('layouts.app')

@section('title', 'Exécution de la Recommandation')

@section('content')
    <div class="container mx-auto px-4 py-6" x-data="executionStepper()">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Exécution : {{ $recommandation->reference }}
                </h1>
                <p class="text-gray-600">{{Str::limit($recommandation->titre, 80)}}</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <span class="block text-sm text-gray-500">Progression Globale</span>
                    <span class="text-xl font-bold text-blue-600" x-text="globalProgress + '%'"></span>
                </div>
                <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 transition-all duration-500" :style="'width: ' + globalProgress + '%'">
                    </div>
                </div>

                <!-- Bouton Clôture (Visible si 100%) -->
                <div x-show="globalProgress >= 100" style="display: none;" class="ml-4">
                    <form action="{{ route('point_focal.avancement.cloture', $recommandation) }}" method="POST"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir demander la clôture de ce dossier ?\n\nCette action informera le Responsable et l\'Inspecteur Général.');">
                        @csrf
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Demander la Clôture
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-200px)]">
            <!-- Sidebar Steps -->
            <div class="w-full lg:w-1/4 bg-white rounded-xl shadow-sm border border-gray-100 overflow-y-auto">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-700">Actions à mener</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $recommandation->plansAction->count() }} étapes</p>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($recommandation->plansAction as $index => $action)
                        <button @click="currentStep = {{ $index }}"
                            class="w-full text-left p-4 hover:bg-gray-50 transition-colors relative group"
                            :class="{'bg-blue-50 border-l-4 border-blue-600': currentStep === {{ $index }}, 'border-l-4 border-transparent': currentStep !== {{ $index }}}">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-1">
                                    <!-- Status Icon -->
                                    <span x-show="actions[{{ $index }}].statut_execution === 'termine'" class="text-green-500">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <span x-show="actions[{{ $index }}].statut_execution === 'en_cours'"
                                        class="text-orange-500">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <span
                                        x-show="actions[{{ $index }}].statut_execution === 'non_demarre' || !actions[{{ $index }}].statut_execution"
                                        class="text-gray-300">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Étape
                                        {{ $index + 1 }}</span>
                                    <h4 class="text-sm font-medium text-gray-800 line-clamp-2">{{ $action->action }}</h4>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Main Content -->
            <div class="w-full lg:w-3/4 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
                <template x-for="(action, index) in actions" :key="action.id">
                    <div x-show="currentStep === index" class="p-8 flex-1 flex flex-col h-full overflow-y-auto">
                        <!-- Action Details -->
                        <div class="mb-8">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-4">
                                Action #<span x-text="index + 1"></span>
                            </span>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4" x-text="action.action"></h2>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-lg border border-gray-100">
                                <div>
                                    <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Indicateurs
                                    </h5>
                                    <p class="text-gray-700" x-text="action.indicateurs || 'Non défini'"></p>
                                </div>
                                <div>
                                    <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Échéance</h5>
                                    <p class="text-gray-700"
                                        x-text="action.date_fin_prevue ? new Date(action.date_fin_prevue).toLocaleDateString() : 'Non définie'">
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Execution Form -->
                        <div class="mt-auto border-t border-gray-100 pt-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Mise à jour de l'exécution</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire
                                        d'exécution</label>
                                    <textarea x-model="action.commentaire_avancement" rows="4"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Décrivez les actions réalisées..."></textarea>
                                </div>

                                <div class="flex items-center justify-between pt-4">
                                    <div class="flex items-center gap-4">
                                        <span class="text-sm text-gray-600">Statut actuel :</span>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium" :class="{
                                                            'bg-green-100 text-green-800': action.statut_execution === 'termine',
                                                            'bg-orange-100 text-orange-800': action.statut_execution === 'en_cours',
                                                            'bg-gray-100 text-gray-800': !action.statut_execution || action.statut_execution === 'non_demarre'
                                                        }" x-text="formatStatus(action.statut_execution)"></span>
                                    </div>

                                    <div class="flex gap-3">
                                        <button @click="updateAction(index, 'en_cours')"
                                            x-show="action.statut_execution !== 'termine'"
                                            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition shadow-sm">
                                            Enregistrer (En cours)
                                        </button>

                                        <button @click="updateAction(index, 'termine')"
                                            :class="action.statut_execution === 'termine' ? 'bg-green-600 text-white cursor-default' : 'bg-blue-600 text-white hover:bg-blue-700'"
                                            class="px-4 py-2 rounded-lg transition shadow-sm flex items-center gap-2"
                                            :disabled="action.statut_execution === 'termine'">
                                            <span x-show="action.statut_execution === 'termine'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </span>
                                            <span
                                                x-text="action.statut_execution === 'termine' ? 'Terminé' : 'Marquer comme terminé'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <script>
        function executionStepper() {
            return {
                currentStep: 0,
                globalProgress: {{ $globalProgress }},
                actions: @json($recommandation->plansAction),

                formatStatus(status) {
                    const map = {
                        'non_demarre': 'Non démarré',
                        'en_cours': 'En cours',
                        'termine': 'Terminé'
                    };
                    return map[status] || 'Non démarré';
                },

                async updateAction(index, status) {
                    const action = this.actions[index];

                    try {
                        const response = await fetch(`/point-focal/avancement/action/${action.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                statut_execution: status,
                                commentaire_avancement: action.commentaire_avancement
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Update local state
                            this.actions[index].statut_execution = status;
                            this.globalProgress = data.global_progress;

                            // Optional: Show notification
                            // alert('Mise à jour effectuée');

                            // If marked as done, maybe move to next step?
                            if (status === 'termine' && index < this.actions.length - 1) {
                                // this.currentStep++; 
                            }
                        }
                    } catch (error) {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la mise à jour.');
                    }
                }
            }
        }
    </script>
    <!-- Modal Rappel -->
    <div id="modal-rappel" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"
                onclick="document.getElementById('modal-rappel').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('point_focal.avancement.rappel', $recommandation) }}" method="POST">
                    @csrf
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-indigo-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                <i class="text-indigo-600 fas fa-bell"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Envoyer un rappel
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">
                                        Envoyez un rappel concernant cette recommandation.
                                    </p>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Destinataire</label>
                                        <select name="destinataire"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            @if($recommandation->responsable)
                                                <option value="responsable">Responsable
                                                    ({{ $recommandation->responsable->name }})</option>
                                            @endif
                                            @if($recommandation->inspecteurGeneral)
                                                <option value="inspecteur_general">Inspecteur Général
                                                    ({{ $recommandation->inspecteurGeneral->name }})</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Message
                                            (Optionnel)</label>
                                        <textarea name="message" rows="3"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Message personnalisé..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Envoyer
                        </button>
                        <button type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            onclick="document.getElementById('modal-rappel').classList.add('hidden')">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection