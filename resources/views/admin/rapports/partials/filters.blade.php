<div class="p-6 bg-white rounded-lg shadow">
    <h3 class="mb-4 text-lg font-semibold text-gray-900">Filtres de Rapport</h3>
    <form action="{{ route('admin.rapports.generer') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
        {{-- Date Début --}}
        <div>
            <label for="date_debut" class="block mb-1 text-sm font-medium text-gray-700">Date Début</label>
            <input type="date"
                   name="date_debut"
                   id="date_debut"
                   value="{{ $filters['date_debut'] ?? '' }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Date Fin --}}
        <div>
            <label for="date_fin" class="block mb-1 text-sm font-medium text-gray-700">Date Fin</label>
            <input type="date"
                   name="date_fin"
                   id="date_fin"
                   value="{{ $filters['date_fin'] ?? '' }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Statut --}}
        <div>
            <label for="statut" class="block mb-1 text-sm font-medium text-gray-700">Statut</label>
            <select name="statut"
                    id="statut"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les statuts</option>
                <option value="en_attente_validation" {{ ($filters['statut'] ?? '') == 'en_attente_validation' ? 'selected' : '' }}>En attente validation</option>
                <option value="validee_ig" {{ ($filters['statut'] ?? '') == 'validee_ig' ? 'selected' : '' }}>Validée IG</option>
                <option value="en_analyse_structure" {{ ($filters['statut'] ?? '') == 'en_analyse_structure' ? 'selected' : '' }}>En analyse structure</option>
                <option value="cloturee" {{ ($filters['statut'] ?? '') == 'cloturee' ? 'selected' : '' }}>Clôturée</option>
            </select>
        </div>

        {{-- Priorité --}}
        <div>
            <label for="priorite" class="block mb-1 text-sm font-medium text-gray-700">Priorité</label>
            <select name="priorite"
                    id="priorite"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Toutes les priorités</option>
                <option value="haute" {{ ($filters['priorite'] ?? '') == 'haute' ? 'selected' : '' }}>Haute</option>
                <option value="moyenne" {{ ($filters['priorite'] ?? '') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                <option value="basse" {{ ($filters['priorite'] ?? '') == 'basse' ? 'selected' : '' }}>Basse</option>
            </select>
        </div>

        {{-- Boutons --}}
        <div class="flex items-end space-x-2">
            <button type="submit"
                    class="w-full px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="mr-2 fas fa-filter"></i>Filtrer
            </button>
            <a href="{{ route('admin.rapports.index') }}"
               class="w-full px-4 py-2 text-center text-gray-700 bg-gray-300 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                <i class="mr-2 fas fa-redo"></i>Réinitialiser
            </a>
        </div>
    </form>
</div>
