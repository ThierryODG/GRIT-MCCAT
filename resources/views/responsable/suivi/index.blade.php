@extends('layouts.app')

@section('title', 'Suivi des Recommandations')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Suivi de l'exécution</h1>
                <p class="text-gray-600 mt-2">Suivez l'avancement des recommandations de votre structure</p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
            <form action="{{ route('responsable.suivi.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="statut"
                        class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Tous les statuts</option>
                        <option value="en_execution" {{ request('statut') == 'en_execution' ? 'selected' : '' }}>En exécution
                        </option>
                        <option value="execution_terminee" {{ request('statut') == 'execution_terminee' ? 'selected' : '' }}>
                            Exécution terminée</option>
                        <option value="demande_cloture" {{ request('statut') == 'demande_cloture' ? 'selected' : '' }}>Demande
                            de clôture</option>
                        <option value="cloturee" {{ request('statut') == 'cloturee' ? 'selected' : '' }}>Clôturée</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Point Focal</label>
                    <select name="point_focal_id"
                        class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Tous les points focaux</option>
                        @foreach($pointsFocaux as $pf)
                            <option value="{{ $pf->id }}" {{ request('point_focal_id') == $pf->id ? 'selected' : '' }}>
                                {{ $pf->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm text-sm">
                    Filtrer
                </button>

                @if(request()->hasAny(['statut', 'point_focal_id']))
                    <a href="{{ route('responsable.suivi.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 transition text-sm">
                        Réinitialiser
                    </a>
                @endif
            </form>
        </div>

        @if($recommandations->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-clipboard-check text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Aucune recommandation trouvée</h3>
                <p class="text-gray-500 mt-1">Modifiez vos filtres ou attendez que des recommandations soient assignées.</p>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence
                                / Titre</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Point
                                Focal</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Progression</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recommandations as $recommandation)
                                @php
                                    $total = $recommandation->plansAction->count();
                                    $done = $recommandation->plansAction->where('statut_execution', 'termine')->count();
                                    $percent = $total > 0 ? round(($done / $total) * 100) : 0;
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-xs font-mono text-blue-600 bg-blue-50 px-2 py-0.5 rounded w-fit mb-1">{{ $recommandation->reference }}</span>
                                            <span
                                                class="text-sm font-medium text-gray-900 line-clamp-2">{{ $recommandation->titre }}</span>
                                            <span class="text-xs text-gray-500 mt-1">Fin:
                                                {{ $recommandation->date_limite->format('d/m/Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                                <i class="fas fa-user text-xs"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $recommandation->pointFocal->name ?? 'Non assigné' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap align-middle">
                                        <div class="w-full max-w-xs">
                                            <div class="flex justify-between text-xs mb-1">
                                                <span class="font-medium text-gray-700">{{ $percent }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $recommandation->statut === 'demande_cloture' ? 'bg-purple-100 text-purple-800' :
                            ($percent >= 100 ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800') }}">
                                            {{ $recommandation->statut === 'demande_cloture' ? 'Demande de Clôture' : ($percent >= 100 ? 'Terminé' : 'En cours') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('responsable.suivi.show', $recommandation) }}"
                                            class="text-blue-600 hover:text-blue-900">Détails</a>
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $recommandations->links() }}
            </div>
        @endif
    </div>
@endsection