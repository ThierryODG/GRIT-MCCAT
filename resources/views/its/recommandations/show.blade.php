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
            @if($recommandation->peutEtreModifiee())
            <a href="{{ route('its.recommandations.edit', $recommandation) }}"
               class="px-4 py-2 text-white transition bg-green-600 rounded-md hover:bg-green-700">
                <i class="mr-2 fas fa-edit"></i>Modifier
            </a>
            @endif

            @if($recommandation->peutEtreSoumise())
            <form action="{{ route('its.recommandations.soumettre', $recommandation) }}" method="POST">
                @csrf
                <button type="submit"
                        class="px-4 py-2 text-white transition bg-purple-600 rounded-md hover:bg-purple-700"
                        onclick="return confirm('Soumettre cette recommandation à l\\'Inspecteur Général ?')">
                    <i class="mr-2 fas fa-paper-plane"></i>Soumettre à l'IG
                </button>
            </form>
            @endif

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
                        <p class="mt-1 text-gray-900 {{ $recommandation->estEnRetard() ? 'text-red-600 font-semibold' : '' }}">
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
                                $joursRestants = now()->diffInDays($recommandation->date_limite, false);
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
                    <form action="{{ route('its.recommandations.soumettre', $recommandation) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                                class="flex items-center justify-center w-full px-4 py-2 text-white transition bg-purple-600 rounded-md hover:bg-purple-700"
                                onclick="return confirm('Soumettre cette recommandation à l\\'Inspecteur Général ?')">
                            <i class="mr-2 fas fa-paper-plane"></i>Soumettre à l'IG
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('its.recommandations.index') }}"
                       class="flex items-center justify-center w-full px-4 py-2 text-white transition bg-gray-500 rounded-md hover:bg-gray-600">
                        <i class="mr-2 fas fa-list"></i>Liste des recommandations
                    </a>
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
        </div>
    </div>
</div>
@endsection
