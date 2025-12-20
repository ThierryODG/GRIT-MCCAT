@extends('layouts.app')

@section('title', 'Détail du Suivi')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="{ currentStep: 0 }">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Suivi : {{ $recommandation->reference }}
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600">{{ $recommandation->structure->nom }}</span>
                <span class="text-gray-400">|</span>
                <span class="text-gray-600 text-sm">{{Str::limit($recommandation->titre, 60)}}</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('inspecteur_general.suivi.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fas fa-arrow-left mr-1"></i> Retour
            </a>
            <div class="text-right">
                <span class="block text-sm text-gray-500">Progression Globale</span>
                <span class="text-xl font-bold text-blue-600">{{ $globalProgress }}%</span>
            </div>
            <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-blue-600" style="width: {{ $globalProgress }}%"></div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-200px)]">
        <!-- Sidebar Steps -->
        <div class="w-full lg:w-1/4 bg-white rounded-xl shadow-sm border border-gray-100 overflow-y-auto">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-700">Actions planifiées</h3>
                <p class="text-xs text-gray-500 mt-1">{{ $recommandation->plansAction->count() }} étapes</p>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($recommandation->plansAction as $index => $action)
                    <button 
                        @click="currentStep = {{ $index }}"
                        class="w-full text-left p-4 hover:bg-gray-50 transition-colors relative group"
                        :class="{'bg-blue-50 border-l-4 border-blue-600': currentStep === {{ $index }}, 'border-l-4 border-transparent': currentStep !== {{ $index }}}"
                    >
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-1">
                                <!-- Status Icon -->
                                @if($action->statut_execution === 'termine')
                                    <span class="text-green-500">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </span>
                                @elseif($action->statut_execution === 'en_cours')
                                    <span class="text-orange-500">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                    </span>
                                @else
                                    <span class="text-gray-300">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"></path></svg>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Étape {{ $index + 1 }}</span>
                                <h4 class="text-sm font-medium text-gray-800 line-clamp-2">{{ $action->action }}</h4>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full lg:w-3/4 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
            @foreach($recommandation->plansAction as $index => $action)
                <div x-show="currentStep === {{ $index }}" class="p-8 flex-1 flex flex-col h-full overflow-y-auto">
                    <!-- Action Details -->
                    <div class="mb-8">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-4">
                            Action #{{ $index + 1 }}
                        </span>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $action->action }}</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-lg border border-gray-100">
                            <div>
                                <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Indicateurs</h5>
                                <p class="text-gray-700">{{ $action->indicateurs ?? 'Non défini' }}</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Échéance</h5>
                                <p class="text-gray-700">{{ $action->date_fin_prevue ? \Carbon\Carbon::parse($action->date_fin_prevue)->format('d/m/Y') : 'Non définie' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Execution Status -->
                    <div class="mt-auto border-t border-gray-100 pt-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">État d'avancement</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire d'exécution (Point Focal)</label>
                                <div class="w-full p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-700 italic min-h-[100px]">
                                    {{ $action->commentaire_avancement ?? 'Aucun commentaire pour le moment.' }}
                                </div>
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <span class="text-sm text-gray-600">Statut actuel :</span>
                                @if($action->statut_execution === 'termine')
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Terminé</span>
                                @elseif($action->statut_execution === 'en_cours')
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">En cours</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">Non démarré</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
