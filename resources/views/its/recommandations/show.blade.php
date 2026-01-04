@extends('layouts.app')

@section('title', $recommandation->reference)

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- En-tête -->
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $recommandation->titre }}</h1>
                <div class="flex items-center mt-2 space-x-4">
                    <span class="font-mono text-lg text-blue-600">{{ $recommandation->reference }}</span>
                    <span class="px-3 py-1 text-sm rounded-full
                            @if($recommandation->statut == 'brouillon') bg-yellow-100 text-yellow-800
                            @elseif($recommandation->statut == 'soumise_ig') bg-blue-100 text-blue-800
                            @elseif($recommandation->statut == 'validee_ig') bg-green-100 text-green-800
                            @elseif($recommandation->statut == 'rejetee_ig') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                        {{ $recommandation->statut_label }}
                    </span>
                    <span class="px-3 py-1 text-sm rounded-full
                            @if($recommandation->priorite == 'haute') bg-red-100 text-red-800
                            @elseif($recommandation->priorite == 'moyenne') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                        <i class="mr-1 fas fa-flag"></i>{{ ucfirst($recommandation->priorite) }}
                    </span>
                    @if($recommandation->estEnRetard())
                        <span class="px-3 py-1 text-sm text-red-800 bg-red-100 rounded-full">
                            <i class="mr-1 fas fa-exclamation-triangle"></i>En retard
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex space-x-2">
                <a href="{{ route('its.recommandations.index') }}"
                    class="px-4 py-2 text-white transition bg-gray-500 rounded-md hover:bg-gray-600">
                    <i class="mr-2 fas fa-arrow-left"></i>Retour
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Informations principales -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Description -->
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h2 class="flex items-center mb-4 text-xl font-semibold text-gray-800">
                        <i class="mr-3 text-blue-500 fas fa-file-alt"></i>Description
                    </h2>
                    <div class="prose text-gray-700 max-w-none">
                        {!! nl2br(e($recommandation->description)) !!}
                    </div>
                </div>

                <!-- Détails techniques -->
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h2 class="flex items-center mb-4 text-xl font-semibold text-gray-800">
                        <i class="mr-3 text-green-500 fas fa-info-circle"></i>Détails techniques
                    </h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Date de création</label>
                            <p class="mt-1 text-gray-900">{{ $recommandation->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Dernière modification</label>
                            <p class="mt-1 text-gray-900">{{ $recommandation->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Date limite</label>
                            <p
                                class="mt-1 text-gray-900 {{ $recommandation->estEnRetard() ? 'text-red-600 font-semibold' : '' }}">
                                {{ $recommandation->date_limite->format('d/m/Y') }}
                                @if($recommandation->estEnRetard())
                                    <i class="ml-1 text-red-500 fas fa-exclamation-triangle"></i>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Jours restants</label>
                            <p class="mt-1 text-gray-900">
                                @php
                                    // Calcul précis des jours restants (sans décimales)
                                    $aujourdhui = now()->startOfDay();
                                    $dateLimite = $recommandation->date_limite->startOfDay();
                                    $joursRestants = $aujourdhui->diffInDays($dateLimite, false);
                                @endphp
                                @if($joursRestants < 0)
                                    <span class="font-semibold text-red-600">En retard ({{ abs($joursRestants) }} jours)</span>
                                @elseif($joursRestants == 0)
                                    <span class="font-semibold text-orange-600">Aujourd'hui</span>
                                @else
                                    <span class="text-green-600">{{ $joursRestants }} jours</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Documents Joints -->
                @if($recommandation->documents->count() > 0)
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <h2 class="flex items-center mb-4 text-xl font-semibold text-gray-800">
                            <i class="mr-3 text-indigo-500 fas fa-paperclip"></i>Documents Joints
                        </h2>
                        <ul class="divide-y divide-gray-200">
                            @foreach($recommandation->documents as $document)
                                <li class="py-3 flex justify-between items-center">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-alt text-gray-400 mr-3 text-lg"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $document->description ?? $document->file_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $document->file_name }}
                                                ({{ $document->created_at->format('d/m/Y') }})</p>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        <i class="fas fa-download mr-1"></i> Télécharger
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Plan d'action (Informations complémentaires) -->
                @if($recommandation->plansAction->count() > 0)
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <h2 class="flex items-center mb-4 text-xl font-semibold text-gray-800">
                            <i class="mr-3 text-purple-500 fas fa-tasks"></i>Plan d'action
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Action</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Indicateurs</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Échéance</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recommandation->plansAction as $action)
                                                        <tr>
                                                            <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($action->action, 50) }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($action->indicateurs, 30) }}
                                                            </td>
                                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                                {{ $action->date_fin_prevue ? \Carbon\Carbon::parse($action->date_fin_prevue)->format('d/m/Y') : '-' }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span
                                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                                                {{ $action->statut_execution === 'termine' ? 'bg-green-100 text-green-800' :
                                        ($action->statut_execution === 'en_cours' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800') }}">
                                                                    {{ $action->statut_execution_label }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Motif de rejet -->
                @if($recommandation->statut === 'rejetee_ig' && ($recommandation->motif_rejet_ig || $recommandation->commentaire_ig))
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <h2 class="flex items-center mb-4 text-xl font-semibold text-red-800">
                            <i class="mr-3 text-red-500 fas fa-exclamation-triangle"></i>Motif du rejet par l'Inspecteur Général
                        </h2>

                        @if($recommandation->motif_rejet_ig)
                            <div class="p-4 mb-4 border border-red-200 rounded-lg bg-red-50">
                                <h3 class="mb-2 font-semibold text-red-800">Motif principal :</h3>
                                <p class="text-red-700">{{ $recommandation->motif_rejet_ig }}</p>
                            </div>
                        @endif

                        @if($recommandation->commentaire_ig)
                            <div class="p-4 border border-orange-200 rounded-lg bg-orange-50">
                                <h3 class="mb-2 font-semibold text-orange-800">Commentaires supplémentaires :</h3>
                                <p class="text-orange-700">{{ $recommandation->commentaire_ig }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar - Informations complémentaires -->
            <div class="space-y-6">
                <!-- Statut et actions -->
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">Statut</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Statut actuel:</span>
                            <span class="px-2 py-1 text-xs rounded-full
                                    @if($recommandation->statut == 'brouillon') bg-yellow-100 text-yellow-800
                                    @elseif($recommandation->statut == 'soumise_ig') bg-blue-100 text-blue-800
                                    @elseif($recommandation->statut == 'validee_ig') bg-green-100 text-green-800
                                    @elseif($recommandation->statut == 'rejetee_ig') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                {{ $recommandation->statut_label }}
                            </span>
                        </div>

                        @if($recommandation->inspecteurGeneral)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Inspecteur Général:</span>
                                <span class="text-sm font-medium">{{ $recommandation->inspecteurGeneral->name }}</span>
                            </div>
                        @endif

                        @if($recommandation->date_validation_ig)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Validée le:</span>
                                <span class="text-sm">{{ $recommandation->date_validation_ig->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">Actions</h2>
                    <div class="space-y-2">
                        @if($recommandation->peutEtreModifiee())
                            <a href="{{ route('its.recommandations.edit', $recommandation) }}"
                                class="flex items-center justify-center w-full px-4 py-2 text-white transition bg-green-600 rounded-md hover:bg-green-700">
                                <i class="mr-2 fas fa-edit"></i>Modifier
                            </a>
                        @endif

                        @if($recommandation->peutEtreSoumise())
                            <form action="{{ route('its.recommandations.soumettre', $recommandation) }}" method="POST"
                                class="w-full">
                                @csrf
                                <button type="submit"
                                    class="flex items-center justify-center w-full px-4 py-2 text-white transition bg-purple-600 rounded-md hover:bg-purple-700"
                                    onclick="return confirm('Soumettre cette recommandation à l\\'Inspecteur Général ?')">
                                    <i class="mr-2 fas fa-paper-plane"></i>Soumettre à l'IG
                                </button>
                            </form>
                        @endif

                        <!-- Bouton Supprimer UNIQUEMENT pour brouillon ou rejetée -->
                        @if(in_array($recommandation->statut, ['brouillon', 'rejetee_ig']))
                            <form action="{{ route('its.recommandations.destroy', $recommandation) }}" method="POST"
                                class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center justify-center w-full px-4 py-2 text-white transition bg-red-600 rounded-md hover:bg-red-700"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette recommandation ? Cette action est irréversible.')">
                                    <i class="mr-2 fas fa-trash"></i>Supprimer
                                </button>
                            </form>
                        @endif

                        <!-- Bouton Rappel -->
                        <button type="button" onclick="document.getElementById('modal-rappel').classList.remove('hidden')"
                            class="flex items-center justify-center w-full px-4 py-2 text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700">
                            <i class="mr-2 fas fa-bell"></i>Faire un rappel
                        </button>
                    </div>
                </div>

                <!-- Informations structure -->
                @if($recommandation->structure)
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <h2 class="mb-4 text-xl font-semibold text-gray-800">Structure concernée</h2>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-sm text-gray-600">Structure</label>
                                <p class="font-medium">{{ $recommandation->structure->nom }}</p>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600">Sigle</label>
                                <p class="font-mono">{{ $recommandation->structure->sigle }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Point Focal & Planification -->
                @if($recommandation->pointFocal)
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <h2 class="mb-4 text-xl font-semibold text-gray-800">Exécution</h2>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm text-gray-600">Point Focal</label>
                                <p class="font-medium">{{ $recommandation->pointFocal->name }}</p>
                                <p class="text-sm text-gray-500">{{ $recommandation->pointFocal->telephone }}</p>
                            </div>

                            @if(in_array($recommandation->statut, ['en_execution', 'execution_terminee', 'demande_cloture', 'cloturee']))
                                <div class="pt-2">
                                    <a href="{{ route('its.recommandations.suivi', $recommandation) }}"
                                        class="flex items-center justify-center w-full px-4 py-2 text-white transition bg-blue-600 rounded-md hover:bg-blue-700 shadow-sm">
                                        <i class="mr-2 fas fa-eye"></i>Suivre l'exécution
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Rappel -->
    <div id="modal-rappel" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"
                onclick="document.getElementById('modal-rappel').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('its.recommandations.rappel', $recommandation) }}" method="POST">
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
                                            @if($recommandation->pointFocal)
                                                <option value="point_focal">Point Focal
                                                    ({{ $recommandation->pointFocal->name }})</option>
                                            @endif
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