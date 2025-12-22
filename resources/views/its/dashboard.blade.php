@extends('layouts.app')

@section('title', 'Tableau de Bord ITS')

@section('content')
    <div class="min-h-screen py-10 bg-[#f8fafc]">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <!-- Premium Header Section -->
            <div
                class="relative overflow-hidden mb-10 p-8 rounded-3xl bg-gradient-to-br from-indigo-600 via-blue-600 to-indigo-700 shadow-xl">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-white tracking-tight">Tableau de Bord ITS</h1>
                        <p class="mt-2 text-indigo-100 text-lg opacity-90 font-medium">Gestion et suivi des recommandations
                            de votre structure</p>
                    </div>
                    <div
                        class="flex items-center space-x-3 bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/20">
                        <div class="w-10 h-10 rounded-full bg-indigo-400/30 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-100 uppercase tracking-wider font-semibold">Date d'aujourd'hui</p>
                            <p class="text-sm font-bold text-white">{{ now()->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                </div>
                <!-- Decorative elements -->
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-indigo-400/20 rounded-full blur-2xl"></div>
            </div>

            <!-- Glassmorphism Stats Cards -->
            <div class="grid grid-cols-1 gap-6 mb-10 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Recommendations -->
                <div
                    class="group relative bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div
                        class="absolute right-0 top-0 mt-[-10px] mr-[-10px] w-20 h-20 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 opacity-50">
                    </div>
                    <div class="relative flex items-center">
                        <div
                            class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg shadow-blue-200 text-white transition-transform group-hover:rotate-6">
                            <i class="text-xl fas fa-file-alt"></i>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Total</p>
                            <p class="text-3xl font-black text-gray-900">{{ $totalRecommandations }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs font-semibold text-blue-600">
                        <span>Voir toutes les recommandations</span>
                        <i class="ml-1 fas fa-arrow-right text-[10px]"></i>
                    </div>
                </div>

                <!-- Drafts -->
                <div
                    class="group relative bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div
                        class="absolute right-0 top-0 mt-[-10px] mr-[-10px] w-20 h-20 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-500 opacity-50">
                    </div>
                    <div class="relative flex items-center">
                        <div
                            class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-amber-400 to-amber-500 rounded-2xl shadow-lg shadow-amber-100 text-white transition-transform group-hover:rotate-6">
                            <i class="text-xl fas fa-edit"></i>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Brouillon</p>
                            <p class="text-3xl font-black text-gray-900">{{ $brouillonsCount }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs font-semibold text-amber-600">
                        <span>En attente de finalisation</span>
                        <i class="ml-1 fas fa-clock text-[10px]"></i>
                    </div>
                </div>

                <!-- Submitted -->
                <div
                    class="group relative bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div
                        class="absolute right-0 top-0 mt-[-10px] mr-[-10px] w-20 h-20 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-500 opacity-50">
                    </div>
                    <div class="relative flex items-center">
                        <div
                            class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl shadow-lg shadow-indigo-100 text-white transition-transform group-hover:rotate-6">
                            <i class="text-xl fas fa-paper-plane"></i>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Soumises</p>
                            <p class="text-3xl font-black text-gray-900">{{ $soumisesCount }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs font-semibold text-indigo-600">
                        <span>En cours de validation IG</span>
                        <i class="ml-1 fas fa-check-double text-[10px]"></i>
                    </div>
                </div>

                <!-- Overdue -->
                <div
                    class="group relative bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div
                        class="absolute right-0 top-0 mt-[-10px] mr-[-10px] w-20 h-20 bg-rose-50 rounded-full group-hover:scale-150 transition-transform duration-500 opacity-50">
                    </div>
                    <div class="relative flex items-center">
                        <div
                            class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl shadow-lg shadow-rose-100 text-white transition-transform group-hover:rotate-6">
                            <i class="text-xl fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">En retard</p>
                            <p class="text-3xl font-black text-gray-900">{{ $enRetardCount }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs font-semibold text-rose-600">
                        <span>Attention - Délai dépassé</span>
                        <i class="ml-1 fas fa-fire text-[10px]"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-10 lg:grid-cols-3">
                <!-- Left Side: Statistics Charts/Bars -->
                <div class="lg:col-span-1 space-y-10">
                    <!-- Status Distribution -->
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">Répartition par statut</h3>
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>

                        @if($statuts->isNotEmpty())
                            <div class="space-y-6">
                                @foreach($statuts as $statut)
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-bold text-gray-700">{{ $statut->label }}</span>
                                            <span class="text-sm font-black text-indigo-600">{{ $statut->count }}</span>
                                        </div>
                                        <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                            @php
                                                // Extraction de la couleur de fond depuis la classe Laravel si possible, sinon défaut
                                                $bgBar = 'bg-indigo-500';
                                                if (strpos($statut->color_class, 'bg-green') !== false)
                                                    $bgBar = 'bg-emerald-500';
                                                if (strpos($statut->color_class, 'bg-red') !== false)
                                                    $bgBar = 'bg-rose-500';
                                                if (strpos($statut->color_class, 'bg-yellow') !== false)
                                                    $bgBar = 'bg-amber-500';
                                                if (strpos($statut->color_class, 'bg-blue') !== false)
                                                    $bgBar = 'bg-blue-500';
                                            @endphp
                                            <div class="h-full rounded-full {{ $bgBar }} transition-all duration-1000"
                                                style="width: {{ ($statut->count / max(1, $totalRecommandations)) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-12 text-center text-gray-400">
                                <i class="text-5xl opacity-20 fas fa-inbox mb-4"></i>
                                <p class="font-medium">Aucune donnée trouvée</p>
                            </div>
                        @endif
                    </div>

                    <!-- Priority Levels -->
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                        <h3 class="text-xl font-extrabold text-gray-900 tracking-tight mb-8">Priorités</h3>
                        <div class="space-y-4">
                            @forelse($priorites as $priorite)
                                <div
                                    class="flex items-center p-4 rounded-2xl bg-gray-50 border border-gray-100 transition-hover duration-200">
                                    <div class="w-2 h-10 rounded-full mr-4
                                        @if($priorite->priorite == 'haute') bg-rose-500
                                        @elseif($priorite->priorite == 'moyenne') bg-amber-500
                                        @else bg-emerald-500 @endif"></div>
                                    <div class="flex-1">
                                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">
                                            {{ $priorite->priorite }}</p>
                                        <p class="text-lg font-bold text-gray-800">{{ $priorite->count }} <span
                                                class="text-sm font-medium text-gray-500">missions</span></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xl font-black text-gray-900">
                                            {{ round(($priorite->count / max(1, $totalRecommandations)) * 100) }}%</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-400 text-center">Aucune priorité définie</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Side: Recent Activity & Actions -->
                <div class="lg:col-span-2 space-y-10">

                    <!-- Quick Action Tiles -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <a href="{{ route('its.recommandations.create') }}"
                            class="group p-8 bg-white border border-gray-100 rounded-[2rem] shadow-sm hover:shadow-2xl transition-all duration-500 hover:bg-emerald-600 overflow-hidden relative">
                            <div class="relative z-10">
                                <div
                                    class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-white group-hover:scale-110 transition-all duration-500">
                                    <i class="text-2xl fas fa-plus"></i>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 group-hover:text-white transition-colors mb-2">
                                    Nouvelle mission</h3>
                                <p class="text-sm text-gray-500 font-medium group-hover:text-emerald-100 transition-colors">
                                    Lancer une nouvelle recommandation</p>
                            </div>
                            <div
                                class="absolute -right-8 -bottom-8 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-[4] group-hover:bg-emerald-500 transition-all duration-700 opacity-50">
                            </div>
                        </a>

                        <a href="{{ route('its.recommandations.index') }}"
                            class="group p-8 bg-white border border-gray-100 rounded-[2rem] shadow-sm hover:shadow-2xl transition-all duration-500 hover:bg-blue-600 overflow-hidden relative">
                            <div class="relative z-10">
                                <div
                                    class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-white group-hover:scale-110 transition-all duration-500">
                                    <i class="text-2xl fas fa-list"></i>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 group-hover:text-white transition-colors mb-2">
                                    Historique</h3>
                                <p class="text-sm text-gray-500 font-medium group-hover:text-blue-100 transition-colors">
                                    Gérer l'ensemble des missions</p>
                            </div>
                            <div
                                class="absolute -right-8 -bottom-8 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-[4] group-hover:bg-blue-500 transition-all duration-700 opacity-50">
                            </div>
                        </a>

                        <a href="{{ route('its.rapports.index') }}"
                            class="group p-8 bg-white border border-gray-100 rounded-[2rem] shadow-sm hover:shadow-2xl transition-all duration-500 hover:bg-purple-600 overflow-hidden relative">
                            <div class="relative z-10">
                                <div
                                    class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 mb-6 group-hover:bg-white group-hover:scale-110 transition-all duration-500">
                                    <i class="text-2xl fas fa-chart-line"></i>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 group-hover:text-white transition-colors mb-2">
                                    Analytique</h3>
                                <p class="text-sm text-gray-500 font-medium group-hover:text-purple-100 transition-colors">
                                    Analyses et rapports avancés</p>
                            </div>
                            <div
                                class="absolute -right-8 -bottom-8 w-24 h-24 bg-purple-50 rounded-full group-hover:scale-[4] group-hover:bg-purple-500 transition-all duration-700 opacity-50">
                            </div>
                        </a>
                    </div>
                    <!-- Recent Table -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-white">
                            <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">Recommandations récentes</h3>
                            <a href="{{ route('its.recommandations.index') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-bold text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors">
                                Voir toutes
                                <i class="ml-2 fas fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            @if($recentRecommandations->isNotEmpty())
                                <table class="w-full">
                                    <thead class="bg-gray-50/50">
                                        <tr>
                                            <th
                                                class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-widest">
                                                INFO</th>
                                            <th
                                                class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-widest">
                                                STATUT</th>
                                            <th
                                                class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-widest">
                                                ÉCHÉANCE</th>
                                            <th class="px-8 py-5 text-right"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($recentRecommandations as $recommandation)
                                            <tr class="group hover:bg-indigo-50/30 transition-colors">
                                                <td class="px-8 py-6">
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-sm font-black text-gray-900 group-hover:text-indigo-700 transition-colors">{{ $recommandation->reference }}</span>
                                                        <span
                                                            class="text-xs font-medium text-gray-500 truncate mt-1 max-w-[200px]">{{ $recommandation->titre }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-8 py-6">
                                                    @include('shared.status-badge', ['statut' => $recommandation->statut])
                                                </td>
                                                <td class="px-8 py-6">
                                                    <div class="flex items-center space-x-2">
                                                        <span
                                                            class="text-sm font-bold {{ $recommandation->estEnRetard() ? 'text-rose-600' : 'text-gray-700' }}">
                                                            {{ $recommandation->date_limite->format('d/m/Y') }}
                                                        </span>
                                                        @if($recommandation->estEnRetard())
                                                            <span class="animate-pulse flex h-2 w-2 rounded-full bg-rose-600"></span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-8 py-6 text-right">
                                                    <a href="{{ route('its.recommandations.show', $recommandation) }}"
                                                        class="p-2 text-gray-400 hover:text-indigo-600 transition-colors">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="py-20 text-center">
                                    <div
                                        class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="text-3xl text-gray-200 fas fa-folder-open"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">Aucun enregistrement récent</p>
                                    <a href="{{ route('its.recommandations.create') }}"
                                        class="mt-4 inline-flex text-indigo-600 font-bold hover:underline">
                                        Commencer par créer une recommandation
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
@endsection