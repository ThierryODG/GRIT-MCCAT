@extends('layouts.app')

@section('title', 'Synthèse Stratégique - Rapport')

@section('content')
    <div class="min-h-screen bg-slate-100 py-12 px-4 print:bg-white print:p-0">

        <!-- Floating Action Button (Print) -->
        <div class="fixed bottom-8 right-8 print:hidden z-50">
            <button onclick="window.print()"
                class="flex items-center justify-center w-16 h-16 bg-slate-900 text-amber-500 rounded-full shadow-2xl hover:bg-slate-800 transition-transform hover:scale-105">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
            </button>
        </div>

        <!-- Report container (A4-ish style) -->
        <div
            class="max-w-[210mm] mx-auto bg-white shadow-2xl print:shadow-none min-h-[297mm] p-12 relative overflow-hidden">

            <!-- Watermark -->
            <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none">
                <div class="w-96 h-96 rounded-full border-[20px] border-slate-900"></div>
            </div>

            <!-- Official Header -->
            <div class="text-center border-b-2 border-slate-900 pb-8 mb-10 relative z-10">
                <h2 class="text-sm font-bold uppercase tracking-[0.2em] text-slate-500 mb-2">Burkina Faso</h2>
                <!-- Motto removed -->
                <!-- Motto is confusing, using User's latest or standard? Sticking to User's request 'La Patrie ou la Mort' elsewhere, but standard for docs? Let's use the one requested: La Patrie ou la Mort -->
                <!-- Correction per user request -->
                <p class="text-xs font-bold uppercase tracking-[0.1em] text-amber-600 mb-6">La Patrie ou la Mort, nous
                    vaincrons</p>

                <h1 class="text-4xl font-black text-slate-900 uppercase">Rapport de Synthèse</h1>
                <p class="text-lg text-slate-600 mt-2 font-serif italic">Suivi des Recommandations Stratégiques</p>
            </div>

            <!-- Meta Info -->
            <div class="flex justify-between items-end mb-12 text-sm">
                <div>
                    <p class="text-slate-500 uppercase tracking-wider font-bold text-xs">Période concernée</p>
                    <p class="font-bold text-slate-900 text-lg">
                        {{ \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') }}
                        <span class="text-slate-400 px-2">-</span>
                        {{ \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-slate-500 uppercase tracking-wider font-bold text-xs">Date du rapport</p>
                    <p class="font-bold text-slate-900">{{ date('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Executive Summary (Stats) -->
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-8 mb-10 break-inside-avoid">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-200 pb-2">
                    Indicateurs Clés</h3>
                <div class="grid grid-cols-4 gap-4 text-center">
                    <div>
                        <span class="block text-4xl font-black text-slate-900 mb-1">{{ $statistiques['total'] }}</span>
                        <span class="text-xs font-bold text-slate-500 uppercase">Total</span>
                    </div>
                    <div>
                        <span
                            class="block text-4xl font-black text-emerald-600 mb-1">{{ $statistiques['cloturees'] }}</span>
                        <span class="text-xs font-bold text-emerald-600 uppercase">Clôturées</span>
                    </div>
                    <div>
                        <span class="block text-4xl font-black text-blue-600 mb-1">{{ $statistiques['en_cours'] }}</span>
                        <span class="text-xs font-bold text-blue-600 uppercase">En Cours</span>
                    </div>
                    <div>
                        <span class="block text-4xl font-black text-red-600 mb-1">{{ $statistiques['en_retard'] }}</span>
                        <span class="text-xs font-bold text-red-600 uppercase">Critiques</span>
                    </div>
                </div>
                <!-- Progress Bar -->
                <div class="mt-8">
                    <div class="flex justify-between text-xs font-bold text-slate-600 mb-2">
                        <span>Performance Globale</span>
                        <span>{{ $statistiques['total'] > 0 ? round(($statistiques['cloturees'] / $statistiques['total']) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-slate-900 h-2 rounded-full"
                            style="width: {{ $statistiques['total'] > 0 ? ($statistiques['cloturees'] / $statistiques['total']) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analysis Text -->
            <div class="mb-10 break-inside-avoid">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4">Analyse de la Performance</h3>
                <p class="text-slate-700 leading-relaxed text-justify">
                    Sur la période analysée, le taux d'exécution global s'établit à
                    <strong>{{ $statistiques['total'] > 0 ? round(($statistiques['cloturees'] / $statistiques['total']) * 100) : 0 }}%</strong>.
                    Nous notons <strong>{{ $statistiques['en_retard'] }}</strong> recommandations accusant un retard
                    critique nécessitant un arbitrage immédiat.
                    La structure la plus sollicitée contribue à hauteur de {{ $parStructure->first() ?? 0 }} dossiers.
                </p>
            </div>

            <!-- Detailed Table -->
            <div class="break-inside-auto">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4">Détail des Recommandations</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-slate-900">
                            <th class="py-3 text-xs font-bold text-slate-900 uppercase w-1/4">Référence</th>
                            <th class="py-3 text-xs font-bold text-slate-900 uppercase w-1/2">Intitulé & Responsable</th>
                            <th class="py-3 text-xs font-bold text-slate-900 uppercase text-right w-1/4">Statut & Échéance
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @foreach($recommandations as $recommandation)
                            <tr class="border-b border-slate-100 break-inside-avoid">
                                <td class="py-4 align-top">
                                    <span class="font-bold block">{{ $recommandation->reference ?? 'N/A' }}</span>
                                    <span class="text-xs text-slate-500">{{ ucfirst($recommandation->priorite) }}</span>
                                </td>
                                <td class="py-4 align-top pr-4">
                                    <p class="font-medium mb-1">{{ $recommandation->titre }}</p>
                                    <p class="text-xs text-slate-500 bg-slate-50 inline-block px-1 rounded">
                                        {{ $recommandation->its->name ?? 'Structure' }}
                                    </p>
                                </td>
                                <td class="py-4 align-top text-right">
                                    @if($recommandation->statut == 'cloturee')
                                        <span class="font-bold text-emerald-600">Clôturée</span>
                                    @elseif($recommandation->estEnRetard())
                                        <span class="font-bold text-red-600">En Retard</span>
                                    @else
                                        <span class="font-bold text-blue-600">En Cours</span>
                                    @endif
                                    <p class="text-xs text-slate-400 mt-1">
                                        {{ $recommandation->date_limite ? $recommandation->date_limite->format('d/m/Y') : '-' }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="mt-12 border-t border-slate-100 pt-6 flex justify-between text-xs text-slate-400">
                <span>GRIT - Système de Suivi Stratégique</span>
                <span>Page imprimée le {{ date('d/m/Y H:i') }}</span>
            </div>

        </div>
    </div>
@endsection