@extends('layouts.app')

@section('title', 'Mes Recommandations - Point Focal')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Mes Dossiers Recommandations</h1>
        <p class="mt-1 text-gray-600">Classés par inspecteur ITS</p>
    </div>

    <!-- Cartes ITS avec leurs recommandations -->
    <div class="space-y-8">
        @foreach($inspecteurs as $its)
        <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- En-tête de la carte ITS -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-600 rounded-full">
                            <span class="text-lg font-bold text-white">{{ substr($its->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ $its->name }}</h2>
                            <p class="text-sm text-gray-600">Inspecteur ITS</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-blue-600">{{ $its->recommandations->count() }}</div>
                        <div class="text-sm text-gray-500">recommandations</div>
                    </div>
                </div>
            </div>

            <!-- Tableau des recommandations pour cet ITS -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Référence</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Titre</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Structure</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Priorité</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date limite</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($its->recommandations as $recommandation)
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-medium text-gray-900">{{ $recommandation->reference }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($recommandation->titre, 60) }}</div>
                                <div class="mt-1 text-sm text-gray-500">{{ Str::limit($recommandation->description, 80) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $recommandation->structure->nom }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $prioriteColors = [
                                        'haute' => 'bg-red-100 text-red-800 border-red-200',
                                        'moyenne' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'basse' => 'bg-green-100 text-green-800 border-green-200'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $prioriteColors[$recommandation->priorite] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($recommandation->priorite) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statutColors = [
                                        'point_focal_assigne' => 'bg-purple-100 text-purple-800',
                                        'plan_en_redaction' => 'bg-blue-100 text-blue-800',
                                        'plan_soumis_responsable' => 'bg-orange-100 text-orange-800',
                                        'plan_valide_responsable' => 'bg-teal-100 text-teal-800',
                                        'en_execution' => 'bg-green-100 text-green-800',
                                        'cloturee' => 'bg-gray-100 text-gray-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statutColors[$recommandation->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $recommandation->statut_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                <div class="flex items-center">
                                    {{ $recommandation->date_limite->format('d/m/Y') }}
                                    @if($recommandation->estEnRetard())
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                        🚨 Retard
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <a href="{{ route('point_focal.recommandations.show', $recommandation) }}"
                                       class="flex items-center px-3 py-2 text-blue-600 transition-colors rounded-lg hover:text-blue-900 bg-blue-50 hover:bg-blue-100">
                                        <i class="mr-2 fas fa-folder-open"></i>
                                        Ouvrir
                                    </a>

                                    @if(in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction', 'plan_rejete_responsable']))
                                    <a href="{{ route('point_focal.plans_action.create', $recommandation) }}"
                                       class="flex items-center px-3 py-2 text-green-600 transition-colors rounded-lg hover:text-green-900 bg-green-50 hover:bg-green-100">
                                        <i class="mr-2 fas fa-edit"></i>
                                        Renseigner
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        <!-- Message si aucune recommandation -->
                        @if($its->recommandations->count() === 0)
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <div class="mb-2 text-gray-400">
                                    <i class="text-3xl fas fa-inbox"></i>
                                </div>
                                <p class="text-gray-500">Aucune recommandation pour cet inspecteur</p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pied de carte -->
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Dossier ITS • {{ $its->recommandations->count() }} élément(s)
                    </div>
                    <a href="{{ route('point_focal.dossier.its', $its) }}"
                       class="flex items-center font-medium text-blue-600 hover:text-blue-800">
                        <i class="mr-2 fas fa-external-link-alt"></i>
                        Ouvrir le dossier complet
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Message si aucun ITS -->
        @if($inspecteurs->count() === 0)
        <div class="p-12 text-center bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="mb-4 text-gray-400">
                <i class="text-5xl fas fa-users"></i>
            </div>
            <h3 class="mb-2 text-lg font-medium text-gray-900">Aucun dossier assigné</h3>
            <p class="mb-4 text-gray-500">Aucun inspecteur ITS ne vous a encore assigné de recommandations.</p>
            <p class="text-sm text-gray-400">Les dossiers apparaîtront ici lorsqu'ils vous seront assignés.</p>
        </div>
        @endif
    </div>
</div>
@endsection
