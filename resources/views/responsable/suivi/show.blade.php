<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Détails de la Recommandation') }}
            </h2>
            <div class="space-x-4">
                <a href="{{ route('responsable.suivi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Retour') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                        Informations de la Recommandation
                    </h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Libellé</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $recommandation->libelle }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Point Focal</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $recommandation->pointFocal->name ?? 'Non assigné' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de création</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $recommandation->created_at->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($recommandation->planAction?->status === 'en_cours') bg-yellow-100 text-yellow-800
                                    @elseif($recommandation->planAction?->status === 'termine') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $recommandation->planAction?->status ? ucfirst(str_replace('_', ' ', $recommandation->planAction->status)) : 'En attente' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if($recommandation->planAction)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                        Plan d'Action
                    </h3>
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $recommandation->planAction->description }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Progression</dt>
                            <dd class="mt-1">
                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $recommandation->planAction->progression }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $recommandation->planAction->progression }}%
                                </div>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de début</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $recommandation->planAction->date_debut?->format('d/m/Y') ?? 'Non définie' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de fin prévue</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $recommandation->planAction->date_fin_prevue?->format('d/m/Y') ?? 'Non définie' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                        Historique des Rapports
                    </h3>
                    @if($recommandation->rapports->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">Aucun rapport disponible.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($recommandation->rapports as $rapport)
                                <div class="border dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $rapport->created_at->format('d/m/Y H:i') }}
                                        </div>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($rapport->type) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $rapport->contenu }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
