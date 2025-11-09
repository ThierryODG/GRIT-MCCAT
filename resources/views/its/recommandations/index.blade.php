@extends('layouts.app')

@section('title', 'Mes Recommandations')
@section('subtitle', 'Liste de toutes vos recommandations')

@section('content')
<!-- Filtres -->
<div class="p-6 mb-6 bg-white rounded-lg shadow-md">
    <form action="{{ route('its.recommandations.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <!-- Recherche -->
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Titre ou référence..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Statut -->
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">Statut</label>
            <select name="statut" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les statuts</option>
                <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                <option value="soumise_ig" {{ request('statut') == 'soumise_ig' ? 'selected' : '' }}>Soumise à l'IG</option>
                <option value="validee_ig" {{ request('statut') == 'validee_ig' ? 'selected' : '' }}>Validée par l'IG</option>
                <option value="rejetee_ig" {{ request('statut') == 'rejetee_ig' ? 'selected' : '' }}>Rejetée par l'IG</option>
            </select>
        </div>

        <!-- Priorité -->
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">Priorité</label>
            <select name="priorite" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Toutes les priorités</option>
                <option value="haute" {{ request('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                <option value="moyenne" {{ request('priorite') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                <option value="basse" {{ request('priorite') == 'basse' ? 'selected' : '' }}>Basse</option>
            </select>
        </div>

        <!-- Actions -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="px-4 py-2 text-white transition bg-blue-600 rounded-md hover:bg-blue-700">
                <i class="mr-2 fas fa-filter"></i>Filtrer
            </button>
            <a href="{{ route('its.recommandations.index') }}"
               class="px-4 py-2 text-white transition bg-gray-500 rounded-md hover:bg-gray-600">
                <i class="mr-2 fas fa-redo"></i>Réinitialiser
            </a>
        </div>
    </form>
</div>

<!-- Bouton de création -->
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-gray-600">
        {{ $recommandations->total() }} recommandation(s) trouvée(s)
    </div>
    <a href="{{ route('its.recommandations.create') }}"
       class="px-4 py-2 text-white transition bg-green-600 rounded-md hover:bg-green-700">
        <i class="mr-2 fas fa-plus"></i>Nouvelle Recommandation
    </a>
</div>

<!-- Liste des recommandations -->
<div class="overflow-hidden bg-white rounded-lg shadow-md">
    @if($recommandations->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Référence
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Titre
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Structure
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Priorité
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Date limite
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recommandations as $recommandation)
                    <tr class="transition hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm text-blue-600">{{ $recommandation->reference }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($recommandation->titre, 60) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $recommandation->structure->sigle ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @include('shared.status-badge', ['statut' => $recommandation->statut])
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($recommandation->priorite == 'haute') bg-red-100 text-red-800
                                @elseif($recommandation->priorite == 'moyenne') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                <i class="mr-1 fas fa-flag"></i>{{ ucfirst($recommandation->priorite) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 {{ $recommandation->estEnRetard() ? 'text-red-600 font-semibold' : '' }}">
                                {{ $recommandation->date_limite->format('d/m/Y') }}
                                @if($recommandation->estEnRetard())
                                    <i class="ml-1 text-red-500 fas fa-exclamation-triangle"></i>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <div class="flex space-x-3">
                                <!-- Voir -->
                                <a href="{{ route('its.recommandations.show', $recommandation) }}"
                                   class="text-blue-600 transition hover:text-blue-900" title="Voir les détails">
                                    <i class="fas fa-eye fa-lg"></i>
                                </a>

                                <!-- Modifier (si possible) -->
                                @if($recommandation->peutEtreModifiee())
                                <a href="{{ route('its.recommandations.edit', $recommandation) }}"
                                   class="text-green-600 transition hover:text-green-900" title="Modifier">
                                    <i class="fas fa-edit fa-lg"></i>
                                </a>
                                @endif

                                <!-- Soumettre à l'IG (si possible) -->
                                @if($recommandation->peutEtreSoumise())
                                <form action="{{ route('its.recommandations.soumettre', $recommandation) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-purple-600 transition hover:text-purple-900"
                                            title="Soumettre à l'IG"
                                            onclick="return confirm('Soumettre la recommandation {{ $recommandation->reference }} à l\\'Inspecteur Général ?')">
                                        <i class="fas fa-paper-plane fa-lg"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $recommandations->links() }}
        </div>
    @else
        <div class="py-12 text-center">
            <i class="mb-4 text-4xl text-gray-300 fas fa-inbox"></i>
            <p class="text-lg text-gray-500">Aucune recommandation trouvée</p>
            <p class="mt-2 text-gray-400">Commencez par créer votre première recommandation</p>
            <a href="{{ route('its.recommandations.create') }}"
               class="inline-block px-6 py-2 mt-4 text-white transition bg-green-600 rounded-md hover:bg-green-700">
                <i class="mr-2 fas fa-plus"></i>Créer une recommandation
            </a>
        </div>
    @endif
</div>
@endsection
