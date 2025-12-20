@extends('layouts.app')

@section('title', 'Avancement des Plans d\'Action')

@section('content')
<div class="container py-8 mx-auto">
    <h1 class="mb-6 text-2xl font-bold text-gray-800">Avancement des Plans d'Action</h1>

    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4">
        <div class="p-4 text-center bg-white rounded-lg shadow">
            <div class="text-sm text-gray-500">Total</div>
            <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $totalCount ?? 0 }}</div>
        </div>
        <div class="p-4 text-center bg-white rounded-lg shadow">
            <div class="text-sm text-gray-500">Non démarrés</div>
            <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $notStarted ?? 0 }}</div>
        </div>
        <div class="p-4 text-center bg-white rounded-lg shadow">
            <div class="text-sm text-gray-500">En cours</div>
            <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $inProgress ?? 0 }}</div>
        </div>
        <div class="p-4 text-center bg-white rounded-lg shadow">
            <div class="text-sm text-gray-500">Terminés</div>
            <div class="mt-2 text-2xl font-semibold text-gray-900">{{ $done ?? 0 }}</div>
        </div>
    </div>

    <div class="space-y-4">
        @if($plansActions->count() === 0)
            <div class="p-6 bg-white rounded-lg shadow">
                <p class="text-gray-600">Aucun plan d'action en attente d'exécution pour le moment.</p>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($plansActions as $plan)
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $plan->recommandation->reference }} — Action #{{ $loop->iteration }}</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ Str::limit($plan->action, 200) }}</p>
                            <p class="mt-2 text-sm text-gray-600"><strong>Recommandation :</strong> {{ Str::limit($plan->recommandation->titre, 80) }}</p>
                        </div>

                        <div class="text-right">
                            <div class="mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $plan->statut_execution == 'termine' ? 'bg-green-100 text-green-800' : ($plan->statut_execution == 'en_cours' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $plan->statut_execution_label }}
                                </span>
                            </div>
                            <div class="mb-2 text-sm text-gray-700">
                                <strong>{{ $plan->pourcentage_avancement ?? 0 }}%</strong>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('point_focal.avancement.edit', $plan) }}" class="inline-flex items-center px-3 py-1 text-sm text-white bg-indigo-600 rounded hover:bg-indigo-700">
                                    <i class="mr-2 fas fa-edit"></i>
                                    Mettre à jour
                                </a>
                                <a href="{{ route('point_focal.recommandations.show', $plan->recommandation) }}" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                                    <i class="mr-2 fas fa-folder-open"></i>
                                    Ouvrir dossier
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($plan->commentaire_avancement)
                    <div class="p-3 mt-3 text-sm text-gray-700 border border-gray-100 rounded bg-gray-50">
                        <strong>Dernier commentaire :</strong>
                        <p class="mt-1 whitespace-pre-line">{{ $plan->commentaire_avancement }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $plansActions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
