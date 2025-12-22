@extends('layouts.app')

@section('title', 'Tableau de Bord - Inspecteur Général')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="ml-1 text-gray-700 font-medium">Tableau de Bord</span>
        </div>
    </li>
@endsection

@section('content')
    <div class="space-y-10 py-6 bg-[#f8fafc]">

        <!-- Hero Header for IG -->
        <div class="relative overflow-hidden p-8 rounded-[2.5rem] bg-[#1e293b] shadow-2xl">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">Supervision Générale</h1>
                    <p class="mt-2 text-slate-400 text-lg font-medium max-w-xl">Bienvenue, Inspecteur Général. Vous avez
                        <span class="text-orange-400 font-bold">{{ $statsRecommandations['en_attente_validation'] }}</span>
                        recommandations en attente de décision.</p>
                </div>
                <div class="flex -space-x-3">
                    <div
                        class="w-12 h-12 rounded-full border-4 border-[#1e293b] bg-indigo-500 flex items-center justify-center text-white font-bold">
                        IG</div>
                    <div
                        class="w-12 h-12 rounded-full border-4 border-[#1e293b] bg-slate-700 flex items-center justify-center text-white/50">
                        <i class="fas fa-shield-alt"></i></div>
                </div>
            </div>
            <!-- Abstract patterns -->
            <div class="absolute right-0 top-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px]"></div>
            <div class="absolute left-1/3 bottom-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-[80px]"></div>
        </div>

        <!-- ==================== PREMIUM KPI CARDS ==================== -->
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            <!-- Recommandations en attente -->
            <div
                class="group relative p-8 bg-white border border-gray-100 shadow-sm rounded-[2rem] hover:shadow-2xl transition-all duration-500 overflow-hidden">
                <div class="relative z-10">
                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest mb-1">À Valider</p>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-4xl font-black text-gray-900 group-hover:text-orange-600 transition-colors">
                                {{ $statsRecommandations['en_attente_validation'] }}</p>
                            <p class="mt-1 text-sm font-bold text-gray-500 italic">Recommandations</p>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-orange-50 text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-all duration-500 shadow-xl shadow-orange-100">
                            <i class="fas fa-hourglass-half text-2xl"></i>
                        </div>
                    </div>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 w-24 h-24 bg-orange-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700">
                </div>
            </div>

            <!-- Recommandations validées -->
            <div
                class="group relative p-8 bg-white border border-gray-100 shadow-sm rounded-[2rem] hover:shadow-2xl transition-all duration-500 overflow-hidden">
                <div class="relative z-10">
                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest mb-1">Total Validées</p>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-4xl font-black text-gray-900 group-hover:text-emerald-600 transition-colors">
                                {{ $statsRecommandations['validees_ig'] }}</p>
                            <p class="mt-1 text-sm font-bold text-gray-500 italic">Par vos soins</p>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-xl shadow-emerald-100">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700">
                </div>
            </div>

            <!-- Plans d'action en attente -->
            <div
                class="group relative p-8 bg-white border border-gray-100 shadow-sm rounded-[2rem] hover:shadow-2xl transition-all duration-500 overflow-hidden">
                <div class="relative z-10">
                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest mb-1">Plans d'Action</p>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-4xl font-black text-gray-900 group-hover:text-blue-600 transition-colors">
                                {{ $statsPlansAction['en_attente_validation'] }}</p>
                            <p class="mt-1 text-sm font-bold text-gray-500 italic">En attente d'approbation</p>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 shadow-xl shadow-blue-100">
                            <i class="fas fa-clipboard-list text-2xl"></i>
                        </div>
                    </div>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700">
                </div>
            </div>
        </div>

        <!-- ==================== ANALYTICS SECTION ==================== -->
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
            <!-- Chart 1: Evolution -->
            <div class="p-10 bg-white border border-gray-100 shadow-xl rounded-[3rem]">
                <div class="flex items-center justify-between mb-10">
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">Activité Mensuelle</h3>
                    <div
                        class="flex items-center space-x-2 text-xs font-bold bg-gray-50 px-4 py-2 rounded-full text-gray-500">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>Volumes de validations</span>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="validationsChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Distribution -->
            <div class="p-10 bg-white border border-gray-100 shadow-xl rounded-[3rem]">
                <div class="flex items-center justify-between mb-10">
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">Répartition des décisions</h3>
                    <i class="fas fa-ellipsis-h text-gray-300"></i>
                </div>
                <div class="h-80 flex items-center justify-center">
                    <div class="w-full h-full">
                        <canvas id="repartitionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== FEED & ACTIONS ==================== -->
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-3">

            <!-- Recent Activities Feed (Span 2) -->
            <div class="lg:col-span-2 space-y-10">
                <div class="p-10 bg-white border border-gray-100 shadow-xl rounded-[3rem]">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">Flux d'activités récentes</h3>
                        <div class="flex space-x-2">
                            <button
                                class="w-8 h-8 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-filter text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @forelse($recommandationsRecentes as $recommandation)
                            <div
                                class="flex items-center p-6 rounded-3xl bg-gray-50/50 border border-gray-100 group hover:bg-white hover:shadow-xl transition-all duration-300">
                                <div
                                    class="w-14 h-14 rounded-2xl flex items-center justify-center mr-6 shadow-lg 
                                    @if($recommandation->statut == 'validee_ig') bg-emerald-100 text-emerald-600 @else bg-rose-100 text-rose-600 @endif group-hover:scale-110 transition-transform">
                                    <i
                                        class="fas @if($recommandation->statut == 'validee_ig') fa-check @else fa-times @endif text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4
                                            class="text-sm font-black text-gray-900 group-hover:text-indigo-600 transition-colors">
                                            {{ $recommandation->titre ?? 'Sans titre' }}</h4>
                                        <span
                                            class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ $recommandation->date_validation_ig->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest italic">
                                        Par: {{ $recommandation->its->name ?? 'Inconnu' }}
                                        @if($recommandation->structure) • {{ $recommandation->structure->nom }} @endif
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="py-20 text-center opacity-30">
                                <i class="fas fa-ghost text-5xl mb-4 text-slate-300"></i>
                                <p class="font-bold">Aucune activité récente</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar: Quick Actions -->
            <div class="space-y-10">
                <div
                    class="p-10 bg-gradient-to-br from-indigo-700 to-blue-800 shadow-2xl rounded-[3rem] text-white overflow-hidden relative">
                    <h3 class="text-2xl font-black mb-8 relative z-10">Actions Critiques</h3>
                    <div class="space-y-4 relative z-10">
                        <a href="{{ route('inspecteur_general.recommandations.index') }}"
                            class="flex items-center p-5 bg-white/10 backdrop-blur-md rounded-2xl hover:bg-white hover:text-indigo-900 transition-all duration-300 border border-white/10 group">
                            <div
                                class="w-10 h-10 rounded-xl bg-orange-400 text-white flex items-center justify-center mr-4 group-hover:scale-110 transition-transform shadow-lg shadow-orange-500/20">
                                <i class="fas fa-file-signature"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black">Recommandations</p>
                                <p class="text-xs opacity-70">{{ $statsRecommandations['en_attente_validation'] }} à traiter
                                </p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-[10px] opacity-40"></i>
                        </a>

                        <a href="{{ route('inspecteur_general.plan_actions.index') }}"
                            class="flex items-center p-5 bg-white/10 backdrop-blur-md rounded-2xl hover:bg-white hover:text-blue-900 transition-all duration-300 border border-white/10 group">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-400 text-white flex items-center justify-center mr-4 group-hover:scale-110 transition-transform shadow-lg shadow-blue-500/20">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black">Plans d'Action</p>
                                <p class="text-xs opacity-70">{{ $statsPlansAction['en_attente_validation'] }} à traiter</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-[10px] opacity-40"></i>
                        </a>

                        <a href="{{ route('inspecteur_general.suivi.index') }}"
                            class="flex items-center p-5 bg-white/10 backdrop-blur-md rounded-2xl hover:bg-white hover:text-purple-900 transition-all duration-300 border border-white/10 group">
                            <div
                                class="w-10 h-10 rounded-xl bg-purple-400 text-white flex items-center justify-center mr-4 group-hover:scale-110 transition-transform shadow-lg shadow-purple-500/20">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black">Suivi de Mise en Œuvre</p>
                                <p class="text-xs opacity-70">Vue globale</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-[10px] opacity-40"></i>
                        </a>
                    </div>
                    <!-- Artistic circle -->
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl"></div>
                </div>

                <!-- Mini Calendar or Info Card -->
                <div class="p-8 bg-white border border-gray-100 shadow-xl rounded-[2.5rem]">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400">
                            <i class="fas fa-calendar-check text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Dernière Mise à jour</p>
                            <p class="text-lg font-black text-gray-900">{{ now()->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const colors = {
                primary: '#3B82F6',
                primaryDark: '#1E40AF',
                success: '#10B981',
                danger: '#EF4444',
                warning: '#F59E0B',
                neutral: '#94A3B8'
            };

            // Validations Chart (Line with gradient)
            const validationsCtx = document.getElementById('validationsChart').getContext('2d');
            const primaryGradient = validationsCtx.createLinearGradient(0, 0, 0, 400);
            primaryGradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
            primaryGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            new Chart(validationsCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_keys($validationsParMois->toArray())) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($validationsParMois->toArray())) !!},
                        borderColor: colors.primary,
                        backgroundColor: primaryGradient,
                        borderWidth: 6,
                        fill: true,
                        tension: 0.5,
                        pointRadius: 0,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: colors.primary,
                        pointHoverBorderWidth: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(241, 245, 249, 1)', drawBorder: false },
                            ticks: { font: { weight: 'bold', family: 'Inter' }, color: '#94A3B8' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: 'bold', family: 'Inter' }, color: '#94A3B8' }
                        }
                    }
                }
            });

            // Decisions Distribution (Doughnut with thin ring)
            const repartitionCtx = document.getElementById('repartitionChart').getContext('2d');
            new Chart(repartitionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['R. Validées', 'R. Rejetées', 'P. Validés', 'P. Rejetés'],
                    datasets: [{
                        data: [
                            {{ $statsRecommandations['validees_ig'] }},
                            {{ $statsRecommandations['rejetees_ig'] }},
                            {{ $statsPlansAction['valides'] }},
                            {{ $statsPlansAction['rejetes'] }}
                        ],
                        backgroundColor: [colors.success, colors.danger, colors.primary, colors.warning],
                        borderWidth: 0,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 30,
                                font: { weight: 'black', size: 12, family: 'Inter' },
                                color: '#64748B'
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection