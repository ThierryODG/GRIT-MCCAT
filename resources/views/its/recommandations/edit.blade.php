@extends('layouts.app')

@section('title', 'Modifier la Recommandation')

@section('content')
<div class="min-h-screen py-8 bg-gray-50">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête de page -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900">Modifier la Recommandation</h1>
            <p class="mt-2 text-lg text-gray-600">{{ $recommandation->reference }}</p>
        </div>

        <!-- Carte du formulaire -->
        <div class="overflow-hidden bg-white shadow-xl rounded-2xl">
            <div class="px-8 py-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                        <i class="text-blue-600 fas fa-edit"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-semibold text-gray-800">Modification de la recommandation</h2>
                        <p class="text-sm text-gray-500">Mettez à jour les informations de la recommandation</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('its.recommandations.update', $recommandation) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- Bannière d'information sur le statut -->
                <div class="p-4 rounded-lg border
                    @if($recommandation->statut == 'brouillon') bg-yellow-50 border-yellow-200
                    @elseif($recommandation->statut == 'rejetee_ig') bg-red-50 border-red-200
                    @else bg-blue-50 border-blue-200 @endif">
                    <div class="flex items-start">
                        <i class="mt-1 mr-3
                            @if($recommandation->statut == 'brouillon') text-yellow-500 fas fa-edit
                            @elseif($recommandation->statut == 'rejetee_ig') text-red-500 fas fa-exclamation-triangle
                            @else text-blue-500 fas fa-info-circle @endif"></i>
                        <div>
                            <h3 class="font-semibold
                                @if($recommandation->statut == 'brouillon') text-yellow-800
                                @elseif($recommandation->statut == 'rejetee_ig') text-red-800
                                @else text-blue-800 @endif">
                                Statut actuel : {{ $recommandation->statut_label }}
                            </h3>
                            <p class="mt-1 text-sm
                                @if($recommandation->statut == 'brouillon') text-yellow-700
                                @elseif($recommandation->statut == 'rejetee_ig') text-red-700
                                @else text-blue-700 @endif">
                                @if($recommandation->statut == 'brouillon')
                                    Cette recommandation est en brouillon. Vous pouvez la modifier et la soumettre à l'IG quand elle sera prête.
                                @elseif($recommandation->statut == 'rejetee_ig')
                                    Cette recommandation a été rejetée par l'Inspecteur Général. Vous pouvez la modifier et la resoumettre.
                                @else
                                    Vous pouvez modifier cette recommandation selon les règles de votre workflow.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Structure -->
                <div class="space-y-4">
                    <label for="structure_id" class="block text-sm font-medium text-gray-700">
                        <span class="flex items-center">
                            Structure destinataire
                            <span class="ml-1 text-red-500">*</span>
                        </span>
                    </label>
                    <select id="structure_id" name="structure_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('structure_id') border-red-500 @enderror">
                        <option value="" class="text-gray-400">Sélectionnez une structure</option>
                        @foreach($structures as $structure)
                            <option value="{{ $structure->id }}" {{ old('structure_id', $recommandation->structure_id) == $structure->id ? 'selected' : '' }}
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
                    <input type="text" id="titre" name="titre" value="{{ old('titre', $recommandation->titre) }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('titre') border-red-500 @enderror"
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
                    <textarea id="description" name="description" rows="6"
                              required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 resize-vertical @error('description') border-red-500 @enderror"
                              placeholder="Décrivez précisément le problème identifié, le contexte de la recommandation et les résultats attendus...">{{ old('description', $recommandation->description) }}</textarea>
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('priorite') border-red-500 @enderror">
                            <option value="" class="text-gray-400">Choisissez une priorité</option>
                            <option value="haute" {{ old('priorite', $recommandation->priorite) == 'haute' ? 'selected' : '' }} class="font-medium text-red-600">🔴 Haute priorité</option>
                            <option value="moyenne" {{ old('priorite', $recommandation->priorite) == 'moyenne' ? 'selected' : '' }} class="font-medium text-yellow-600">🟡 Priorité moyenne</option>
                            <option value="basse" {{ old('priorite', $recommandation->priorite) == 'basse' ? 'selected' : '' }} class="font-medium text-green-600">🟢 Priorité basse</option>
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
                            <input type="date" id="date_limite" name="date_limite"
                                   value="{{ old('date_limite', $recommandation->date_limite->format('Y-m-d')) }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('date_limite') border-red-500 @enderror">
                            <i class="absolute text-gray-400 transform -translate-y-1/2 pointer-events-none fas fa-calendar-alt right-4 top-1/2"></i>
                        </div>
                        @error('date_limite')
                            <p class="flex items-center mt-2 text-sm text-red-600">
                                <i class="mr-1 fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Informations de suivi -->
                <div class="grid grid-cols-1 gap-6 p-6 rounded-lg bg-gray-50 md:grid-cols-2">
                    <div>
                        <h4 class="font-medium text-gray-700">Informations de création</h4>
                        <div class="mt-2 space-y-1 text-sm text-gray-600">
                            <p><span class="font-medium">Référence :</span> {{ $recommandation->reference }}</p>
                            <p><span class="font-medium">Créée le :</span> {{ $recommandation->created_at->format('d/m/Y à H:i') }}</p>
                            <p><span class="font-medium">Dernière modification :</span> {{ $recommandation->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700">État actuel</h4>
                        <div class="mt-2 space-y-1 text-sm text-gray-600">
                            <p><span class="font-medium">Statut :</span>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                    @if($recommandation->statut == 'brouillon') bg-yellow-100 text-yellow-800
                                    @elseif($recommandation->statut == 'rejetee_ig') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $recommandation->statut_label }}
                                </span>
                            </p>
                            <p><span class="font-medium">Jours restants :</span>
                                @php
                                    $joursRestants = now()->diffInDays($recommandation->date_limite, false);
                                @endphp
                                @if($joursRestants < 0)
                                    <span class="font-medium text-red-600">En retard ({{ abs($joursRestants) }} jours)</span>
                                @elseif($joursRestants == 0)
                                    <span class="font-medium text-orange-600">Aujourd'hui</span>
                                @else
                                    <span class="text-green-600">{{ $joursRestants }} jours</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end pt-6 space-x-4 border-t border-gray-200">
                    <a href="{{ route('its.recommandations.show', $recommandation) }}"
                       class="px-6 py-3 font-medium text-gray-700 transition duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="mr-2 fas fa-times"></i>Annuler
                    </a>
                    <button type="submit"
                            class="px-8 py-3 font-medium text-white transition duration-200 bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="mr-2 fas fa-save"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Validation de la date
    document.getElementById('date_limite').addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (selectedDate <= today) {
            alert('La date limite doit être postérieure à aujourd\'hui.');
            this.value = '{{ $recommandation->date_limite->format('Y-m-d') }}';
            this.focus();
        }
    });

    // Animation des champs au focus
    document.querySelectorAll('input, select, textarea').forEach(element => {
        element.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-200');
        });

        element.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-200');
        });
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
