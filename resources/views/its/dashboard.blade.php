@extends('layouts.app')

@section('title', 'Tableau de Bord ITS')

@section('content')
<div class="min-h-screen py-8 bg-gray-50">
    <div class="mx-auto max-w-7xl">
    {{-- En-tête --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tableau de Bord ITS</h1>
            <p class="text-gray-600">Vue d'ensemble de votre structure</p>
        </div>
        <div class="text-sm text-gray-500">
            {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
            <!-- Total des recommandations -->
            <div class="overflow-hidden bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-blue-100 rounded-md">
                            <i class="text-lg text-blue-600 fas fa-file-alt"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total des recommandations</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $totalRecommandations }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommandations en brouillon -->
            <div class="overflow-hidden bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-md">
                            <i class="text-lg text-yellow-600 fas fa-edit"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">En brouillon</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $brouillonsCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommandations soumises -->
            <div class="overflow-hidden bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-blue-100 rounded-md">
                            <i class="text-lg text-blue-600 fas fa-paper-plane"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Soumises à l'IG</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $soumisesCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommandations en retard -->
            <div class="overflow-hidden bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-red-100 rounded-md">
                            <i class="text-lg text-red-600 fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">En retard</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $enRetardCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Graphique de répartition par statut -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Répartition par statut</h3>
                </div>
                <div class="p-6">
                    @if($statuts->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($statuts as $statut)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statut->color_class }}">
                                        {{ $statut->label }}
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $statut->count }}</span>
                                    <span class="ml-2 text-sm text-gray-500">
                                        ({{ number_format(($statut->count / $totalRecommandations) * 100, 1) }}%)
                                    </span>
                                </div>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded-full">
                                <div class="h-2 rounded-full {{ $statut->color_class }}"
                                     style="width: {{ ($statut->count / $totalRecommandations) * 100 }}%"></div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center text-gray-500">
                            <i class="text-4xl text-gray-300 fas fa-chart-pie"></i>
                            <p class="mt-2">Aucune donnée à afficher</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Répartition par priorité -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Répartition par priorité</h3>
                </div>
                <div class="p-6">
                    @if($priorites->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($priorites as $priorite)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($priorite->priorite == 'haute') bg-red-100 text-red-800
                                        @elseif($priorite->priorite == 'moyenne') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        <i class="mr-1 fas fa-flag"></i>
                                        {{ ucfirst($priorite->priorite) }}
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $priorite->count }}</span>
                                    <span class="ml-2 text-sm text-gray-500">
                                        ({{ number_format(($priorite->count / $totalRecommandations) * 100, 1) }}%)
                                    </span>
                                </div>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded-full">
                                <div class="h-2 rounded-full
                                    @if($priorite->priorite == 'haute') bg-red-500
                                    @elseif($priorite->priorite == 'moyenne') bg-yellow-500
                                    @else bg-green-500 @endif"
                                     style="width: {{ ($priorite->count / $totalRecommandations) * 100 }}%"></div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center text-gray-500">
                            <i class="text-4xl text-gray-300 fas fa-chart-bar"></i>
                            <p class="mt-2">Aucune donnée à afficher</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recommandations récentes -->
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Recommandations récentes</h3>
                    <a href="{{ route('its.recommandations.index') }}"
                       class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Voir toutes
                    </a>
                </div>
            </div>
            <div class="overflow-hidden">
                @if($recentRecommandations->isNotEmpty())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Référence</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Titre</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Statut</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Priorité</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date limite</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentRecommandations as $recommandation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('its.recommandations.show', $recommandation) }}"
                                       class="text-sm font-medium text-blue-600 hover:text-blue-900">
                                        {{ $recommandation->reference }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs text-sm text-gray-900 truncate">{{ $recommandation->titre }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @include('shared.status-badge', ['statut' => $recommandation->statut])
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($recommandation->priorite == 'haute') bg-red-100 text-red-800
                                        @elseif($recommandation->priorite == 'moyenne') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        <i class="mr-1 fas fa-flag"></i>
                                        {{ ucfirst($recommandation->priorite) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $recommandation->date_limite->format('d/m/Y') }}
                                    @if($recommandation->estEnRetard())
                                        <i class="ml-1 text-red-500 fas fa-exclamation-triangle"></i>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="py-12 text-center">
                        <i class="text-4xl text-gray-300 fas fa-inbox"></i>
                        <p class="mt-2 text-gray-500">Aucune recommandation récente</p>
                        <a href="{{ route('its.recommandations.create') }}"
                           class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700">
                            <i class="mr-2 fas fa-plus"></i>
                            Créer une recommandation
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-1 gap-6 mt-8 md:grid-cols-3">
            <a href="{{ route('its.recommandations.create') }}"
               class="flex items-center p-6 transition-shadow bg-white rounded-lg shadow hover:shadow-md">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg">
                    <i class="text-lg text-green-600 fas fa-plus"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Nouvelle recommandation</h3>
                    <p class="text-gray-500">Créer une nouvelle recommandation</p>
                </div>
            </a>

            <a href="{{ route('its.recommandations.index') }}"
               class="flex items-center p-6 transition-shadow bg-white rounded-lg shadow hover:shadow-md">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg">
                    <i class="text-lg text-blue-600 fas fa-list"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Toutes les recommandations</h3>
                    <p class="text-gray-500">Voir et gérer toutes vos recommandations</p>
                </div>
            </a>

            <a href="{{ route('its.rapports.index') }}"
               class="flex items-center p-6 transition-shadow bg-white rounded-lg shadow hover:shadow-md">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-purple-100 rounded-lg">
                    <i class="text-lg text-purple-600 fas fa-chart-bar"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Rapports</h3>
                    <p class="text-gray-500">Générer des rapports et statistiques</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
