@extends('layouts.app')

@section('title', 'Validation des Recommandations')

@section('content')
<div class="container p-6 mx-auto">
    <h1 class="mb-6 text-2xl font-bold">Validation des Recommandations</h1>

    <!-- Messages -->
    @if(session('success'))
        <div class="px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tableau des recommandations -->
    <div class="overflow-hidden bg-white rounded-lg shadow-md">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Référence</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Titre</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Structure</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Priorité</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Date</th>
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
                        <div class="font-medium">{{ $recommandation->titre }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($recommandation->description, 80) }}</div>
                    </td>
                    <td class="px-6 py-4">{{ $recommandation->structure_destinataire }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded
                            {{ $recommandation->priorite === 'haute' ? 'bg-red-100 text-red-800' :
                               ($recommandation->priorite === 'moyenne' ? 'bg-yellow-100 text-yellow-800' :
                               'bg-green-100 text-green-800') }}">
                            {{ $recommandation->priorite }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $recommandation->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('inspecteur_general.validation.show', $recommandation) }}"
                               class="text-blue-600 hover:text-blue-900">Détails</a>

                            @if($recommandation->statut === 'soumise_ig')
                            <form action="{{ route('inspecteur_general.validation.valider', $recommandation) }}"
                                  method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900"
                                        onclick="return confirm('Valider cette recommandation ?')">
                                    Valider
                                </button>
                            </form>

                            <form action="{{ route('inspecteur_general.validation.rejeter', $recommandation) }}"
                                  method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Rejeter cette recommandation ?')">
                                    Rejeter
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Aucune recommandation en attente de validation.
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
