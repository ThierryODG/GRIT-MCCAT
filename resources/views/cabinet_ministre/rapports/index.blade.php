@extends('layouts.app')

@section('title', 'Rapports Ministériels - Consultation')

@section('content')
    <div class="min-h-screen bg-[#f8fafc]">
        <!-- Hero Header -->
        <div class="bg-slate-900 pt-10 pb-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-amber-500/10 to-transparent"></div>
            <div class="relative z-10 max-w-7xl mx-auto flex justify-between items-end">
                <div>
                    <span
                        class="inline-block py-1 px-3 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-xs font-bold uppercase tracking-widest mb-4">
                        Archives Stratégiques
                    </span>
                    <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">
                        Rapports & Synthèses
                    </h1>
                    <p class="mt-4 text-slate-400 max-w-2xl text-lg">
                        Consultez l'historique des rapports de performance et générez de nouvelles synthèses pour le
                        Cabinet.
                    </p>
                </div>
                <div class="hidden md:block">
                    <a href="{{ route('cabinet_ministre.rapports.create') }}"
                        class="group flex items-center px-6 py-4 bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold rounded-xl transition-all shadow-lg shadow-amber-500/20 transform hover:-translate-y-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Nouveau Rapport
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Container -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-20">

            <!-- Mobile Action Button -->
            <div class="md:hidden mb-8">
                <a href="{{ route('cabinet_ministre.rapports.create') }}"
                    class="flex items-center justify-center w-full px-6 py-4 bg-amber-500 text-slate-900 font-bold rounded-xl shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouveau Rapport
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-start">
                    <svg class="w-6 h-6 text-emerald-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-bold text-emerald-800">Succès</h3>
                        <p class="text-sm text-emerald-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Reports Grid -->
            <div class="grid grid-cols-1 gap-8">
                @forelse($rapportsParAnnee as $annee => $rapports)
                    <div x-data="{ open: true }"
                        class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100">
                        <!-- Year Header -->
                        <div @click="open = !open"
                            class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-10 h-10 rounded-lg bg-slate-900 text-amber-500 flex items-center justify-center font-black text-sm shadow-md">
                                    {{ $annee }}
                                </div>
                                <span class="font-bold text-slate-700 text-lg">Exercice {{ $annee }}</span>
                                <span
                                    class="text-xs font-bold px-2 py-1 bg-slate-200 text-slate-600 rounded-md">{{ $rapports->count() }}
                                    rapports</span>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-300 transform"
                                :class="{'rotate-180': !open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <!-- Reports List -->
                        <div x-show="open" x-transition class="divide-y divide-slate-50">
                            @foreach($rapports as $rapport)
                                <div
                                    class="group p-6 flex items-center justify-between hover:bg-slate-50/80 transition-all duration-200">
                                    <div class="flex items-center space-x-5">
                                        <div
                                            class="flex-shrink-0 w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500 group-hover:bg-red-100 group-hover:scale-110 transition-all duration-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4
                                                class="text-base font-bold text-slate-800 mb-1 group-hover:text-amber-600 transition-colors">
                                                {{ $rapport->titre }}</h4>
                                            <div class="flex items-center text-xs text-slate-500 space-x-3">
                                                <span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg> {{ $rapport->created_at->format('d/m/Y') }}</span>
                                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                <span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg> {{ $rapport->user->name ?? 'Système' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('cabinet_ministre.rapports.show', $rapport->id) }}"
                                            class="flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-600 font-bold text-sm rounded-lg hover:border-amber-500 hover:text-amber-600 transition-all shadow-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Télécharger
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-[2rem] p-12 text-center shadow-xl shadow-slate-200/50 border border-slate-100">
                        <div
                            class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Aucun rapport archivé</h3>
                        <p class="text-slate-500 mb-8 max-w-sm mx-auto">Commencez par générer un rapport stratégique pour
                            alimenter votre bibliothèque.</p>
                        <a href="{{ route('cabinet_ministre.rapports.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-colors">
                            Créer le premier rapport
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection