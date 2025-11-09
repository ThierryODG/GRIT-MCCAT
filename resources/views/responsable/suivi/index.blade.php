@extends('layouts.app')

@section('title', 'Suivi des Recommandations - Responsable')

@section('content')
<div class="container p-6 mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Suivi des Recommandations</h1>
            <p class="text-gray-600">Suivi complet des recommandations assignées à votre structure</p>
        </div>
        <a href="{{ route('responsable.suivi.export') }}"
           class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exporter
        </a>
    </div>

    <!-- Filtres -->
    <div class="p-4 mb-6 rounded-lg bg-gray-50">
        <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="statut" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="validee_ig" {{ request('statut') == 'validee_ig' ? 'selected' : '' }}>Validée IG</option>
                    <option value="point_focal_assigne" {{ request('statut') == 'point_focal_assigne' ? 'selected' : '' }}>PF assigné</option>
                    <option value="plan_en_redaction" {{ request('statut') == 'plan_en_redaction' ? 'selected' : '' }}>Plan en rédaction</option>
                    <option value="plan_valide_responsable" {{ request('statut') == 'plan_valide_responsable' ? 'selected' : '' }}>Plan validé</option>
                    <option value="en_execution" {{ request('statut') == 'en_execution' ? 'selected' : '' }}>En exécution</option>
                    <option value="cloturee" {{ request('statut') == 'cloturee' ? 'selected' : '' }}>Clôturée</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Priorité</label>
                <select name="priorite" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Toutes</option>
                    <option value="haute" {{ request('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                    <option value="moyenne" {{ request('priorite') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                    <option value="basse" {{ request('priorite') == 'basse' ? 'selected' : '' }}>Basse</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Point Focal</label>
                <select name="point_focal" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous</option>
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
                <a href="{{ route('responsable.suivi.index') }}"
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau de suivi -->
    <div class="overflow-hidden bg-white rounded-lg shadow-md">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Référence</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Titre</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Priorité</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Point Focal</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Échéance</th>
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
                        <span class="px-2 py-1 text-xs font-semibold rounded
                            {{ $recommandation->priorite === 'haute' ? 'bg-red-100 text-red-800' :
                               ($recommandation->priorite === 'moyenne' ? 'bg-yellow-100 text-yellow-800' :
                               'bg-green-100 text-green-800') }}">
                            {{ $recommandation->priorite }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {{ $recommandation->pointFocal->name ?? 'Non assigné' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded
                            {{ in_array($recommandation->statut, ['cloturee', 'terminee']) ? 'bg-green-100 text-green-800' :
                               (in_array($recommandation->statut, ['en_retard', 'rejetee']) ? 'bg-red-100 text-red-800' :
                               'bg-yellow-100 text-yellow-800') }}">
                            {{ str_replace('_', ' ', $recommandation->statut) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($recommandation->date_limite)
                            <span class="text-sm {{ $recommandation->estEnRetard() ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                {{ $recommandation->date_limite->format('d/m/Y') }}
                            </span>
                            @if($recommandation->estEnRetard())
                            <div class="text-xs text-red-500">En retard</div>
                            @endif
                        @else
                            <span class="text-sm text-gray-400">Non définie</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('responsable.suivi.show', $recommandation) }}"
                           class="text-blue-600 hover:text-blue-900">
                            Détails
                        </a>
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
