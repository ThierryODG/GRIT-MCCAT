@extends('layouts.app')

@section('title', 'Assigner un Point Focal - ' . $recommandation->reference)

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- Navigation -->
    <div class="mb-6">
        <a href="{{ route('responsable.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="mr-2 fas fa-arrow-left"></i>
            Retour au dashboard
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- En-tête -->
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-bold text-gray-900">Assigner un Point Focal</h1>
                <p class="mt-1 text-gray-600">à la recommandation</p>
                <div class="p-4 mt-4 border border-blue-200 rounded-lg bg-blue-50">
                    <h2 class="text-lg font-semibold text-blue-900">{{ $recommandation->reference }}</h2>
                    <p class="mt-1 text-blue-800">{{ $recommandation->titre }}</p>
                    <div class="flex justify-center mt-2 space-x-4 text-sm text-blue-700">
                        <span>ITS: <strong>{{ $recommandation->its->name ?? 'N/A' }}</strong></span>
                        <span>•</span>
                        <span>Structure: <strong>{{ $recommandation->structure->nom }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Formulaire d'assignation -->
            <form method="POST" action="{{ route('responsable.recommandations.store_assignation', $recommandation) }}">
                @csrf

                <div class="mb-6">
                    <label for="point_focal_id" class="block mb-2 text-sm font-medium text-gray-700">
                        Sélectionnez un point focal <span class="text-red-500">*</span>
                    </label>

                    @if($pointsFocaux->count() > 0)
                    <select name="point_focal_id" id="point_focal_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Choisissez un point focal...</option>
                        @foreach($pointsFocaux as $pointFocal)
                        <option value="{{ $pointFocal->id }}"
                            {{ $recommandation->point_focal_id == $pointFocal->id ? 'selected' : '' }}>
                            {{ $pointFocal->name }} - {{ $pointFocal->email }}
                            ({{ $pointFocal->recommandationsAssignees->count() }} recommandations)
                        </option>
                        @endforeach
                    </select>
                    @else
                    <div class="p-4 text-center border border-yellow-200 rounded-lg bg-yellow-50">
                        <i class="mb-2 text-xl text-yellow-500 fas fa-exclamation-triangle"></i>
                        <p class="text-yellow-800">Aucun point focal disponible dans votre structure.</p>
                        <p class="mt-1 text-sm text-yellow-700">Contactez l'administrateur pour ajouter des points focaux.</p>
                    </div>
                    @endif

                    @error('point_focal_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informations sur la recommandation (lecture seule) -->
                <div class="p-4 mb-6 rounded-lg bg-gray-50">
                    <h3 class="mb-2 font-medium text-gray-900">Détails de la recommandation</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="text-gray-600">Priorité</label>
                            <p class="font-medium">{{ ucfirst($recommandation->priorite) }}</p>
                        </div>
                        <div>
                            <label class="text-gray-600">Date limite</label>
                            <p class="font-medium {{ $recommandation->estEnRetard() ? 'text-red-600' : '' }}">
                                {{ $recommandation->date_limite->format('d/m/Y') }}
                                @if($recommandation->estEnRetard())
                                <span class="ml-1 text-red-500">🚨</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-span-2">
                            <label class="text-gray-600">Description</label>
                            <p class="mt-1 text-gray-700">{{ $recommandation->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end pt-4 space-x-4 border-t border-gray-200">
                    <a href="{{ route('responsable.dashboard') }}"
                       class="px-6 py-2 text-white transition-colors bg-gray-500 rounded-lg hover:bg-gray-600">
                        Annuler
                    </a>

                    @if($pointsFocaux->count() > 0)
                    <button type="submit"
                            class="flex items-center px-6 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                        <i class="mr-2 fas fa-user-check"></i>
                        Assigner le Point Focal
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
