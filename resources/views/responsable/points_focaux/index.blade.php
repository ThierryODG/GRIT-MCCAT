@extends('layouts.app')

@section('title', 'Gestion des Points Focaux - Responsable')

@section('content')
<div class="container p-6 mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Points Focaux</h1>
        <p class="text-gray-600">Assigner et réassigner les points focaux aux recommandations</p>
    </div>

    <!-- Filtres -->
    <div class="p-4 mb-6 rounded-lg bg-gray-50">
        <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="statut" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="validee_ig" {{ request('statut') == 'validee_ig' ? 'selected' : '' }}>Validée IG</option>
                    <option value="transmise_structure" {{ request('statut') == 'transmise_structure' ? 'selected' : '' }}>Transmise structure</option>
                    <option value="point_focal_assigne" {{ request('statut') == 'point_focal_assigne' ? 'selected' : '' }}>Point focal assigné</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Point Focal</label>
                <select name="point_focal" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous les points focaux</option>
                    @foreach($pointsFocaux as $pointFocal)
                        <option value="{{ $pointFocal->id }}" {{ request('point_focal') == $pointFocal->id ? 'selected' : '' }}>
                            {{ $pointFocal->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Filtrer
                </button>
                <a href="{{ route('responsable.points_focaux.index') }}"
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau des recommandations -->
    <div class="overflow-hidden bg-white rounded-lg shadow-md">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Référence</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Titre</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Inspecteur ITS</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Priorité</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Point Focal</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recommandations as $recommandation)
                <tr>
                    <td class="px-6 py-4">
                        <span class="font-medium">{{ $recommandation->reference }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ Str::limit($recommandation->titre, 60) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        {{ $recommandation->its->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded
                            {{ $recommandation->priorite === 'haute' ? 'bg-red-100 text-red-800' :
                               ($recommandation->priorite === 'moyenne' ? 'bg-yellow-100 text-yellow-800' :
                               'bg-green-100 text-green-800') }}">
                            {{ $recommandation->priorite }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($recommandation->pointFocal)
                            <span class="text-sm text-gray-900">{{ $recommandation->pointFocal->name }}</span>
                        @else
                            <span class="text-sm text-red-600">Non assigné</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded
                            {{ $recommandation->statut === 'point_focal_assigne' ? 'bg-green-100 text-green-800' :
                               ($recommandation->statut === 'validee_ig' ? 'bg-blue-100 text-blue-800' :
                               'bg-yellow-100 text-yellow-800') }}">
                            {{ str_replace('_', ' ', $recommandation->statut) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            @if(!$recommandation->pointFocal)
                            <form action="{{ route('responsable.points_focaux.assigner', $recommandation) }}" method="POST">
                                @csrf
                                <select name="point_focal_id" class="px-2 py-1 text-sm border rounded" required>
                                    <option value="">Choisir PF</option>
                                    @foreach($pointsFocaux as $pointFocal)
                                        <option value="{{ $pointFocal->id }}">{{ $pointFocal->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="ml-2 text-sm text-green-600 hover:text-green-900">
                                    Assigner
                                </button>
                            </form>
                            @else
                            <form action="{{ route('responsable.points_focaux.reassigner', $recommandation) }}" method="POST">
                                @csrf
                                <select name="point_focal_id" class="px-2 py-1 text-sm border rounded">
                                    <option value="">Changer PF</option>
                                    @foreach($pointsFocaux as $pointFocal)
                                        <option value="{{ $pointFocal->id }}" {{ $recommandation->point_focal_id == $pointFocal->id ? 'selected' : '' }}>
                                            {{ $pointFocal->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="ml-2 text-sm text-blue-600 hover:text-blue-900">
                                    Modifier
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Aucune recommandation trouvée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $recommandations->links() }}
    </div>
</div>
@endsection
