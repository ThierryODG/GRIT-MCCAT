@extends('layouts.app')

@section('title', 'Tableau de Bord - Responsable')

@section('content')
    <div class="space-y-8 pb-10">
        <!-- ==================== HEADER & GLOBAL PROGRESS ==================== -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tableau de Bord</h1>
                <p class="mt-2 text-gray-500 font-medium italic">Bienvenue, {{ Auth::user()->name }}. Voici l'état actuel de
                    votre structure.</p>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6 min-w-[300px]">
                <div class="relative w-16 h-16 flex-shrink-0">
                    <svg class="w-full h-full transform -rotate-90">
                        <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="6" fill="transparent"
                            class="text-gray-100" />
                        <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="6" fill="transparent"
                            stroke-dasharray="{{ 2 * pi() * 28 }}"
                            stroke-dashoffset="{{ 2 * pi() * 28 * (1 - $stats['progression_globale'] / 100) }}"
                            class="text-blue-600 transition-all duration-1000 ease-out" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-sm font-bold text-gray-900">{{ $stats['progression_globale'] }}%</span>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Progression Globale</p>
                    <p class="text-xl font-black text-gray-900 leading-none mt-1">Mise en œuvre</p>
                    <p class="text-xs text-blue-600 font-medium mt-1">Moyenne de tous les plans</p>
                </div>
            </div>
        </div>

        <!-- ==================== KPI CARDS ==================== -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Recommandations Assignées -->
            <div
                class="relative group bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 overflow-hidden">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50/50 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative flex items-center gap-4">
                    <div class="p-4 bg-blue-600 text-white rounded-2xl shadow-lg shadow-blue-200">
                        <i class="fas fa-file-invoice text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">Assignées</p>
                        <p class="text-3xl font-black text-gray-900">{{ $stats['recommandations_assignees'] }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs font-medium text-gray-500 bg-gray-50 p-2 rounded-lg">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Total des recommandations à traiter
                </div>
            </div>

            <!-- En attente de validation -->
            <a href="{{ route('responsable.validation_plans.index') }}"
                class="relative group bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 overflow-hidden">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50/50 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative flex items-center gap-4">
                    <div class="p-4 bg-amber-500 text-white rounded-2xl shadow-lg shadow-amber-200">
                        <i class="fas fa-clipboard-check text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">À Valider</p>
                        <p class="text-3xl font-black text-gray-900">{{ $stats['recommandations_attente'] }}</p>
                    </div>
                </div>
                <div
                    class="mt-4 flex items-center text-xs font-medium text-amber-600 bg-amber-50 p-2 rounded-lg group-hover:bg-amber-100 transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Action requise par vous
                </div>
            </a>

            <!-- En Retard -->
            <div
                class="relative group bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 overflow-hidden">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-rose-50/50 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative flex items-center gap-4">
                    <div class="p-4 bg-rose-600 text-white rounded-2xl shadow-lg shadow-rose-200 animate-pulse-slow">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">En Retard</p>
                        <p class="text-3xl font-black text-gray-900">{{ $stats['recommandations_retard'] }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs font-medium text-rose-600 bg-rose-50 p-2 rounded-lg">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Délai de mise en œuvre dépassé
                </div>
            </div>
        </div>

        <!-- ==================== MAIN SECTION: TWO BLOCKS ==================== -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- BLOCK 1: VALIDATION WAITLIST (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- DASHBOARD ACTIONS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('responsable.suivi.index') }}"
                        class="group bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5 hover:border-purple-200 transition-all">
                        <div
                            class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-xl group-hover:bg-purple-600 group-hover:text-white transition-all">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Suivi d'Exécution</h4>
                            <p class="text-xs text-gray-400 mt-0.5">Piloter l'avancement des recommandations</p>
                        </div>
                    </a>
                    <a href="{{ route('responsable.points_focaux.index') }}"
                        class="group bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5 hover:border-indigo-200 transition-all">
                        <div
                            class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl group-hover:bg-indigo-600 group-hover:text-white transition-all">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Équipe Points Focaux</h4>
                            <p class="text-xs text-gray-400 mt-0.5">Gérer les accès et affectations</p>
                        </div>
                    </a>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 min-h-[400px]">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Missions prêtes pour validation</h3>
                            <p class="text-sm text-gray-500 mt-1 italic">Dernières recommandations soumises par vos points
                                focaux</p>
                        </div>
                        <a href="{{ route('responsable.validation_plans.index') }}"
                            class="text-sm font-bold text-blue-600 hover:text-blue-800 flex items-center group">
                            Voir tout
                            <i class="fas fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($recommandationsAttente as $reco)
                            <div
                                class="group relative flex items-center p-5 bg-gray-50 rounded-2xl border border-transparent hover:border-blue-200 hover:bg-white transition-all cursor-default">
                                <div
                                    class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-blue-600 mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="bg-blue-100 text-blue-700 text-[10px] font-black uppercase px-2 py-0.5 rounded-full">{{ $reco->reference }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Le
                                            {{ $reco->updated_at->format('d/m/Y') }}</span>
                                    </div>
                                    <h4
                                        class="text-sm font-bold text-gray-900 truncate group-hover:text-blue-700 transition-colors">
                                        {{ $reco->titre }}</h4>
                                    <p class="text-xs text-gray-500 flex items-center mt-1">
                                        <i class="fas fa-user-circle mr-1.5 opacity-50"></i>
                                        PF: <span class="font-bold text-gray-700 ml-1">{{ $reco->pointFocal->name }}</span>
                                    </p>
                                </div>
                                <a href="{{ route('responsable.validation_plans.dossier', $reco) }}"
                                    class="ml-4 px-4 py-2 bg-blue-600 text-white text-[10px] font-black uppercase tracking-wider rounded-full hover:bg-blue-700 transition-colors shadow-lg shadow-blue-100">
                                    Analyser
                                </a>
                            </div>
                        @empty
                            <div class="py-16 text-center">
                                <div
                                    class="w-20 h-20 bg-gray-50 rounded-3xl mx-auto flex items-center justify-center mb-4 border border-dashed border-gray-200">
                                    <i class="fas fa-check-double text-gray-300 text-2xl"></i>
                                </div>
                                <p class="text-gray-400 font-medium italic">Aucune recommandation en attente de validation.</p>
                                <p class="text-xs text-gray-300 mt-1">Tout est à jour !</p>
                            </div>
                        @endforelse
                    </div>
                </div>                
            </div>

            <!-- BLOCK 2: STATISTICS & RECENT (1/3) -->
            <div class="space-y-8">
                <!-- REPARTITION PAR PRIORITE -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-filter mr-2 text-gray-400"></i>
                        Répartition par Priorité
                    </h3>

                    <div class="space-y-5">
                        <!-- Haute -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-rose-600 uppercase tracking-widest flex items-center">
                                    <span class="w-2 h-2 bg-rose-600 rounded-full mr-2"></span>
                                    Haute
                                </span>
                                <span class="font-black text-gray-900">{{ $stats['par_priorite']['haute'] }}</span>
                            </div>
                            <div class="h-2 bg-gray-50 rounded-full overflow-hidden">
                                <div class="h-full bg-rose-600 rounded-full transition-all duration-1000 ease-out"
                                    style="width: {{ $stats['recommandations_assignees'] > 0 ? ($stats['par_priorite']['haute'] / $stats['recommandations_assignees'] * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                        <!-- Moyenne -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-amber-500 uppercase tracking-widest flex items-center">
                                    <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span>
                                    Moyenne
                                </span>
                                <span class="font-black text-gray-900">{{ $stats['par_priorite']['moyenne'] }}</span>
                            </div>
                            <div class="h-2 bg-gray-50 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-500 rounded-full transition-all duration-1000 ease-out"
                                    style="width: {{ $stats['recommandations_assignees'] > 0 ? ($stats['par_priorite']['moyenne'] / $stats['recommandations_assignees'] * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                        <!-- Basse -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-emerald-500 uppercase tracking-widest flex items-center">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                    Basse
                                </span>
                                <span class="font-black text-gray-900">{{ $stats['par_priorite']['basse'] }}</span>
                            </div>
                            <div class="h-2 bg-gray-50 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000 ease-out"
                                    style="width: {{ $stats['recommandations_assignees'] > 0 ? ($stats['par_priorite']['basse'] / $stats['recommandations_assignees'] * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACTIVITES RECENTES -->
                <div class="bg-gray-900 p-8 rounded-3xl shadow-xl shadow-gray-200">
                    <h3 class="text-lg font-bold text-white mb-6">Activités Récentes</h3>
                    <div class="space-y-6">
                        @forelse($recommandationsRecentes as $rec)
                            <div class="relative pl-6 pb-2 border-l border-gray-800 last:border-0">
                                <div
                                    class="absolute -left-[5px] top-0 w-[9px] h-[9px] rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.8)]">
                                </div>
                                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">
                                    {{ $rec->updated_at->diffForHumans() }}
                                </p>
                                <h4 class="text-xs font-bold text-gray-100 line-clamp-1 mb-1">{{ $rec->titre }}</h4>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-[9px] font-bold px-1.5 py-0.5 bg-gray-800 text-gray-400 rounded uppercase tracking-tighter">
                                        {{ str_replace('_', ' ', $rec->statut) }}
                                    </span>
                                    @if($rec->plansAction->count() > 0)
                                        @php
                                            $avg = round($rec->plansAction->avg('pourcentage_avancement'));
                                        @endphp
                                        <div class="flex items-center gap-1.5 bg-white/5 px-1.5 py-0.5 rounded">
                                            <div class="w-10 h-1 bg-gray-800 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-500" style="width: {{ $avg }}%"></div>
                                            </div>
                                            <span class="text-[9px] font-black text-blue-400">{{ $avg }}%</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600 text-xs italic text-center">Aucun mouvement récent.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(0.95);
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 3s infinite ease-in-out;
        }
    </style>
@endsection