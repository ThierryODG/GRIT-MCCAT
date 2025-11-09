@extends('layouts.app')


@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span class="ml-1 text-gray-700">Rapports - Générer</span>
        </div>
    </li>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Générer un Rapport</h2>
                    <p class="text-gray-600 mt-2">Sélectionnez les critères pour votre rapport personnalisé</p>
                </div>

                <form method="POST" action="{{ route('its.rapports.generate') }}" class="space-y-6">
                    @csrf

                    <!-- Type de rapport -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type de Rapport *</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="flex items-center p-4 border-2 border-blue-200 rounded-lg cursor-pointer hover:bg-blue-50">
                                <input type="radio" name="type_rapport" value="statistiques"
                                       class="text-blue-600 focus:ring-blue-500"
                                       {{ (request('type') == 'statistiques' || old('type_rapport') == 'statistiques') ? 'checked' : '' }} required>
                                <div class="ml-3">
                                    <span class="block text-sm font-medium text-blue-800">Statistiques</span>
                                    <span class="block text-sm text-blue-600">Vue d'ensemble avec graphiques</span>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border-2 border-green-200 rounded-lg cursor-pointer hover:bg-green-50">
                                <input type="radio" name="type_rapport" value="details"
                                       class="text-green-600 focus:ring-green-500"
                                       {{ (request('type') == 'details' || old('type_rapport') == 'details') ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <span class="block text-sm font-medium text-green-800">Détaillé</span>
                                    <span class="block text-sm text-green-600">Liste complète avec filtres</span>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border-2 border-purple-200 rounded-lg cursor-pointer hover:bg-purple-50">
                                <input type="radio" name="type_rapport" value="cloture"
                                       class="text-purple-600 focus:ring-purple-500"
                                       {{ (request('type') == 'cloture' || old('type_rapport') == 'cloture') ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <span class="block text-sm font-medium text-purple-800">Clôture</span>
                                    <span class="block text-sm text-purple-600">Recommandations clôturées</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Période -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début *</label>
                            <input type="date" id="date_debut" name="date_debut"
                                   value="{{ old('date_debut', date('Y-m-01')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin *</label>
                            <input type="date" id="date_fin" name="date_fin"
                                   value="{{ old('date_fin', date('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                    </div>

                    <!-- Filtres supplémentaires -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                            <select id="statut" name="statut" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Tous les statuts</option>
                                <option value="brouillon" {{ old('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                <option value="soumise" {{ old('statut') == 'soumise' ? 'selected' : '' }}>Soumise</option>
                                <option value="validee_ig" {{ old('statut') == 'validee_ig' ? 'selected' : '' }}>Validée IG</option>
                                <option value="rejetee_ig" {{ old('statut') == 'rejetee_ig' ? 'selected' : '' }}>Rejetée IG</option>
                                <option value="en_analyse_structure" {{ old('statut') == 'en_analyse_structure' ? 'selected' : '' }}>En analyse structure</option>
                                <option value="plan_action_soumis" {{ old('statut') == 'plan_action_soumis' ? 'selected' : '' }}>Plan action soumis</option>
                                <option value="plan_action_valide" {{ old('statut') == 'plan_action_valide' ? 'selected' : '' }}>Plan action validé</option>
                                <option value="plan_action_rejete" {{ old('statut') == 'plan_action_rejete' ? 'selected' : '' }}>Plan action rejeté</option>
                                <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="terminee" {{ old('statut') == 'terminee' ? 'selected' : '' }}>Terminée</option>
                                <option value="cloturee" {{ old('statut') == 'cloturee' ? 'selected' : '' }}>Clôturée</option>
                            </select>
                        </div>
                        <div>
                            <label for="priorite" class="block text-sm font-medium text-gray-700">Priorité</label>
                            <select id="priorite" name="priorite" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Toutes les priorités</option>
                                <option value="haute" {{ old('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                                <option value="moyenne" {{ old('priorite') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                                <option value="basse" {{ old('priorite') == 'basse' ? 'selected' : '' }}>Basse</option>
                            </select>
                        </div>
                    </div>

                    <!-- Options de format -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Format de sortie</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="format" value="html" checked
                                       class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">HTML (Navigateur)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="format" value="pdf"
                                       class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">PDF</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="format" value="excel"
                                       class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Excel</span>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('its.rapports.index') }}"
                           class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition duration-200">
                            Annuler
                        </a>
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                            Générer le Rapport
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');

    // Validation des dates
    dateFin.addEventListener('change', function() {
        if (dateDebut.value && dateFin.value < dateDebut.value) {
            alert('La date de fin doit être postérieure à la date de début');
            dateFin.value = dateDebut.value;
        }
    });
});
</script>
@endsection
