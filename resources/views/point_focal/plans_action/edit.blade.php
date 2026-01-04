{{-- resources/views/point_focal/plans_action/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Modifier un plan d\'action')

@section('content')
    <div class="container px-4 py-6 mx-auto max-w-3xl">
        <!-- En-tête -->
        <div class="mb-6">
            <a href="{{ route('point_focal.recommandations.show', $planAction->recommandation) }}"
                class="inline-flex items-center mb-2 text-blue-600 hover:text-blue-800">
                <i class="mr-2 fas fa-arrow-left"></i>
                Retour aux détails
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Modifier un plan d'action</h1>
            <p class="mt-1 text-gray-600">{{ $planAction->recommandation->titre }}</p>
        </div>

        <!-- Contexte -->
        <div class="p-4 mb-6 bg-blue-50 border border-blue-200 rounded-lg">
            <h2 class="font-semibold text-blue-900 mb-3">Contexte de la recommandation</h2>
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                @if($planAction->recommandation->indicateurs)
                    <div>
                        <span class="font-medium text-blue-800">Indicateur :</span>
                        <p class="text-blue-700 text-sm mt-1">{{ $planAction->recommandation->indicateurs }}</p>
                    </div>
                @endif

                @if($planAction->recommandation->incidence_financiere)
                    <div>
                        <span class="font-medium text-blue-800">Incidence :</span>
                        <p class="text-blue-700 text-sm mt-1">{{ ucfirst($planAction->recommandation->incidence_financiere) }}
                        </p>
                    </div>
                @endif

                @if($planAction->recommandation->delai_mois)
                    <div>
                        <span class="font-medium text-blue-800">Délai :</span>
                        <p class="text-blue-700 text-sm mt-1">{{ $planAction->recommandation->delai_mois }} mois</p>
                    </div>
                @endif

                @if($planAction->recommandation->date_fin_prevue)
                    <div>
                        <span class="font-medium text-blue-800">Date limite :</span>
                        <p class="text-blue-700 text-sm mt-1">
                            {{ $planAction->recommandation->date_fin_prevue->format('d/m/Y') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="{{ route('point_focal.plans_action.update', $planAction) }}"
            class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="action" class="block mb-2 text-sm font-medium text-gray-700">
                    Plan d'action <span class="text-red-500">*</span>
                </label>
                <textarea name="action" id="action" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                          @error('action') border-red-500 @enderror"
                    placeholder="Décrivez l'action ou la mesure à prendre..."
                    required>{{ old('action', $planAction->action) }}</textarea>

                @error('action')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Responsable de l'exécution -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <label class="block mb-3 text-sm font-medium text-gray-700">Qui est responsable de l'exécution de cette
                    action ?</label>

                <div class="flex items-center space-x-6 mb-4">
                    <div class="flex items-center">
                        <input id="executant_self" name="executant_type" type="radio" value="self" {{ old('executant_type', $planAction->executant_type ?? 'self') == 'self' ? 'checked' : '' }}
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                        <label for="executant_self" class="ml-2 block text-sm text-gray-700">
                            Moi-même (Point Focal)
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="executant_other" name="executant_type" type="radio" value="other" {{ old('executant_type', $planAction->executant_type ?? 'self') == 'other' ? 'checked' : '' }}
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                        <label for="executant_other" class="ml-2 block text-sm text-gray-700">
                            Une autre personne / entité
                        </label>
                    </div>
                </div>

                <div id="executant_details"
                    class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ old('executant_type', $planAction->executant_type ?? 'self') == 'other' ? '' : 'hidden' }}">
                    <div>
                        <label for="executant_nom" class="block mb-1 text-sm font-medium text-gray-700">Nom de
                            l'exécutant</label>
                        <input type="text" name="executant_nom" id="executant_nom"
                            value="{{ old('executant_nom', $planAction->executant_nom) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Nom Prénom ou Entité">
                        @error('executant_nom')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="executant_role" class="block mb-1 text-sm font-medium text-gray-700">Fonction /
                            Rôle</label>
                        <input type="text" name="executant_role" id="executant_role"
                            value="{{ old('executant_role', $planAction->executant_role) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Ex: Responsable Technique">
                    </div>
                </div>
            </div>

            <!-- Message d'information -->
            @if(in_array($planAction->statut_validation, ['rejete_responsable', 'rejete_ig']))
                <div class="p-4 mb-6 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-sm text-amber-800">
                        <i class="mr-2 fas fa-info-circle"></i>
                        <strong>Cette action a été rejetée.</strong> En la modifiant, elle sera automatiquement renvoyée pour
                        validation.
                    </p>
                </div>
            @endif

            <!-- Boutons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('point_focal.recommandations.show', $planAction->recommandation) }}"
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="mr-2 fas fa-save"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radioSelf = document.getElementById('executant_self');
        const radioOther = document.getElementById('executant_other');
        const detailsDiv = document.getElementById('executant_details');

        function toggleDetails() {
            if (radioOther.checked) {
                detailsDiv.classList.remove('hidden');
            } else {
                detailsDiv.classList.add('hidden');
            }
        }

        radioSelf.addEventListener('change', toggleDetails);
        radioOther.addEventListener('change', toggleDetails);
    });
</script>
@endsection