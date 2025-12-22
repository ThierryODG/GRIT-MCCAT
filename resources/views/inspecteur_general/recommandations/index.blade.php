@extends('layouts.app')

@section('title', 'Validation des Recommandations')

@section('content')
    <div class="container p-6 mx-auto">
        <h1 class="mb-6 text-2xl font-bold text-gray-800">Validation des Recommandations</h1>

        <!-- Messages -->
        @if(session('success'))
            <div class="p-4 mb-6 text-green-800 bg-green-100 border-l-4 border-green-500 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-6 text-red-800 bg-red-100 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Filtres -->
        <div class="p-6 mb-6 bg-white border border-gray-100 rounded-lg shadow-sm">
            <form method="GET" class="grid grid-cols-1 gap-6 md:grid-cols-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Statut</label>
                    <select name="statut"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="soumise_ig" {{ request('statut') == 'soumise_ig' ? 'selected' : '' }}>En attente
                        </option>
                        <option value="validee_ig" {{ request('statut') == 'validee_ig' ? 'selected' : '' }}>Validées</option>
                        <option value="rejetee_ig" {{ request('statut') == 'rejetee_ig' ? 'selected' : '' }}>Rejetées</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Priorité</label>
                    <select name="priorite"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes</option>
                        <option value="haute" {{ request('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                        <option value="moyenne" {{ request('priorite') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                        <option value="basse" {{ request('priorite') == 'basse' ? 'selected' : '' }}>Basse</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Structure</label>
                    <select name="structure_id"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les structures</option>
                        @foreach($structures as $structure)
                            <option value="{{ $structure->id }}" {{ request('structure_id') == $structure->id ? 'selected' : '' }}>
                                {{ $structure->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end space-x-3">
                    <button type="submit"
                        class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filtrer
                    </button>
                    <a href="{{ route('inspecteur_general.recommandations.index') }}"
                        class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Tableau des recommandations -->
        <div class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-xl">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Liste des Recommandations</h2>
                    <span class="px-3 py-1 text-sm font-medium text-blue-700 bg-blue-100 rounded-full">
                        {{ $recommandations->total() }} résultat(s)
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50/80">
                            <th class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                Référence</th>
                            <th class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                Titre</th>
                            <th class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                Inspecteur ITS</th>
                            <th class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                Priorité</th>
                            <th class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                Date</th>
                            <th class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recommandations as $recommandation)
                            <tr class="transition-colors duration-150 hover:bg-blue-50/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="flex items-center justify-center flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ $recommandation->reference }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <div class="text-sm font-semibold text-gray-900 truncate">{{ $recommandation->titre }}
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $recommandation->description }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="flex items-center justify-center flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $recommandation->its->name ?? 'N/A' }}</div>
                                            @if($recommandation->its->structure ?? false)
                                                <div class="text-xs text-gray-500">{{ $recommandation->its->structure->nom ?? '' }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $priorityConfig = [
                                            'haute' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'dot' => 'bg-red-400'],
                                            'moyenne' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'dot' => 'bg-yellow-400'],
                                            'basse' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'dot' => 'bg-green-400'],
                                        ];
                                        $config = $priorityConfig[$recommandation->priorite] ?? $priorityConfig['basse'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                        <span class="w-2 h-2 rounded-full {{ $config['dot'] }} mr-2"></span>
                                        {{ ucfirst($recommandation->priorite) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $recommandation->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $recommandation->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('inspecteur_general.recommandations.show', $recommandation) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors duration-150">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Détails
                                        </a>

                                        @if($recommandation->statut === 'soumise_ig')
                                            <form action="{{ route('inspecteur_general.recommandations.valider', $recommandation) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition-colors duration-150"
                                                    onclick="return confirm('Valider cette recommandation ?')">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Valider
                                                </button>
                                            </form>


                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500">Aucune recommandation trouvée</p>
                                        <p class="mt-1 text-sm text-gray-400">Aucune recommandation ne correspond à vos critères
                                            de recherche</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pied de tableau -->
            @if($recommandations->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Affichage de <span class="font-medium">{{ $recommandations->firstItem() }}</span> à
                            <span class="font-medium">{{ $recommandations->lastItem() }}</span> sur
                            <span class="font-medium">{{ $recommandations->total() }}</span> résultats
                        </div>
                        <div class="flex space-x-2">
                            {{ $recommandations->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection