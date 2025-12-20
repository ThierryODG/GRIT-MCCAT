@extends('layouts.app')

@section('title', 'Validation Plan d\'Action - Inspecteur Général')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Validation du Plan d'Action</h2>
                    <p class="text-gray-600 mt-2">Référence: {{ $planAction->recommandation->reference }}</p>
                </div>

                <!-- Résumé du plan -->
                <div class="bg-blue-50 p-6 rounded-lg mb-6">
                    <h3 class="text-xl font-semibold mb-4">Résumé du Plan d'Action</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                                <p><strong>Titre:</strong> {{ $planAction->recommandation->titre }}</p>
                                <p><strong>Point Focal:</strong> {{ $planAction->pointFocal->name ?? 'N/A' }}</p>
                                <p><strong>Date création:</strong> {{ $planAction->created_at->format('d/m/Y') }}</p>
                            </div>
                        <div>
                            <p><strong>Nombre d'activités:</strong> {{ $planAction->activites->count() }}</p>
                            <p><strong>Avancement global:</strong> {{ $planAction->pourcentage_avancement ?? 0 }}%</p>
                            <p><strong>Statut actuel:</strong>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    En attente de validation
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de validation -->
                <form method="POST" action="{{ route('inspecteur_general.plan_actions.validate', $planAction) }}" class="space-y-6">
                    @csrf

                    <!-- Avis de validation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Votre décision *</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center p-4 border-2 border-green-200 rounded-lg cursor-pointer hover:bg-green-50">
                                <input type="radio" name="action" value="valider" class="text-green-600 focus:ring-green-500" required>
                                <div class="ml-3">
                                    <span class="block text-sm font-medium text-green-800">Valider le plan</span>
                                    <span class="block text-sm text-green-600">Le plan sera approuvé et pourra être exécuté</span>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border-2 border-red-200 rounded-lg cursor-pointer hover:bg-red-50">
                                <input type="radio" name="action" value="rejeter" class="text-red-600 focus:ring-red-500" required>
                                <div class="ml-3">
                                    <span class="block text-sm font-medium text-red-800">Rejeter le plan</span>
                                    <span class="block text-sm text-red-600">Le plan devra être révisé par le point focal</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Commentaire -->
                    <div>
                        <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                            Commentaire (optionnel)
                        </label>
                        <textarea id="commentaire" name="commentaire" rows="4"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Donnez votre avis sur ce plan d'action..."></textarea>
                    </div>

                    <!-- Recommandations -->
                    <div>
                        <label for="recommandations" class="block text-sm font-medium text-gray-700 mb-2">
                            Recommandations (optionnel)
                        </label>
                        <textarea id="recommandations" name="recommandations" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Suggestions d'amélioration..."></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('inspecteur_general.plan_actions.show', $planAction) }}"
                           class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition duration-200">
                            Annuler
                        </a>
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                            Soumettre la décision
                        </button>
                    </div>
                </form>

                <!-- Informations importantes -->
                <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Informations importantes</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>• Une fois validé, le plan d'action sera définitif et ne pourra plus être modifié</p>
                                <p>• En cas de rejet, le point focal sera notifié et devra soumettre une nouvelle version</p>
                                <p>• Votre commentaire sera visible par le point focal et le responsable</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const radioButtons = document.querySelectorAll('input[name="action"]');

    form.addEventListener('submit', function(e) {
        let isChecked = false;
        radioButtons.forEach(radio => {
            if (radio.checked) isChecked = true;
        });

        if (!isChecked) {
            e.preventDefault();
            alert('Veuillez sélectionner une décision (Valider ou Rejeter)');
        }
    });
});
</script>
@endsection
