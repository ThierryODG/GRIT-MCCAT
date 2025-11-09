<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord Point Focal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Recommandations</h3>
                            <div class="text-3xl font-bold">{{ $stats['recommandations'] ?? 0 }}</div>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Plans d'action</h3>
                            <div class="text-3xl font-bold">{{ $stats['plans_action'] ?? 0 }}</div>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Recommandations en retard</h3>
                            <div class="text-3xl font-bold">{{ $stats['recommandations_retard'] ?? 0 }}</div>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold mb-4">Recommandations récentes</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contenu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date limite</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($mesRecommandations as $recommandation)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ Str::limit($recommandation->contenu, 80) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($recommandation->statut === 'validee') bg-green-100 text-green-800
                                                @elseif($recommandation->statut === 'en_attente_validation') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $recommandation->statut)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $recommandation->date_limite ? $recommandation->date_limite->format('d/m/Y') : 'Non définie' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Aucune recommandation récente
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
