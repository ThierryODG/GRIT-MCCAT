@extends('layouts.app')

@section('title', 'Générer un rapport')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto">
            <div class="mb-6">
                <a href="{{ route('point_focal.rapports.index') }}"
                    class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Retour aux rapports
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h1 class="text-xl font-bold text-gray-800">Générer un nouveau rapport</h1>
                    <p class="text-sm text-gray-500 mt-1">Créez un document officiel pour vos recommandations.</p>
                </div>

                <form action="{{ route('point_focal.rapports.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Titre -->
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">Titre du rapport <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="titre" id="titre" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="Ex: Rapport d'exécution - Recommandation #123">
                        @error('titre')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type de rapport <span
                                class="text-red-500">*</span></label>
                        <select name="type" id="type" required readonly
                            class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed shadow-sm">
                            <option value="execution" selected>Rapport d'exécution (Individuel)</option>
                            <!-- Global report temporarily disabled -->
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Pour le moment, seul le rapport d'exécution par recommandation est disponible.</p>
                    </div>

                    <!-- Recommandation (Obligatoire pour rapport d'exécution) -->
                    <div>
                        <label for="recommandation_id" class="block text-sm font-medium text-gray-700 mb-1">Lier à une
                            recommandation <span class="text-red-500">*</span></label>
                        <select name="recommandation_id" id="recommandation_id" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            <option value="">-- Sélectionnez une recommandation --</option>
                            @foreach($recommandations as $recommandation)
                                <option value="{{ $recommandation->id }}">
                                    {{ $recommandation->reference }} -
                                    {{ Str::limit($recommandation->titre, 60) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Le rapport affichera l'état d'avancement de cette
                            recommandation.</p>
                    </div>

                    <!-- Description / Contenu supplémentaire -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Observations /
                            Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="Ajoutez des détails supplémentaires ici..."></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow transition flex items-center gap-2">
                            <i class="fas fa-file-pdf"></i>
                            Générer le PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection