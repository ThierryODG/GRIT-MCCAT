@extends('layouts.app')

@section('title', 'Suivi des Recommandations')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Suivi des Recommandations</h1>
            <p class="text-gray-600">Surveillez l'avancement de toutes vos recommandations</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="toggleFilters()"
                    class="flex items-center px-4 py-2 text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                </svg>
                Filtres
            </button>
            <a href="{{ route('responsable.suivi.export') }}"
               class="flex items-center px-4 py-2 text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exporter
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div id="filtersSection" class="hidden p-6 bg-white border border-gray-200 rounded-xl">
        <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Statut</label>
                <select name="statut" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="point_focal_assigne" {{ request('statut') == 'point_focal_assigne' ? 'selected' : '' }}>Point Focal Assigné</option>
                    <option value="plan_en_redaction" {{ request('statut') == 'plan_en_redaction' ? 'selected' : '' }}>Plan en Rédaction</option>
                    <option value="plan_soumis_responsable" {{ request('statut') == 'plan_soumis_responsable' ? 'selected' : '' }}>Plan Soumis</option>
                    <option value="plan_valide_responsable" {{ request('statut') == 'plan_valide_responsable' ? 'selected' : '' }}>Plan Validé</option>
                    <option value="plan_soumis_ig" {{ request('statut') == 'plan_soumis_ig' ? 'selected' : '' }}>Plan Soumis à l'IG</option>
                    <option value="plan_valide_ig" {{ request('statut') == 'plan_valide_ig' ? 'selected' : '' }}>Plan Validé par l'IG</option>
                    <option value="en_execution" {{ request('statut') == 'en_execution' ? 'selected' : '' }}>En Exécution</option>
                    <option value="execution_terminee" {{ request('statut') == 'execution_terminee' ? 'selected' : '' }}>Exécution Terminée</option>
                    <option value="cloturee" {{ request('statut') == 'cloturee' ? 'selected' : '' }}>Clôturée</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Point Focal</label>
                <select name="point_focal_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les points focaux</option>
                    @foreach($pointsFocaux as $pf)
                    <option value="{{ $pf->id }}" {{ request('point_focal_id') == $pf->id ? 'selected' : '' }}>
                        {{ $pf->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Priorité</label>
                <select name="priorite" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les priorités</option>
                    <option value="haute" {{ request('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                    <option value="moyenne" {{ request('priorite') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                    <option value="basse" {{ request('priorite') == 'basse' ? 'selected' : '' }}>Basse</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3 md:col-span-3">
                <button type="reset" class="px-4 py-2 text-gray-700 transition-colors bg-gray-300 rounded-lg hover:bg-gray-400">
                    Réinitialiser
                </button>
                <button type="submit" class="px-4 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                    Appliquer les filtres
                </button>
            </div>
        </form>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-5">
        <div class="p-6 bg-white border border-gray-200 rounded-xl">
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $recommandations->total() }}</p>
                <p class="text-sm font-medium text-gray-600">Total</p>
            </div>
        </div>

        <div class="p-6 bg-white border border-gray-200 rounded-xl">
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">
                    {{ $recommandations->where('statut', 'plan_valide_ig')->count() }}
                </p>
                <p class="text-sm font-medium text-gray-600">Plans validés</p>
            </div>
        </div>

        <div class="p-6 bg-white border border-gray-200 rounded-xl">
            <div class="text-center">
                <p class="text-2xl font-bold text-orange-600">
                    {{ $recommandations->whereIn('statut', ['plan_en_redaction', 'plan_soumis_responsable'])->count() }}
                </p>
                <p class="text-sm font-medium text-gray-600">En cours</p>
            </div>
        </div>

        <div class="p-6 bg-white border border-gray-200 rounded-xl">
            <div class="text-center">
                <p class="text-2xl font-bold text-red-600">
                    {{ $recommandations->where('date_limite', '<', now())->whereNotIn('statut', ['cloturee', 'execution_terminee'])->count() }}
                </p>
                <p class="text-sm font-medium text-gray-600">En retard</p>
            </div>
        </div>

        <div class="p-6 bg-white border border-gray-200 rounded-xl">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">
                    {{ $recommandations->whereIn('statut', ['cloturee', 'execution_terminee'])->count() }}
                </p>
                <p class="text-sm font-medium text-gray-600">Terminées</p>
            </div>
        </div>
    </div>

    <!-- Tableau des recommandations -->
    <div class="overflow-hidden bg-white border border-gray-200 rounded-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Recommandation</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Point Focal</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Échéance</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Avancement</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recommandations as $recommandation)
                    <tr class="transition-colors hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $recommandation->reference }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($recommandation->priorite === 'haute') bg-red-100 text-red-800
                                        @elseif($recommandation->priorite === 'moyenne') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ strtoupper(substr($recommandation->priorite, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="mt-1 text-sm font-medium text-gray-900">{{ $recommandation->titre }}</div>
                                <div class="mt-1 text-xs text-gray-500 line-clamp-1">{{ $recommandation->description }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $recommandation->pointFocal->name ?? 'Non assigné' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $recommandation->its->name ?? 'ITS' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if(in_array($recommandation->statut, ['cloturee', 'execution_terminee', 'plan_valide_ig'])) bg-green-100 text-green-800
                                @elseif(in_array($recommandation->statut, ['en_execution', 'plan_valide_responsable'])) bg-blue-100 text-blue-800
                                @elseif(in_array($recommandation->statut, ['plan_en_redaction', 'plan_soumis_responsable'])) bg-yellow-100 text-yellow-800
                                @elseif($recommandation->statut === 'point_focal_assigne') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $recommandation->statut_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $recommandation->date_limite->format('d/m/Y') }}
                            </div>
                            @if($recommandation->date_limite < now() && !in_array($recommandation->statut, ['cloturee', 'execution_terminee']))
                            <div class="text-xs font-medium text-red-600">En retard</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($recommandation->planAction)
                            <div class="flex items-center space-x-2">
                                <div class="w-16 h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-green-600 rounded-full"
                                         style="width: {{ $recommandation->planAction->pourcentage_avancement }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ $recommandation->planAction->pourcentage_avancement }}%</span>
                            </div>
                            <div class="mt-1 text-xs text-gray-500">
                                {{ $recommandation->planAction->statut_execution_label }}
                            </div>
                            @else
                            <span class="text-sm text-gray-500">Aucun plan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <a href="{{ route('responsable.suivi.show', $recommandation) }}"
                               class="mr-3 text-blue-600 hover:text-blue-900">Détails</a>

                            @if($recommandation->planAction && $recommandation->planAction->statut_validation === 'en_attente_responsable')
                            <a href="{{ route('responsable.validation_plans.show', $recommandation->planAction) }}"
                               class="text-orange-600 hover:text-orange-900">Valider</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-4 text-gray-500">Aucune recommandation trouvée</p>
                            <p class="text-sm text-gray-400">Ajustez vos filtres pour voir plus de résultats</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($recommandations->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $recommandations->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function toggleFilters() {
    const filtersSection = document.getElementById('filtersSection');
    filtersSection.classList.toggle('hidden');
}
</script>
@endsection
