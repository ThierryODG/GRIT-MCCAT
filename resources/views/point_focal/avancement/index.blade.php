@extends('layouts.app')

@section('title', 'Suivi de l\'exécution')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Suivi de l'exécution</h1>
                <p class="text-gray-600 mt-1">Gérez l'avancement de vos recommandations assignées</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Dossiers</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $total }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">En Cours</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $enCours }}</p>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-full text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Terminés / Clôture</p>
                        <p class="text-3xl font-bold text-green-600">{{ $termines }}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-full text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            @forelse($recommandations->getCollection()->groupBy('its_id') as $itsId => $group)
                @php
                    $its = $group->first()->its;
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Section Header -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Inspecteur Technique</h2>
                                <p class="text-lg font-bold text-gray-800">{{ $its->name ?? 'Non assigné' }}</p>
                            </div>
                        </div>
                        <span
                            class="px-3 py-1 bg-white border border-gray-200 rounded-full text-xs font-semibold text-gray-500">
                            {{ $group->count() }} Recommandation{{ $group->count() > 1 ? 's' : '' }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-white border-b border-gray-50">
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Référence
                                    </th>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Titre
                                    </th>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Progression</th>
                                    <th
                                        class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($group as $recommandation)
                                    @php
                                        $totalActions = $recommandation->plansAction->count();
                                        $completed = $recommandation->plansAction->where('statut_execution', 'termine')->count();
                                        $percent = $totalActions > 0 ? round(($completed / $totalActions) * 100) : 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-mono text-sm text-gray-600">{{ $recommandation->reference }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-medium text-gray-900 line-clamp-1">{{ $recommandation->titre }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 w-1/4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                                    <div class="h-full bg-blue-600 rounded-full" style="width: {{ $percent }}%">
                                                    </div>
                                                </div>
                                                <span class="text-sm font-medium text-gray-600">{{ $percent }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <a href="{{ route('point_focal.avancement.show', $recommandation) }}"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition shadow-sm">
                                                Gérer l'exécution
                                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="flex flex-col items-center justify-center text-gray-500">
                        <svg class="w-12 h-12 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <p class="text-lg font-medium">Aucun dossier en cours d'exécution</p>
                        <p class="text-sm mt-1">Les dossiers validés par l'IG apparaîtront ici.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($recommandations->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                {{ $recommandations->links() }}
            </div>
        @endif
    </div>
    </div>
@endsection