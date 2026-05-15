@extends('layouts.app')

@section('title', 'Génération de Rapports - Cabinet Ministre')

@section('content')
    <div class="min-h-screen bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="max-w-4xl mx-auto mb-10 text-center">
            <span
                class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-600 text-xs font-bold uppercase tracking-widest border border-amber-500/20 mb-4 inline-block">
                Intelligence Décisionnelle
            </span>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-4">Générateur de Rapports Stratégiques</h1>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">
                Générez des synthèses détaillées sur l'état d'exécution des recommandations pour le Conseil des Ministres ou
                les revues de performance.
            </p>
        </div>

        <div class="max-w-3xl mx-auto">

            <!-- Generator Card -->
            <div
                class="bg-white rounded-[2rem] shadow-2xl shadow-slate-200 border border-slate-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-slate-900 via-blue-800 to-amber-500">
                </div>

                <div class="p-6 pb-0">
                    <a href="{{ route('cabinet_ministre.rapports.index') }}"
                        class="inline-flex items-center text-slate-500 hover:text-amber-600 font-bold transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Retour aux archives
                    </a>
                </div>

                <form action="{{ route('cabinet_ministre.rapports.store') }}" method="POST" class="p-8 md:p-12 pt-6">
                    @csrf

                    <!-- Date Range Selection -->
                    <div class="mb-10">
                        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                            <span
                                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 mr-3 text-sm">1</span>
                            Période d'Analyse
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="date_debut" class="block text-sm font-bold text-slate-500 mb-2">Du</label>
                                <input type="date" name="date_debut" id="date_debut" required
                                    class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 py-3 text-slate-800 font-bold bg-slate-50/50">
                            </div>
                            <div>
                                <label for="date_fin" class="block text-sm font-bold text-slate-500 mb-2">Au</label>
                                <input type="date" name="date_fin" id="date_fin" required value="{{ date('Y-m-d') }}"
                                    class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 py-3 text-slate-800 font-bold bg-slate-50/50">
                            </div>
                        </div>
                    </div>

                    <div class="w-full h-px bg-slate-100 mb-10"></div>

                    <!-- Filters -->
                    <div class="mb-12">
                        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                            <span
                                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 mr-3 text-sm">2</span>
                            Critères de Filtrage
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="statut" class="block text-sm font-bold text-slate-500 mb-2">Statut des
                                    Recommandations</label>
                                <select name="statut" id="statut"
                                    class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 py-3 text-slate-800 font-medium">
                                    <option value="">Tous les statuts</option>
                                    <option value="en_cours">En Cours d'Exécution</option>
                                    <option value="cloturee">Clôturées / Terminées</option>
                                    <option value="en_retard">En Retard Critique</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-500 mb-2">Format de Sortie</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="format" value="web" checked class="peer sr-only">
                                        <div
                                            class="rounded-xl border-2 border-slate-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 p-3 text-center transition-all h-full flex items-center justify-center">
                                            <span
                                                class="block text-sm font-bold text-slate-600 peer-checked:text-amber-700">Aperçu
                                                Web</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="format" value="archive" class="peer sr-only">
                                        <div
                                            class="rounded-xl border-2 border-slate-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 p-3 text-center transition-all h-full flex items-center justify-center">
                                            <span
                                                class="block text-sm font-bold text-slate-600 peer-checked:text-amber-700">Générer
                                                & Archiver (PDF)</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action -->
                    <button type="submit"
                        class="group w-full py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl shadow-xl shadow-slate-900/20 transition-all transform hover:-translate-y-1 relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-white/10 group-hover:translate-x-full transition-transform duration-700 transform -skew-x-12 -translate-x-full">
                        </div>
                        <span class="relative flex items-center justify-center font-black text-lg tracking-wide uppercase">
                            <svg class="w-6 h-6 mr-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Générer le Rapport
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection