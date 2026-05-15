@extends('layouts.app')

@section('title', 'Alertes & Points Critiques - Cabinet Ministre')

@section('content')
    <div class="min-h-screen bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="max-w-7xl mx-auto mb-10 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Points d'Attention Critiques</h1>
                <p class="text-slate-500 text-lg">Surveillance des retards et des risques majeurs.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span
                    class="inline-flex items-center px-4 py-2 rounded-full bg-red-50 text-red-600 border border-red-100 font-bold text-sm">
                    <span class="w-2 h-2 rounded-full bg-red-500 mr-2 animate-pulse"></span>
                    {{ $enRetard->count() + $hautePriorite->count() }} Alertes Actives
                </span>
            </div>
        </div>

        <div class="max-w-7xl mx-auto space-y-12">

            <!-- SECTION 1: RETARDS CRITIQUES (RED) -->
            <div class="bg-white rounded-3xl shadow-xl border border-red-100 overflow-hidden">
                <div class="bg-red-50/50 px-8 py-6 border-b border-red-100 flex items-center">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-red-900">Dépassements de Délais</h2>
                        <p class="text-red-600/80 text-sm font-medium">Recommandations dont la date limite est passée.</p>
                    </div>
                </div>

                <div class="p-8">
                    @if($enRetard->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($enRetard as $recommandation)
                                <div
                                    class="bg-white rounded-2xl border border-red-100 p-5 hover:shadow-lg hover:border-red-200 transition-all group">
                                    <div class="flex justify-between items-start mb-4">
                                        <span
                                            class="px-2 py-1 rounded-md bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-wider">
                                            {{ $recommandation->date_limite->diffForHumans() }}
                                        </span>
                                        <a href="{{ route('cabinet_ministre.suivi.show', $recommandation->id) }}"
                                            class="text-slate-300 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                    <h3
                                        class="font-bold text-slate-800 mb-2 line-clamp-2 leading-relaxed group-hover:text-red-700 transition-colors">
                                        {{ $recommandation->titre }}
                                    </h3>
                                    <div class="flex items-center text-xs text-slate-500 font-medium">
                                        <span class="truncate max-w-[150px]">{{ $recommandation->its->name ?? 'N/A' }}</span>
                                        <span class="mx-2">•</span>
                                        <span class="text-red-500">Priorité {{ ucfirst($recommandation->priorite) }}</span>
                                    </div>

                                    <!-- Actions Grid (Escalation) -->
                                    <div class="mt-5 pt-4 border-t border-slate-50 flex justify-end">
                                        <form action="{{ route('cabinet_ministre.alertes.escalader', $recommandation->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs font-bold text-red-600 hover:text-red-800 flex items-center transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                                ESCALADER
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="inline-flex items-center justify-center p-4 bg-emerald-50 rounded-full mb-4">
                                <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-slate-400 font-medium">Aucun retard critique détecté.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- SECTION 2: RISQUES IMMINENTS (AMBER/ORANGE) -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-100 flex items-center">
                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800">Échéances Proches (7 jours)</h2>
                        <p class="text-slate-500 text-sm font-medium">Actions nécessitant une attention immédiate.</p>
                    </div>
                </div>

                <div class="p-8">
                    @if($prochesEcheances->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                            @foreach($prochesEcheances as $recommandation)
                                <div
                                    class="flex items-start p-4 rounded-xl bg-amber-50/30 border border-amber-100/50 hover:bg-amber-50 hover:border-amber-200 transition-all">
                                    <div class="flex-1 min-w-0 mr-4">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-widest text-amber-600">J-{{ now()->diffInDays($recommandation->date_limite) }}</span>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-800 line-clamp-1 mb-1">{{ $recommandation->titre }}
                                        </h4>
                                        <p class="text-xs text-slate-500">{{ $recommandation->its->name ?? 'N/A' }}</p>
                                    </div>
                                    <a href="{{ route('cabinet_ministre.suivi.show', $recommandation->id) }}"
                                        class="p-2 rounded-full bg-white text-slate-400 hover:text-amber-500 hover:shadow-md transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-slate-400 font-medium text-center">Aucune échéance proche.</p>
                    @endif
                </div>
            </div>

            <!-- SECTION 3: BLOCAGES ADMIN (GRAY) -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-100 flex items-center">
                    <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800">Blocages Administratifs</h2>
                        <p class="text-slate-500 text-sm font-medium">Recommandations en attente d'assignation ou bloquées.
                        </p>
                    </div>
                </div>
                <div class="p-8">
                    @if($sansPointFocal->count() > 0)
                        <div class="overflow-hidden bg-slate-50 rounded-xl border border-slate-200">
                            <table class="w-full">
                                <tbody class="divide-y divide-slate-200">
                                    @foreach($sansPointFocal as $recommandation)
                                        <tr class="group hover:bg-white transition-colors">
                                            <td class="px-6 py-4">
                                                <span class="text-xs font-bold text-slate-400 block mb-1">Non Assigné</span>
                                                <span class="text-sm font-bold text-slate-800">{{ $recommandation->titre }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                                    {{ $recommandation->its->name ?? 'Structure' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-slate-400 font-medium text-center">Aucun blocage administratif signalé.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection