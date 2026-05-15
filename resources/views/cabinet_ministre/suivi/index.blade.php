@extends('layouts.app')

@section('title', 'Suivi Stratégique - Cabinet Ministre')

@section('content')
    <div class="min-h-screen bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="max-w-7xl mx-auto mb-10">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Suivi de la Performance</h1>
            <p class="text-slate-500 text-lg">Vue détaillée sur l'état d'avancement des recommandations.</p>
        </div>

        <div class="max-w-7xl mx-auto">

            <!-- Filters (Glassmorphism) -->
            <div class="bg-white rounded-3xl shadow-lg border border-slate-100 p-6 mb-8">
                <form action="{{ route('cabinet_ministre.suivi.index') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Status Filter -->
                    <div>
                        <label for="statut"
                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Statut</label>
                        <select name="statut" id="statut"
                            class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 text-slate-700 font-medium">
                            <option value="">Tous les statuts</option>
                            <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="cloturee" {{ request('statut') == 'cloturee' ? 'selected' : '' }}>Clôturée</option>
                            <option value="en_attente_validation" {{ request('statut') == 'en_attente_validation' ? 'selected' : '' }}>En attente</option>
                            <option value="en_retard" {{ request('statut') == 'en_retard' ? 'selected' : '' }}>En retard
                            </option>
                        </select>
                    </div>

                    <!-- Priority Filter -->
                    <div>
                        <label for="priorite"
                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Priorité</label>
                        <select name="priorite" id="priorite"
                            class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 text-slate-700 font-medium">
                            <option value="">Toutes les priorités</option>
                            <option value="haute" {{ request('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                            <option value="moyenne" {{ request('priorite') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                            <option value="basse" {{ request('priorite') == 'basse' ? 'selected' : '' }}>Basse</option>
                        </select>
                    </div>

                    <!-- Structure Filter -->
                    <div>
                        <label for="its_id"
                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Structure</label>
                        <select name="its_id" id="its_id"
                            class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 text-slate-700 font-medium">
                            <option value="">Toutes les structures</option>
                            @foreach($listITS as $id => $name)
                                <option value="{{ $id }}" {{ request('its_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Button -->
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition-colors shadow-lg shadow-slate-900/20 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtrer les résultats
                        </button>
                    </div>
                </form>
            </div>

            <!-- Results Table -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Référence & Titre</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Structure Lead</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Priorité</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Statut</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Échéance</th>
                                <th
                                    class="px-8 py-5 text-right text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recommandations as $recommandation)
                                <tr class="group hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-6 max-w-xs">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-xs font-black text-amber-500 tracking-wider mb-1">{{ $recommandation->reference ?? 'REF-001' }}</span>
                                            <p
                                                class="text-sm font-bold text-slate-800 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                                                {{ $recommandation->titre }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-bold text-slate-700">{{ $recommandation->its->name ?? 'Non assigné' }}</span>
                                            <span class="text-xs text-slate-500 mt-0.5">Responsable</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($recommandation->priorite == 'haute')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                Haute
                                            </span>
                                        @elseif($recommandation->priorite == 'moyenne')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                                Moyenne
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                Basse
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($recommandation->statut == 'cloturee')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span> Clôturée
                                            </span>
                                        @elseif($recommandation->statut == 'en_cours')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></span> En Cours
                                            </span>
                                        @elseif($recommandation->statut == 'en_retard')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2 animate-pulse"></span> En
                                                Retard
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ str_replace('_', ' ', ucfirst($recommandation->statut)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        <div
                                            class="text-sm font-bold {{ $recommandation->estEnRetard() ? 'text-red-500' : 'text-slate-600' }}">
                                            {{ $recommandation->date_limite ? $recommandation->date_limite->format('d/m/Y') : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <a href="{{ route('cabinet_ministre.suivi.show', $recommandation->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-indigo-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-12 text-center text-slate-400">
                                        <p class="font-medium">Aucune recommandation trouvée avec ces critères.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50">
                    {{ $recommandations->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection