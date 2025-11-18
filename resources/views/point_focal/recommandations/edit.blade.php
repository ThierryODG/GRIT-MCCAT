{{-- Formulaire d'édition de recommandation par le point focal --}}
@extends('layouts.app')

@section('title', 'Compléter la recommandation - ' . $recommandation->reference)

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- Navigation -->
    <div class="mb-6">
        <a href="{{ route('point_focal.recommandations.show', $recommandation) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="mr-2 fas fa-arrow-left"></i>
            Retour à la recommandation
        </a>
    </div>

    <!-- En-tête -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Compléter les informations de planification</h1>
        <p class="mt-1 text-gray-600">Recommandation : <strong>{{ $recommandation->reference }}</strong></p>
        <p class="text-gray-500">{{ $recommandation->titre }}</p>
    </div>

    <div class="max-w-4xl">
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <form method="POST" action="{{ route('point_focal.recommandations.update', $recommandation) }}">
                @csrf
                @method('PUT')

                <!-- Contexte et informations existantes -->
                <div class="p-4 mb-6 bg-blue-50 border border-blue-200 rounded-lg">
                    <h3 class="font-semibold text-blue-900">Information préalable</h3>
                    <p class="mt-1 text-sm text-blue-800">
                        L'ITS a défini une <strong>date limite</strong> de <strong>{{ $recommandation->date_limite->format('d/m/Y') }}</strong>
                        pour cette recommandation. Vous devez maintenant planifier comment l'exécuter et créer les actions correspondantes.
                    </p>
                </div>

                <!-- Section 1 : Indicateurs de résultat (UN SEUL) -->
                <div class="mb-8">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">1. Indicateur de résultat</h2>
                    <p class="mb-4 text-sm text-gray-600">
                        Définissez le seul indicateur qui mesurera la réussite de cette recommandation.
                    </p>
                    <textarea name="indicateurs" id="indicateurs" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                        @error('indicateurs') border-red-500 @enderror"
                        placeholder="Ex: Rapport d'audit produit et approuvé par l'IG, processus mis en place et documenté..."
                        required>{{ old('indicateurs', $recommandation->indicateurs) }}</textarea>
                    @error('indicateurs')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section 2 : Incidence financière (UN SEUL) -->
                <div class="mb-8">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">2. Incidence financière</h2>
                    <p class="mb-4 text-sm text-gray-600">
                        Évaluez le coût ou l'impact financier global de l'exécution de cette recommandation.
                    </p>
                    <select name="incidence_financiere" id="incidence_financiere"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                        @error('incidence_financiere') border-red-500 @enderror"
                        required>
                        <option value="">Sélectionnez un niveau...</option>
                        <option value="faible" {{ old('incidence_financiere', $recommandation->incidence_financiere) == 'faible' ? 'selected' : '' }}>
                            Faible (< 1 million)
                        </option>
                        <option value="moyen" {{ old('incidence_financiere', $recommandation->incidence_financiere) == 'moyen' ? 'selected' : '' }}>
                            Moyen (1-10 millions)
                        </option>
                        <option value="eleve" {{ old('incidence_financiere', $recommandation->incidence_financiere) == 'eleve' ? 'selected' : '' }}>
                            Élevé (> 10 millions)
                        </option>
                    </select>
                    @error('incidence_financiere')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section 3 : Délai et dates de planification -->
                <div class="mb-8">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">3. Planification temporelle</h2>
                    <p class="mb-4 text-sm text-gray-600">
                        Définissez le délai total et les dates prévues pour réaliser cette recommandation.
                    </p>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <!-- Délai en mois -->
                        <div>
                            <label for="delai_mois" class="block mb-2 text-sm font-medium text-gray-700">
                                Délai total (mois) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="delai_mois" id="delai_mois" min="0" max="60"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                @error('delai_mois') border-red-500 @enderror"
                                placeholder="Ex: 6"
                                value="{{ old('delai_mois', $recommandation->delai_mois) }}" required>
                            @error('delai_mois')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de début prévue -->
                        <div>
                            <label for="date_debut_prevue" class="block mb-2 text-sm font-medium text-gray-700">
                                Date de début prévue <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date_debut_prevue" id="date_debut_prevue"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                @error('date_debut_prevue') border-red-500 @enderror"
                                value="{{ old('date_debut_prevue', $recommandation->date_debut_prevue?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                            @error('date_debut_prevue')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de fin prévue -->
                        <div>
                            <label for="date_fin_prevue" class="block mb-2 text-sm font-medium text-gray-700">
                                Date de fin prévue <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date_fin_prevue" id="date_fin_prevue"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                @error('date_fin_prevue') border-red-500 @enderror"
                                value="{{ old('date_fin_prevue', $recommandation->date_fin_prevue?->format('Y-m-d')) }}" required>
                            @error('date_fin_prevue')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Vérification de cohérence -->
                    <div class="p-3 mt-4 text-sm bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-amber-900">
                            <strong>Important :</strong> La date de fin prévue ne doit pas dépasser la date limite de <strong>{{ $recommandation->date_limite->format('d/m/Y') }}</strong>.
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end pt-6 space-x-4 border-t border-gray-200">
                    <a href="{{ route('point_focal.recommandations.show', $recommandation) }}"
                       class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit"
                            class="flex items-center px-6 py-3 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        <i class="mr-2 fas fa-save"></i>
                        Sauvegarder et continuer
                    </button>
                </div>
            </form>
        </div>

        <!-- Conseil -->
        <div class="p-6 mt-6 bg-green-50 border border-green-200 rounded-lg">
            <h3 class="font-semibold text-green-900">Prochaine étape</h3>
            <p class="mt-2 text-sm text-green-800">
                Une fois ces informations renseignées et sauvegardées, vous pourrez créer les <strong>actions ou mesures à prendre</strong>
                pour réaliser cette recommandation. Vous pouvez en créer plusieurs.
            </p>
        </div>
    </div>
</div>

<script>
// Auto-calcul de la date de fin en fonction du délai
document.getElementById('delai_mois').addEventListener('change', function() {
    const delaiMois = parseInt(this.value);
    const dateDebut = document.getElementById('date_debut_prevue').value;

    if (delaiMois > 0 && dateDebut) {
        const dateDebutObj = new Date(dateDebut);
        const dateFinObj = new Date(dateDebutObj);
        dateFinObj.setMonth(dateFinObj.getMonth() + delaiMois);

        const dateFinFormatted = dateFinObj.toISOString().split('T')[0];
        document.getElementById('date_fin_prevue').value = dateFinFormatted;
    }
});

// Auto-calcul lors du changement de date de début
document.getElementById('date_debut_prevue').addEventListener('change', function() {
    const delaiMois = parseInt(document.getElementById('delai_mois').value);
    if (delaiMois > 0 && this.value) {
        const dateDebutObj = new Date(this.value);
        const dateFinObj = new Date(dateDebutObj);
        dateFinObj.setMonth(dateFinObj.getMonth() + delaiMois);

        const dateFinFormatted = dateFinObj.toISOString().split('T')[0];
        document.getElementById('date_fin_prevue').value = dateFinFormatted;
    }
});
</script>
@endsection
