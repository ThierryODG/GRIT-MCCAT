@extends('layouts.app')

@section('title', 'Cabinet Ministre - Vision Stratégique')

@section('content')
    <div class="min-h-screen bg-[#f8fafc] pb-12">

        <!-- Hero Section (Royal Theme - Burkina Faso) -->
        <div class="relative bg-slate-900 pb-32 pt-12 overflow-hidden rounded-b-[3rem] shadow-2xl">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 opacity-95"></div>
                <!-- Abstract Royal Patterns -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-amber-500/10 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-red-600/10 blur-3xl"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <span
                                class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-400 text-xs font-bold uppercase tracking-widest border border-amber-500/20">
                                Burkina Faso
                            </span>
                            <span
                                class="px-3 py-1 rounded-full bg-white/5 text-slate-300 text-xs font-bold uppercase tracking-widest border border-white/10">
                                La Patrie ou la Mort, nous vaincrons
                            </span>
                        </div>
                        <h1 class="text-4xl font-black text-white tracking-tight">
                            Tableau de Bord Ministériel
                        </h1>
                        <p class="mt-2 text-slate-400 text-lg max-w-2xl">
                            Vue globale sur la performance et l'exécution des recommandations stratégiques.
                        </p>
                    </div>

                    <div class="mt-6 md:mt-0 flex items-center space-x-4">
                        <div
                            class="flex items-center space-x-3 bg-white/5 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10">
                            <div
                                class="w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center border border-amber-500/30">
                                <span class="text-amber-400 font-bold text-lg">{{ $tauxMiseEnOeuvre }}%</span>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Taux d'Exécution
                                </p>
                                <p class="text-sm font-bold text-white">Global</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-10">

            <!-- Executive KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <!-- Total Volume -->
                <div
                    class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 relative overflow-hidden group hover:shadow-2xl transition-all duration-300">
                    <div
                        class="absolute top-0 right-0 w-24 h-24 bg-slate-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Dossiers</p>
                        <h3 class="text-3xl font-black text-slate-800">{{ number_format($stats['total_recommandations']) }}
                        </h3>
                        <div class="mt-4 flex items-center text-sm text-slate-500">
                            <span class="w-2 h-2 rounded-full bg-slate-400 mr-2"></span>
                            Recommandations émises
                        </div>
                    </div>
                </div>

                <!-- Critical Alerts (Red/Amber) -->
                <div
                    class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-red-500 relative overflow-hidden group hover:shadow-2xl transition-all duration-300">
                    <div class="relative">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-bold text-red-500 uppercase tracking-widest mb-1">Points Critiques
                                </p>
                                <h3 class="text-3xl font-black text-slate-800">{{ $enRetard }}</h3>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm text-red-600 font-medium">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse mr-2"></span>
                            En retard critique
                        </div>
                    </div>
                </div>

                <!-- Success Metric (Green/Gold) -->
                <div
                    class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 shadow-lg text-white relative overflow-hidden group hover:shadow-2xl transition-all duration-300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                    <div class="relative">
                        <p class="text-xs font-bold text-amber-400 uppercase tracking-widest mb-1">Performance</p>
                        <h3 class="text-3xl font-black text-white">{{ $tauxMiseEnOeuvre }}<span
                                class="text-lg text-amber-400">%</span></h3>
                        <div class="mt-4 flex items-center text-sm text-slate-300">
                            <svg class="w-4 h-4 text-amber-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Taux de clôture
                        </div>
                    </div>
                </div>

                <!-- Speed Metric -->
                <div
                    class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 relative overflow-hidden group hover:shadow-2xl transition-all duration-300">
                    <div class="relative">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Vélocité</p>
                        <h3 class="text-3xl font-black text-slate-800">{{ $delaiMoyen }}<span
                                class="text-sm font-medium text-slate-400 ml-1">jours</span></h3>
                        <div class="mt-4 flex items-center text-sm text-slate-500">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                            Temps moyen de traitement
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

                <!-- Left Column: Evolution (50%) -->
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800">Dynamique des Recommandations</h3>
                            <p class="text-sm text-slate-500">Évolution sur les 6 derniers mois</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span
                                class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold">Volume</span>
                        </div>
                    </div>
                    <div class="h-80 w-full">
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>

                <!-- Right Column: Status Donut (50%) -->
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 relative overflow-hidden">
                    <h3 class="text-xl font-bold text-slate-800 mb-6">État des Lieux Global</h3>
                    <div class="h-80 relative z-10 flex items-center justify-center">
                        <canvas id="statutChart"></canvas>
                    </div>
                    <div class="absolute bottom-0 right-0 w-32 h-32 bg-slate-50 rounded-full blur-2xl -mr-6 -mb-6"></div>
                </div>
            </div>

            <!-- Structure Performance (Full Width) -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 mb-10">
                <h3 class="text-xl font-bold text-slate-800 mb-6">Performance des Structures</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($topStructures as $index => $structure)
                        <div
                            class="group flex items-center p-4 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-all duration-200">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold mr-4 shadow-md
                                    @if($index == 0) bg-amber-500 shadow-amber-200
                                    @elseif($index == 1) bg-slate-600 shadow-slate-200
                                    @elseif($index == 2) bg-orange-700 shadow-orange-200
                                    @else bg-slate-400 @endif">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between mb-1">
                                    <h4 class="font-bold text-slate-800 truncate">
                                        {{ $structure->its->name ?? 'Structure Inconnue' }}</h4>
                                    <span class="font-bold text-indigo-600">{{ $structure->total }} <span
                                            class="text-xs text-slate-400 font-normal">clôturées</span></span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="bg-indigo-600 h-1.5 rounded-full"
                                        style="width: {{ ($structure->total / max(1, $stats['cloturees'])) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-8 text-slate-400">
                            <p>Aucune données de performace structure disponible.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity Feed (Full Width Bottom) -->
            <div class="bg-slate-900 rounded-3xl shadow-xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl -mr-16 -mt-16"></div>

                <div class="flex items-center justify-between mb-8 relative z-10">
                    <h3 class="text-xl font-bold flex items-center">
                        <span class="w-2 h-6 bg-amber-500 rounded-full mr-3"></span>
                        Activités Récentes
                    </h3>
                    <a href="{{ route('cabinet_ministre.suivi.index') }}"
                        class="text-sm font-bold text-amber-400 hover:text-amber-300 transition-colors flex items-center">
                        Voir tout le journal
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
                    @forelse($dernieresActivites->take(4) as $activite)
                        <div
                            class="p-4 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors group">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-2 h-2 rounded-full 
                                        @if($activite->statut == 'cloturee') bg-emerald-500
                                        @elseif($activite->statut == 'en_retard') bg-red-500
                                        @else bg-blue-500 @endif"></div>
                                <span
                                    class="text-xs text-slate-400 font-bold uppercase tracking-wider">{{ $activite->created_at->format('d M') }}</span>
                            </div>

                            <h4
                                class="text-sm font-bold text-white mb-1 line-clamp-2 group-hover:text-amber-400 transition-colors">
                                {{ $activite->titre }}
                            </h4>
                            <p class="text-xs text-slate-500">
                                {{ $activite->its->name ?? 'N/A' }}
                            </p>
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-8 text-slate-500">
                            <p>Aucune activité récente.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Configuration
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#94a3b8';

            const colors = {
                primary: '#3B82F6',   // Blue 500
                royal: '#0f172a',     // Slate 900
                accent: '#f59e0b',    // Amber 500
                success: '#10B981',   // Emerald 500
                danger: '#EF4444',    // Red 500
                light: '#f1f5f9'      // Slate 100
            };

            // Evolution Chart
            const evoCtx = document.getElementById('evolutionChart').getContext('2d');
            const gradient = evoCtx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

            new Chart(evoCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_keys($recommandationsParMois->toArray())) !!},
                    datasets: [{
                        label: 'Nouvelles Recommandations',
                        data: {!! json_encode(array_values($recommandationsParMois->toArray())) !!},
                        borderColor: colors.primary,
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: colors.primary,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: colors.royal,
                            titleColor: '#fff',
                            bodyColor: '#cbd5e1',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 2], color: '#f1f5f9' },
                            ticks: { font: { weight: '600' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: '600' } }
                        }
                    }
                }
            });

            // Status Chart (Donut)
            const statCtx = document.getElementById('statutChart').getContext('2d');
            new Chart(statCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($repartitionStatut)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($repartitionStatut)) !!},
                        backgroundColor: [
                            colors.primary, // En cours
                            colors.success, // Cloturee
                            colors.accent,  // En attente
                            colors.danger,  // Rejetee
                            colors.royal
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { size: 11, weight: '600' }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection