@extends('layouts.app')

@section('title', 'Suivi des Recommandations')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Suivi de l'exécution</h1>
            <p class="text-gray-600 mt-2">Suivi des recommandations par structure</p>
        </div>

        @if($structures->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-clipboard-check text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Aucune recommandation en cours de suivi</h3>
                <p class="text-gray-500 mt-1">Les recommandations validées apparaîtront ici.</p>
            </div>
        @else
            <div class="space-y-8">
                @foreach($structures as $structureId => $data)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Structure Header -->
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                    {{ $data['info']->sigle }}
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-800">{{ $data['info']->nom }}</h2>
                                    <span class="text-sm text-gray-500">{{ $data['recommandations']->count() }} dossiers
                                        suivis</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recommendations List -->
                        <div class="divide-y divide-gray-50">
                            @foreach($data['recommandations'] as $recommandation)
                                @php
                                    $total = $recommandation->plansAction->count();
                                    $done = $recommandation->plansAction->where('statut_execution', 'termine')->count();
                                    $percent = $total > 0 ? round(($done / $total) * 100) : 0;
                                @endphp
                                <div class="p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span
                                                    class="text-xs font-mono text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $recommandation->reference }}</span>
                                                <span
                                                    class="text-xs px-2 py-0.5 rounded-full 
                                                                {{ $recommandation->statut === 'demande_cloture' ? 'bg-purple-100 text-purple-800' :
                                ($percent >= 100 ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800') }}">
                                                    {{ $recommandation->statut === 'demande_cloture' ? 'Demande de Clôture' : ($percent >= 100 ? 'Terminé' : 'En cours') }}
                                                </span>
                                            </div>
                                            <h3 class="text-base font-semibold text-gray-800 mb-2">{{ $recommandation->titre }}</h3>
                                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                                <span><i class="fas fa-user-tie mr-1"></i>
                                                    {{ $recommandation->pointFocal->name ?? 'Non assigné' }}</span>
                                                <span><i class="fas fa-calendar mr-1"></i> Fin:
                                                    {{ $recommandation->date_limite->format('d/m/Y') }}</span>
                                            </div>
                                        </div>

                                        <div class="w-full md:w-1/3 flex items-center gap-4">
                                            <div class="flex-1">
                                                <div class="flex justify-between text-xs mb-1">
                                                    <span class="font-medium text-gray-700">Progression</span>
                                                    <span class="font-bold text-blue-600">{{ $percent }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500"
                                                        style="width: {{ $percent }}%"></div>
                                                </div>
                                            </div>
                                            <a href="{{ route('inspecteur_general.suivi.show', $recommandation) }}"
                                                class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition shadow-sm text-sm font-medium whitespace-nowrap">
                                                Détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection