@extends('layouts.app')

@section('title', 'Nouvelle Recommandation')

@section('content')
    <div class="min-h-screen py-8 bg-gray-50">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête de page -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900">Nouvelle Recommandation</h1>
                <p class="mt-2 text-lg text-gray-600">Créez une nouvelle recommandation pour une structure</p>
            </div>

            <!-- Carte du formulaire -->
            <div class="overflow-hidden bg-white shadow-xl rounded-2xl">
                <div class="px-8 py-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                            <i class="text-blue-600 fas fa-file-alt"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-semibold text-gray-800">Informations de la recommandation</h2>
                            <p class="text-sm text-gray-500">Renseignez tous les champs obligatoires</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('its.recommandations.store') }}" method="POST" class="p-8 space-y-8">
                    @csrf

                    <!-- Structure -->
                    <div class="space-y-4">
                        <label for="structure_id" class="block text-sm font-medium text-gray-700">
                            <span class="flex items-center">
                                Structure destinataire
                                <span class="ml-1 text-red-500">*</span>
                            </span>
                        </label>
                        <select id="structure_id" name="structure_id" required
                            class="w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('structure_id') border-red-500 @else border-gray-300 @enderror">
                            <option value="" class="text-gray-400">Sélectionnez une structure</option>
                            @foreach($structures as $structure)
                                <option value="{{ $structure->id }}" {{ old('structure_id') == $structure->id ? 'selected' : '' }}
                                    class="text-gray-700">
                                    {{ $structure->nom }} ({{ $structure->sigle }})
                                </option>
                            @endforeach
                        </select>
                        @error('structure_id')
                            <p class="flex items-center mt-2 text-sm text-red-600">
                                <i class="mr-1 fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Titre -->
                    <div class="space-y-4">
                        <label for="titre" class="block text-sm font-medium text-gray-700">
                            <span class="flex items-center">
                                Titre de la recommandation
                                <span class="ml-1 text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required
                            class="w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('titre') border-red-500 @else border-gray-300 @enderror"
                            placeholder="Ex: Renforcement des capacités techniques du personnel...">
                        @error('titre')
                            <p class="flex items-center mt-2 text-sm text-red-600">
                                <i class="mr-1 fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="space-y-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            <span class="flex items-center">
                                Description détaillée
                                <span class="ml-1 text-red-500">*</span>
                            </span>
                        </label>
                        <textarea id="description" name="description" rows="6" required
                            class="w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 resize-vertical @error('description') border-red-500 @else border-gray-300 @enderror"
                            placeholder="Décrivez précisément le problème identifié, le contexte de la recommandation et les résultats attendus...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="flex items-center mt-2 text-sm text-red-600">
                                <i class="mr-1 fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Priorité et Date limite -->
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <!-- Priorité -->
                        <div class="space-y-4">
                            <label for="priorite" class="block text-sm font-medium text-gray-700">
                                <span class="flex items-center">
                                    Niveau de priorité
                                    <span class="ml-1 text-red-500">*</span>
                                </span>
                            </label>
                            <select id="priorite" name="priorite" required
                                class="w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('priorite') border-red-500 @else border-gray-300 @enderror">
                                <option value="" class="text-gray-400">Choisissez une priorité</option>
                                <option value="haute" {{ old('priorite') == 'haute' ? 'selected' : '' }}
                                    class="font-medium text-red-600">🔴 Haute priorité</option>
                                <option value="moyenne" {{ old('priorite') == 'moyenne' ? 'selected' : '' }}
                                    class="font-medium text-yellow-600">🟡 Priorité moyenne</option>
                                <option value="basse" {{ old('priorite') == 'basse' ? 'selected' : '' }}
                                    class="font-medium text-green-600">🟢 Priorité basse</option>
                            </select>
                            @error('priorite')
                                <p class="flex items-center mt-2 text-sm text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Date limite -->
                        <div class="space-y-4">
                            <label for="date_limite" class="block text-sm font-medium text-gray-700">
                                <span class="flex items-center">
                                    Date limite d'exécution
                                    <span class="ml-1 text-red-500">*</span>
                                </span>
                            </label>
                            <div class="relative">
                                <input type="date" id="date_limite" name="date_limite" value="{{ old('date_limite') }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                                    class="w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('date_limite') border-red-500 @else border-gray-300 @enderror">
                                <i
                                    class="absolute text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-calendar-alt right-4 top-1/2"></i>
                            </div>
                            @error('date_limite')
                                <p class="flex items-center mt-2 text-sm text-red-600">
                                    <i class="mr-1 fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Documents joints (Rapport d'audit, etc.) -->
                    <div class="p-6 border border-gray-200 rounded-lg bg-gray-50">
                        <h3 class="flex items-center mb-4 text-lg font-medium text-gray-900">
                            <i class="mr-2 text-indigo-500 fas fa-paperclip"></i> Documents joints
                        </h3>
                        <p class="mb-4 text-sm text-gray-600">
                            Joignez ici les rapports d'audit ou autres documents justificatifs (PDF, Excel, Images).
                        </p>

                        <div id="documents-container" class="space-y-4">
                            <!-- Les champs seront ajoutés ici dynamiquement -->
                            <div class="flex items-start space-x-4 document-row">
                                <div class="flex-grow">
                                    <label class="block text-sm font-medium text-gray-700">Fichier</label>
                                    <input type="file" name="documents[]" multiple
                                        class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                                <div class="flex-grow">
                                    <label class="block text-sm font-medium text-gray-700">Description (Optionnel)</label>
                                    <input type="text" name="documents_descriptions[]"
                                        placeholder="Ex: Rapport d'audit complet"
                                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <button type="button" id="add-document-btn"
                            class="mt-4 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i> Ajouter un autre document
                        </button>

                        @error('documents.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informations complémentaires -->
                    <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                        <div class="flex items-start">
                            <i class="mt-1 mr-3 text-blue-500 fas fa-info-circle"></i>
                            <div>
                                <h3 class="font-semibold text-blue-800">Processus de création</h3>
                                <p class="mt-1 text-sm text-blue-700">
                                    La recommandation sera créée en statut "Brouillon". Vous pourrez la modifier
                                    à tout moment avant de la soumettre à l'Inspecteur Général pour validation.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end pt-6 space-x-4 border-t border-gray-200">
                        <a href="{{ route('its.recommandations.index') }}"
                            class="px-6 py-3 font-medium text-gray-700 transition duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <i class="mr-2 fas fa-arrow-left"></i>Retour à la liste
                        </a>
                        <button type="submit"
                            class="px-8 py-3 font-medium text-white transition duration-200 bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="mr-2 fas fa-save"></i>Créer la recommandation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Validation améliorée de la date
        document.getElementById('date_limite').addEventListener('change', function () {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate <= today) {
                alert('La date limite doit être postérieure à aujourd\'hui.');
                this.value = '';
                this.focus();
            }
        });

        // Animation des champs au focus
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('focus', function () {
                this.parentElement.classList.add('ring-2', 'ring-blue-200');
            });

            element.addEventListener('blur', function () {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200');
            });
        });

        // Gestion dynamique des documents
        document.getElementById('add-document-btn').addEventListener('click', function () {
            const container = document.getElementById('documents-container');
            const firstRow = container.querySelector('.document-row');
            const newRow = firstRow.cloneNode(true);

            // Reset values
            newRow.querySelector('input[type="file"]').value = '';
            newRow.querySelector('input[type="text"]').value = '';

            container.appendChild(newRow);
        });
    </script>

    <style>
        .resize-vertical {
            resize: vertical;
            min-height: 120px;
        }

        /* Style personnalisé pour le select */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
@endsection