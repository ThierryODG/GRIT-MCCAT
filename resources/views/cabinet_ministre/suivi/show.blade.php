@extends('layouts.app')

@section('title', 'Détail du Suivi')

@section('content')
<div class="container px-4 py-6 mx-auto" x-data="{ currentStep: 0 }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Suivi : {{ $recommandation->reference }}
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-sm text-gray-600">{{Str::limit($recommandation->titre, 80)}}</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('cabinet_ministre.suivi.index') }}" class="mr-4 text-gray-500 hover:text-gray-700">
                <i class="mr-1 fas fa-arrow-left"></i> Retour
            </a>
            <div class="text-right">
                <span class="block text-sm text-gray-500">Progression Globale</span>
                <span class="text-xl font-bold text-blue-600">{{ $globalProgress }}%</span>
            </div>
            <div class="w-32 h-2 overflow-hidden bg-gray-200 rounded-full">
                <div class="h-full bg-blue-600" style="width: {{ $globalProgress }}%"></div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-200px)]">
        <!-- Sidebar Steps -->
        <div class="w-full overflow-y-auto bg-white border border-gray-100 shadow-sm lg:w-1/4 rounded-xl">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-700">Actions planifiées</h3>
                <p class="mt-1 text-xs text-gray-500">{{ $recommandation->plansAction->count() }} étapes</p>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($recommandation->plansAction as $index => $action)
                    <button
                        @click="currentStep = {{ $index }}"
                        class="relative w-full p-4 text-left transition-colors hover:bg-gray-50 group"
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
                                <span class="text-xs font-bold tracking-wider text-gray-400 uppercase">Étape {{ $index + 1 }}</span>
                                <h4 class="text-sm font-medium text-gray-800 line-clamp-2">{{ $action->action }}</h4>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>

            <!-- Pièces jointes (En dehors de la boucle d'actions) -->
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                <h3 class="flex items-center mb-3 text-sm font-semibold text-gray-700">
                    <i class="mr-2 text-blue-500 fas fa-paperclip"></i>
                    Pièces Jointes
                </h3>
                @if($recommandation->documents && $recommandation->documents->count() > 0)
                    <div class="space-y-2">
                        @foreach($recommandation->documents as $doc)
                            <div class="flex items-center justify-between p-2 transition-colors bg-white border border-gray-100 rounded hover:bg-gray-50 group">
                                <div class="flex items-center min-w-0">
                                    <div class="truncate">
                                        <p class="text-xs font-medium text-gray-900 truncate" title="{{ $doc->file_name }}">
                                            {{ $doc->file_name }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('cabinet_ministre.suivi.document.download', $doc->id) }}"
                                   class="ml-2 text-gray-400 transition-colors hover:text-blue-600"
                                   title="Télécharger">
                                    <i class="fas fa-download fa-sm"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="py-2 text-xs italic text-center text-gray-400">Aucune pièce jointe</p>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col w-full bg-white border border-gray-100 shadow-sm lg:w-3/4 rounded-xl">
            @foreach($recommandation->plansAction as $index => $action)
                <div x-show="currentStep === {{ $index }}" class="flex flex-col flex-1 h-full p-8 overflow-y-auto">
                    <!-- Action Details -->
                    <div class="mb-8">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-4">
                            Action #{{ $index + 1 }}
                        </span>
                        <h2 class="mb-4 text-2xl font-bold text-gray-900">{{ $action->action }}</h2>

                        <div class="grid grid-cols-1 gap-6 p-6 border border-gray-100 rounded-lg md:grid-cols-3 bg-gray-50">
                            <div>
                                <h5 class="mb-1 text-xs font-bold tracking-wider text-gray-400 uppercase">Indicateurs</h5>
                                <p class="text-gray-700">{{ $action->indicateurs ?? 'Non défini' }}</p>
                            </div>
                            <div>
                                <h5 class="mb-1 text-xs font-bold tracking-wider text-gray-400 uppercase">Exécutant</h5>
                                <div class="text-gray-900">
                                    @if($action->executant_type === 'autre')
                                        <p class="font-medium">{{ $action->executant_nom }}</p>
                                        @if($action->executant_role)
                                            <p class="text-xs text-gray-500">{{ $action->executant_role }}</p>
                                        @endif
                                    @else
                                        <p class="font-medium">Point Focal</p>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-1 text-xs font-bold tracking-wider text-gray-400 uppercase">Échéance</h5>
                                <p class="text-gray-700">{{ $action->date_fin_prevue ? \Carbon\Carbon::parse($action->date_fin_prevue)->format('d/m/Y') : 'Non définie' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Execution Status -->
                    <div class="pt-8 mt-auto border-t border-gray-100">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800">État d'avancement</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">Commentaire d'exécution (Point Focal)</label>
                                <div class="w-full p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-700 italic min-h-[100px]">
                                    {{ $action->commentaire_avancement ?? 'Aucun commentaire pour le moment.' }}
                                </div>
                            </div>

                            <!-- Preuves d'exécution -->
                            <div class="pt-4 mt-4 border-t border-gray-100">
                                <h4 class="mb-2 text-sm font-semibold text-gray-700">Preuves d'exécution</h4>
                                @if($action->preuvesExecution->count() > 0)
                                    <ul class="space-y-2">
                                        @foreach($action->preuvesExecution as $preuve)
                                            <li class="flex items-center text-sm">
                                                <i class="mr-2 text-gray-400 fas fa-file-alt"></i>
                                                <a href="{{ route('point_focal.avancement.download_preuve', $preuve) }}" class="text-blue-600 hover:underline" target="_blank">
                                                    {{ $preuve->file_name }}
                                                </a>
                                                <span class="ml-2 text-xs text-gray-400">({{ $preuve->created_at->format('d/m/Y') }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm italic text-gray-500">Aucune preuve jointe.</p>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <span class="text-sm text-gray-600">Statut actuel :</span>
                                @if($action->statut_execution === 'termine')
                                    <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">Terminé</span>
                                @elseif($action->statut_execution === 'en_cours')
                                    <span class="px-3 py-1 text-sm font-medium text-orange-800 bg-orange-100 rounded-full">En cours</span>
                                @else
                                    <span class="px-3 py-1 text-sm font-medium text-gray-800 bg-gray-100 rounded-full">Non démarré</span>
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
