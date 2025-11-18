@extends('layouts.app')

@section('title', 'Gestion des Points Focaux')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Gestion des Points Focaux</h1>
        <p class="mt-1 text-gray-600">Assignation des ITS aux points focaux de votre structure</p>
    </div>

    <!-- Messages de statut -->
    @if(session('success'))
    <div class="p-4 mb-6 border border-green-200 rounded-lg bg-green-50">
        <div class="flex items-center">
            <i class="mr-3 text-green-500 fas fa-check-circle"></i>
            <p class="font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 mb-6 border border-red-200 rounded-lg bg-red-50">
        <div class="flex items-center">
            <i class="mr-3 text-red-500 fas fa-exclamation-triangle"></i>
            <p class="font-medium text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Section d'assignation -->
    <div class="p-6 mb-8 bg-white border border-gray-200 rounded-lg shadow-sm">
        <h2 class="mb-4 text-lg font-semibold text-gray-900">Nouvelle Assignation</h2>

        <form method="POST" action="{{ route('responsable.points_focaux.assigner') }}" class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @csrf

            <!-- Sélection ITS -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Inspecteur ITS</label>
                <select name="its_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">Sélectionnez un ITS</option>
                    @foreach($itsList as $its)
                    <option value="{{ $its->id }}">
                        {{ $its->name }} ({{ $its->nb_recommandations }} recommandations validées)
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Sélection Point Focal -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Point Focal</label>
                <select name="point_focal_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">Choisissez un point focal</option>
                    @foreach($pointsFocaux as $pointFocal)
                    <option value="{{ $pointFocal->id }}">
                        {{ $pointFocal->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Bouton d'assignation -->
            <div class="flex items-end">
                <button type="submit" class="flex items-center justify-center w-full px-4 py-2 font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="mr-2 fas fa-user-check"></i>Assigner
                </button>
            </div>
        </form>
    </div>

    <!-- Section des assignations existantes -->
    <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Assignations en cours</h2>
            <p class="mt-1 text-sm text-gray-600">ITS avec leurs points focaux assignés</p>
        </div>

        <div class="overflow-x-auto">
            @if($assignations->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Inspecteur ITS</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Point Focal Assigné</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Recommandations</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date d'assignation</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($assignations as $itsId => $assignation)
                    <tr class="transition-colors hover:bg-gray-50">
                        <!-- ITS -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 mr-3 bg-blue-100 rounded-full">
                                    <i class="text-blue-600 fas fa-user-shield"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $assignation['its']->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $assignation['its']->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Point Focal -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 mr-3 bg-green-100 rounded-full">
                                    <i class="text-green-600 fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $assignation['point_focal']->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $assignation['point_focal']->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Recommandations -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-800 bg-blue-100 rounded-full">
                                {{ $assignation['nb_recommandations'] }} recommandation(s)
                            </span>
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($assignation['date_assignation'])->format('d/m/Y H:i') }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <div class="flex space-x-2">
                                <!-- Formulaire de réassignation -->
                                <form method="POST" action="{{ route('responsable.points_focaux.reassigner', $itsId) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="point_focal_id" class="px-2 py-1 mr-2 text-sm border border-gray-300 rounded" onchange="this.form.submit()">
                                        <option value="">Changer de PF</option>
                                        @foreach($pointsFocaux as $pointFocal)
                                        <option value="{{ $pointFocal->id }}">{{ $pointFocal->name }}</option>
                                        @endforeach
                                    </select>
                                </form>

                                <!-- Bouton retirer -->
                                <form method="POST" action="{{ route('responsable.points_focaux.retirer', $itsId) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Êtes-vous sûr de vouloir retirer l\\'assignation pour cet ITS ?')"
                                            class="px-3 py-1 text-sm text-red-600 transition-colors rounded hover:text-red-900 bg-red-50 hover:bg-red-100">
                                        <i class="mr-1 fas fa-times"></i>Retirer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="py-12 text-center">
                <div class="mb-4 text-gray-400">
                    <i class="text-4xl fas fa-users"></i>
                </div>
                <h3 class="mb-2 text-lg font-medium text-gray-900">Aucune assignation en cours</h3>
                <p class="text-gray-500">Utilisez le formulaire ci-dessus pour assigner un point focal à un ITS.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Section ITS sans assignation -->
    @if($itsList->whereNotIn('id', $assignations->keys())->count() > 0)
    <div class="mt-8 overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 bg-orange-50">
            <h2 class="text-lg font-semibold text-gray-900">ITS en attente d'assignation</h2>
            <p class="mt-1 text-sm text-gray-600">ITS avec des recommandations validées mais sans point focal assigné</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($itsList->whereNotIn('id', $assignations->keys()) as $its)
                <div class="p-4 transition-colors border border-gray-200 rounded-lg hover:border-orange-300">
                    <div class="flex items-center mb-3">
                        <div class="flex items-center justify-center w-8 h-8 mr-3 bg-orange-100 rounded-full">
                            <i class="text-sm text-orange-600 fas fa-user-shield"></i>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $its->name }}</div>
                            <div class="text-xs text-gray-500">{{ $its->nb_recommandations }} recommandation(s)</div>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        En attente d'assignation
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
