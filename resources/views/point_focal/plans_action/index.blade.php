{{-- resources/views/point-focal/plans-action/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes Plans d\'Action - Point Focal')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- En-tête -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mes Plans d'Action</h1>
            <p class="mt-1 text-gray-600">Tous les plans d'action que j'ai créés</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
        <form method="GET" class="flex flex-col gap-4 sm:flex-row">
            <!-- Filtre Statut Validation -->
            <div class="flex-1">
                <label class="block mb-1 text-sm font-medium text-gray-700">Statut validation</label>
                <select name="statut_validation" class="w-full px-3 py-2 border border-gray-300 rounded-lg" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    @foreach($statutsValidation as $value => $label)
                    <option value="{{ $value }}" {{ request('statut_validation') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtre Statut Execution -->
            <div class="flex-1">
                <label class="block mb-1 text-sm font-medium text-gray-700">Statut exécution</label>
                <select name="statut_execution" class="w-full px-3 py-2 border border-gray-300 rounded-lg" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    @foreach($statutsExecution as $value => $label)
                    <option value="{{ $value }}" {{ request('statut_execution') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Réinitialiser -->
            <div class="flex-1">
                <label class="invisible block mb-1 text-sm font-medium text-gray-700">Réinitialiser</label>
                <a href="{{ route('point_focal.plans_action.index') }}" class="block w-full px-4 py-2 text-center text-white bg-gray-500 rounded-lg hover:bg-gray-600">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau -->
    <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
        @if($plansAction->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Action</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Recommandation</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Structure</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Validation</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Exécution</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Échéance</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($plansAction as $plan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($plan->action, 70) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $plan->recommandation->reference }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($plan->recommandation->titre, 40) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $plan->recommandation->structure->nom }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $recStatut = optional($plan->recommandation)->statut;
                                $validationColors = [
                                    'plan_soumis_responsable' => 'bg-yellow-100 text-yellow-800',
                                    'plan_valide_responsable' => 'bg-green-100 text-green-800',
                                    'plan_rejete_responsable' => 'bg-red-100 text-red-800',
                                    'plan_soumis_ig' => 'bg-orange-100 text-orange-800',
                                    'plan_valide_ig' => 'bg-green-100 text-green-800',
                                    'plan_rejete_ig' => 'bg-red-100 text-red-800'
                                ];

                                $colorClass = $validationColors[$recStatut] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                {{ $plan->statut_validation_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $executionColors = [
                                    'non_demarre' => 'bg-gray-100 text-gray-800',
                                    'en_cours' => 'bg-blue-100 text-blue-800',
                                    'termine' => 'bg-green-100 text-green-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $executionColors[$plan->statut_execution] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $plan->statut_execution_label }}
                            </span>
                            @if($plan->pourcentage_avancement > 0)
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $plan->pourcentage_avancement }}%"></div>
                            </div>
                            <div class="mt-1 text-xs text-gray-500">{{ $plan->pourcentage_avancement }}%</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            @if($plan->recommandation->date_fin_prevue)
                                {{ $plan->recommandation->date_fin_prevue->format('d/m/Y') }}
                                @if($plan->recommandation->estEnRetard())
                                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                    Retard
                                </span>
                                @endif
                            @else
                                <span class="text-gray-400">Non définie</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <a href="{{ route('point_focal.recommandations.show', $plan->recommandation_id) }}"
                               class="px-3 py-1 mr-2 text-blue-600 transition-colors rounded-md hover:text-blue-900 bg-blue-50 hover:bg-blue-100">
                                <i class="mr-1 fas fa-eye"></i>Voir
                            </a>

                            @if(in_array(optional($plan->recommandation)->statut, ['plan_soumis_responsable', 'plan_rejete_responsable', 'plan_rejete_ig']))
                            <a href="{{ route('point_focal.plans_action.edit', $plan) }}"
                               class="px-3 py-1 text-green-600 transition-colors rounded-md hover:text-green-900 bg-green-50 hover:bg-green-100">
                                <i class="mr-1 fas fa-edit"></i>Modifier
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Affichage de {{ $plansAction->firstItem() }} à {{ $plansAction->lastItem() }}
                    sur {{ $plansAction->total() }} plans d'action
                </div>
                <div>
                    {{ $plansAction->links() }}
                </div>
            </div>
        </div>

        @else
        <div class="py-12 text-center">
            <i class="mb-4 text-4xl text-gray-300 fas fa-tasks"></i>
            <h3 class="mb-2 text-lg font-medium text-gray-900">Aucun plan d'action</h3>
            <p class="text-gray-500">Vous n'avez pas encore créé de plans d'action.</p>
            <a href="{{ route('point_focal.recommandations.index') }}" class="inline-block mt-2 text-blue-600 hover:text-blue-800">
                Voir mes recommandations
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
