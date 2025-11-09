<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Validation des Plans d\'Action') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($plansAction->isEmpty())
                        <div class="text-center py-8">
                            <div class="mb-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">Aucun plan à valider</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Il n'y a actuellement aucun plan d'action en attente de validation.
                            </p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Recommandation
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Point Focal
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Date Soumission
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                                    @foreach($plansAction as $plan)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-normal">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ Str::limit($plan->recommandation->libelle, 100) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $plan->pointFocal->name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $plan->pointFocal->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $plan->updated_at->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $plan->updated_at->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex space-x-3">
                                                    <button type="button"
                                                            onclick="document.getElementById('modal-plan-{{ $plan->id }}').showModal()"
                                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Voir détails
                                                    </button>
                                                    <form action="{{ route('responsable.validation_plans.valider', $plan) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                            Valider
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('responsable.validation_plans.rejeter', $plan) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                            Rejeter
                                                        </button>
                                                    </form>
                                                </div>

                                                <dialog id="modal-plan-{{ $plan->id }}" class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20 bg-gray-500 bg-opacity-75">
                                                    <div class="bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6">
                                                        <div class="sm:flex sm:items-start">
                                                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                                                    Détails du Plan d'Action
                                                                </h3>
                                                                <div class="mt-4">
                                                                    <div class="space-y-4">
                                                                        <div>
                                                                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description du plan</h4>
                                                                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $plan->description }}</p>
                                                                        </div>
                                                                        <div>
                                                                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de début</h4>
                                                                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $plan->date_debut?->format('d/m/Y') ?? 'Non définie' }}</p>
                                                                        </div>
                                                                        <div>
                                                                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de fin prévue</h4>
                                                                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $plan->date_fin_prevue?->format('d/m/Y') ?? 'Non définie' }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                                            <button type="button" onclick="document.getElementById('modal-plan-{{ $plan->id }}').close()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                                                Fermer
                                                            </button>
                                                        </div>
                                                    </div>
                                                </dialog>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="fixed bottom-4 right-4">
            <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg shadow-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
