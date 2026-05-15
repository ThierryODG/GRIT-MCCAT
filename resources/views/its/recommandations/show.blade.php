@extends('layouts.app')

@section('title', $recommandation->reference)

@section('content')
    <div class="min-h-screen py-10 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumbs / Top Bar -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('its.recommandations.index') }}" class="p-2 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-blue-600 transition-all shadow-sm">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-extrabold text-[#1E293B]">{{ $recommandation->reference }}</h1>
                        <p class="text-sm font-medium text-gray-500">Détails de la recommandation stratégique</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest
                        @if($recommandation->statut == 'brouillon') bg-amber-100 text-amber-700
                        @elseif($recommandation->statut == 'validee_ig') bg-emerald-100 text-emerald-700
                        @elseif($recommandation->statut == 'rejetee_ig') bg-rose-100 text-rose-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ $recommandation->statut_label }}
                    </span>
                    <span class="px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest
                        @if($recommandation->priorite == 'haute') bg-rose-50 text-rose-600 border border-rose-100
                        @elseif($recommandation->priorite == 'moyenne') bg-amber-50 text-amber-600 border border-amber-100
                        @else bg-emerald-50 text-emerald-600 border border-emerald-100 @endif">
                        <i class="fas fa-flag mr-1.5"></i>{{ $recommandation->priorite }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Main Content (Left) -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- Core Details Card -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8 border-b border-gray-50 bg-gradient-to-r from-white to-gray-50/50">
                            <h2 class="text-3xl font-black text-[#1E293B] leading-tight mb-4">
                                {{ $recommandation->titre }}
                            </h2>
                            <div class="flex flex-wrap gap-6 text-sm">
                                <div class="flex items-center text-gray-500">
                                    <i class="far fa-building mr-2 text-blue-500"></i>
                                    <span class="font-bold text-gray-700">{{ $recommandation->structure->sigle }}</span>
                                    <span class="mx-2 text-gray-300">|</span>
                                    <span>{{ $recommandation->structure->nom }}</span>
                                </div>
                                <div class="flex items-center text-gray-500">
                                    <i class="far fa-calendar-alt mr-2 text-blue-500"></i>
                                    <span class="font-bold text-gray-700">Deadline :</span>
                                    <span class="ml-1 {{ $recommandation->estEnRetard() ? 'text-rose-600 font-black' : '' }}">
                                        {{ $recommandation->date_limite->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-8">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Constat & Recommandation</h3>
                            <div class="prose prose-slate max-w-none text-gray-700 leading-relaxed font-medium">
                                {!! nl2br(e($recommandation->description)) !!}
                            </div>
                        </div>
                    </div>

                    <!-- Rejection Alert (If applicable) -->
                    @if($recommandation->statut === 'rejetee_ig')
                        <div class="bg-rose-50 border border-rose-100 rounded-3xl p-8">
                            <div class="flex items-start space-x-4">
                                <div class="bg-rose-100 p-3 rounded-2xl">
                                    <i class="fas fa-exclamation-circle text-rose-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-rose-900 mb-2">Motif du rejet (Inspecteur Général)</h3>
                                    <div class="text-rose-700 font-medium leading-relaxed">
                                        {{ $recommandation->motif_rejet_ig ?? 'Aucun motif spécifié.' }}
                                    </div>
                                    @if($recommandation->commentaire_ig)
                                        <div class="mt-4 p-4 bg-white/60 border border-rose-100 rounded-2xl italic text-sm text-rose-800">
                                            "{{ $recommandation->commentaire_ig }}"
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Plan (If applicable) -->
                    @if($recommandation->plansAction->count() > 0)
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                            <h3 class="text-lg font-extrabold text-[#1E293B] mb-6 flex items-center">
                                <i class="fas fa-tasks mr-3 text-blue-500"></i> Plan d'exécution
                            </h3>
                            <div class="overflow-hidden border border-gray-100 rounded-2xl">
                                <table class="min-w-full divide-y divide-gray-100">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Action</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Échéance</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($recommandation->plansAction as $action)
                                            <tr class="hover:bg-gray-50/50 transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-bold text-gray-800">{{ $action->action }}</div>
                                                    <div class="text-xs text-gray-400 mt-1 italic">{{ $action->indicateurs }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="text-xs font-bold text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                                                        {{ $action->date_fin_prevue ? \Carbon\Carbon::parse($action->date_fin_prevue)->format('d/m/Y') : '-' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full
                                                        {{ $action->statut_execution === 'termine' ? 'bg-emerald-100 text-emerald-700' :
                                                           ($action->statut_execution === 'en_cours' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                                                        {{ $action->statut_execution_label }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar (Right) -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <!-- Actions Étendus -->
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 border-t-4 border-t-blue-600">
                        <h3 class="text-[#1E293B] font-black text-sm mb-6 flex items-center uppercase tracking-widest">
                            <i class="fas fa-bolt mr-2 text-blue-500"></i> Actions ITS
                        </h3>
                        <div class="space-y-4">
                            @if($recommandation->peutEtreModifiee())
                                <a href="{{ route('its.recommandations.edit', $recommandation) }}" 
                                    class="flex items-center justify-center w-full px-6 py-4 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-2xl font-bold transition-all border border-blue-100">
                                    <i class="fas fa-edit mr-3 opacity-60"></i> Modifier le contenu
                                </a>
                            @endif

                            @if($recommandation->peutEtreSoumise())
                                <form action="{{ route('its.recommandations.soumettre', $recommandation) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center justify-center w-full px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black transition-all shadow-lg shadow-blue-900/10">
                                        <i class="fas fa-paper-plane mr-3 opacity-60"></i> Soumettre à l'IG
                                    </button>
                                </form>
                            @endif

                            @if($recommandation->pointFocal || $recommandation->responsable)
                                <button type="button" @click="$dispatch('open-rappel-modal')" onclick="document.getElementById('modal-rappel').classList.remove('hidden')"
                                    class="flex items-center justify-center w-full px-6 py-4 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-2xl font-bold transition-all border border-slate-100">
                                    <i class="fas fa-bell mr-3 text-amber-500"></i> Envoyer un rappel
                                </button>
                            @else
                                <div class="p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200 text-center">
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Aucun destinataire associé pour rappel</p>
                                </div>
                            @endif

                            @if(in_array($recommandation->statut, ['brouillon', 'rejetee_ig']))
                                <form action="{{ route('its.recommandations.destroy', $recommandation) }}" method="POST" class="pt-4 border-t border-gray-50">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Attention : suppression irréversible. Continuer ?')"
                                        class="flex items-center justify-center w-full px-6 py-3 text-rose-500 hover:text-rose-600 font-bold transition-all text-xs">
                                        <i class="fas fa-trash-alt mr-2 opacity-60"></i> Supprimer la recommandation
                                    </button>
                                </form>
                            @endif

                            @if($recommandation->statut === 'cloturee' && !$recommandation->estArchivee())
                                <form action="{{ route('its.recommandations.archive', $recommandation) }}" method="POST" class="pt-4 border-t border-gray-50">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Archiver cette recommandation ? Elle ne sera plus visible dans la liste principale.')"
                                        class="flex items-center justify-center w-full px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-black rounded-2xl text-sm transition-all shadow-lg shadow-gray-900/10">
                                        <i class="fas fa-archive mr-2 opacity-60"></i> Archiver
                                    </button>
                                </form>
                            @endif
                            
                            @if($recommandation->estArchivee())
                                <div class="p-4 bg-gray-100 rounded-2xl border border-gray-200 text-center">
                                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest"><i class="fas fa-archive mr-1"></i> Cette recommandation est archivée</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Documents & Audit Reports -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest">Docs & Audit</h3>
                            <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-2 py-0.5 rounded-md">{{ $recommandation->documents->count() }} Files</span>
                        </div>
                        
                        @if($recommandation->documents->count() > 0)
                            <div class="space-y-4">
                                @foreach($recommandation->documents as $doc)
                                    <div class="group flex items-center p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:border-blue-200 transition-all">
                                        <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center shadow-sm mr-4 group-hover:bg-blue-50 transition-colors">
                                            <i class="fas fa-file-pdf text-rose-500"></i>
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <div class="text-xs font-black text-gray-800 truncate">{{ $doc->description ?? $doc->file_name }}</div>
                                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $doc->created_at->format('d/m/Y') }}</div>
                                        </div>
                                        <a href="{{ route('its.recommandations.download', $doc) }}" class="h-8 w-8 flex items-center justify-center text-gray-400 hover:text-blue-600 transition-all">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <div class="bg-gray-50 h-16 w-16 mx-auto rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-inbox text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Aucun document joint</p>
                            </div>
                        @endif
                    </div>

                    <!-- Timeline / Meta -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Récapitulatif</h3>
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="h-2 w-2 rounded-full bg-blue-500 mt-1.5 mr-4 ring-4 ring-blue-50"></div>
                                <div>
                                    <div class="text-[10px] font-black text-gray-400 uppercase mb-1">Créé par</div>
                                    <div class="text-xs font-bold text-gray-800">{{ $recommandation->createur->name ?? 'Système' }}</div>
                                </div>
                            </div>
                            @if($recommandation->pointFocal)
                                <div class="flex items-start">
                                    <div class="h-2 w-2 rounded-full bg-emerald-500 mt-1.5 mr-4 ring-4 ring-emerald-50"></div>
                                    <div>
                                        <div class="text-[10px] font-black text-gray-400 uppercase mb-1">Point Focal (Exécution)</div>
                                        <div class="text-xs font-bold text-gray-800">{{ $recommandation->pointFocal->name }}</div>
                                        <div class="text-[10px] text-gray-500 mt-1 italic">{{ $recommandation->pointFocal->email }}</div>
                                    </div>
                                </div>
                            @endif
                            <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                                <div class="text-[10px] font-black text-gray-400 uppercase">Jours restants</div>
                                @php
                                    $jours = now()->startOfDay()->diffInDays($recommandation->date_limite->startOfDay(), false);
                                @endphp
                                <div class="text-sm font-black {{ $jours < 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                    {{ $jours < 0 ? 'En retard (' . abs($jours) . 'j)' : $jours . ' jours' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Rappel -->
    <div id="modal-rappel" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" onclick="document.getElementById('modal-rappel').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('its.recommandations.rappel', $recommandation) }}" method="POST">
                    @csrf
                    <div class="px-8 pt-8 pb-6">
                        <h3 class="text-xl font-black text-[#1E293B] mb-2">Envoyer un rappel</h3>
                        <p class="text-sm font-medium text-gray-500 mb-6">Relancez les acteurs concernés par cette recommandation.</p>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Destinataire</label>
                                <select name="destinataire" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="">Sélectionner un destinataire...</option>
                                    @if($recommandation->pointFocal)
                                        <option value="point_focal">Point Focal ({{ $recommandation->pointFocal->name }})</option>
                                    @endif
                                    @if($recommandation->responsable)
                                        <option value="responsable">Responsable ({{ $recommandation->responsable->name }})</option>
                                    @endif
                                    <option value="inspecteur_general">Inspecteur Général</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Message personnalisé</label>
                                <textarea name="message" rows="4" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-500" placeholder="Votre message..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-6 bg-gray-50/50 flex flex-row-reverse space-x-reverse space-x-3">
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl text-sm transition-all shadow-lg shadow-blue-900/20">
                            Envoyer le rappel
                        </button>
                        <button type="button" class="px-6 py-3 font-bold text-gray-500 hover:text-gray-700 text-sm" onclick="document.getElementById('modal-rappel').classList.add('hidden')">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection