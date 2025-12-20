@extends('layouts.app')

@section('title', 'Validation des Plans d\'Action')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Validation des Plans d'Action</h1>
        <p class="mt-1 text-gray-600">Vérifiez et validez les recommandations soumises par les structures (statut: <strong>plan_soumis_ig</strong>)</p>
    </div>

    @if(!empty($structures) && $structures->count() > 0)
        <!-- Liste par Structure -->
        <div class="space-y-6">
            @foreach($structures as $structure)
            <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
                <!-- En-tête Structure -->
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">
                                <i class="mr-2 text-blue-600 fas fa-building"></i>
                                {{ $structure->nom }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                <i class="mr-1 fas fa-users"></i>
                                {{ $structure->recommandations->count() }} recommandation(s) en attente
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Recommandations de la Structure -->
                <div class="divide-y divide-gray-200">
                    @foreach($structure->recommandations as $recommandation)
                    <div class="p-4 mb-3 transition bg-white border border-gray-100 rounded-lg shadow-sm hover:bg-gray-50">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $recommandation->reference }}</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ Str::limit($recommandation->titre, 80) }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $recommandation->plans_action_count ?? $recommandation->plansAction->count() }} plan(s)
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                    {{ $recommandation->priorite == 'haute' ? 'bg-red-100 text-red-800' :
                                       ($recommandation->priorite == 'moyenne' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($recommandation->priorite) }}
                                </span>
                            </div>
                        </div>

                        <!-- Badge de statut -->
                        <div class="flex items-center mt-2 mb-3 space-x-2">
                            @if($recommandation->statut == 'plan_soumis_ig')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    <i class="mr-1 fas fa-upload"></i> Plan soumis à l'IG
                                </span>
                            @elseif($recommandation->statut == 'plan_valide_ig')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="mr-1 fas fa-check-circle"></i> Validée par l'IG
                                </span>
                            @endif
                        </div>

                        <!-- Information du Point Focal / Responsable -->
                        <div class="mt-3 space-y-1 text-sm text-gray-500">
                            <p>
                                <i class="mr-1 fas fa-user"></i>
                                <strong>Point Focal :</strong> {{ optional($recommandation->pointFocal)->name ?? 'Non assigné' }}
                            </p>
                            <p>
                                <i class="mr-1 fas fa-user-tie"></i>
                                <strong>Responsable :</strong> {{ optional($recommandation->responsable)->name ?? 'N/A' }}
                            </p>
                            <p>
                                <i class="mr-1 fas fa-calendar"></i>
                                <strong>Date limite :</strong> {{ $recommandation->date_limite?->format('d/m/Y') ?? 'Non spécifié' }}
                                @if(method_exists($recommandation, 'estEnRetard') && $recommandation->estEnRetard())
                                <span class="inline-block ml-1 font-semibold text-red-600">🚨 En retard</span>
                                @endif
                            </p>
                        </div>

                        <!-- Résumé des plans -->
                        <div class="pt-3 mt-3 border-t border-gray-100">
                            <p class="mb-2 text-xs font-medium text-gray-700">Résumé des plans :</p>
                            @php
                                $summary = $recommandation->summarizePlansValidation();
                            @endphp
                            <div class="flex items-center space-x-3">
                                <span class="text-xs text-gray-600">
                                    <span class="font-medium">{{ $summary['total'] }}</span> total
                                </span>
                                @if($summary['en_attente'] > 0)
                                <span class="inline-flex items-center text-xs text-orange-600">
                                    <i class="mr-1 fas fa-clock"></i> {{ $summary['en_attente'] }} en attente
                                </span>
                                @endif
                                @if($summary['valide'] > 0)
                                <span class="inline-flex items-center text-xs text-green-600">
                                    <i class="mr-1 fas fa-check"></i> {{ $summary['valide'] }} validé(s)
                                </span>
                                @endif
                                @if($summary['rejete'] > 0)
                                <span class="inline-flex items-center text-xs text-red-600">
                                    <i class="mr-1 fas fa-times"></i> {{ $summary['rejete'] }} rejeté(s)
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end mt-4">
                            <a href="{{ route('inspecteur_general.validation_recommandations.dossier', $recommandation) }}"
                               class="inline-flex items-center px-4 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                                <i class="mr-2 fas fa-folder-open"></i>
                                Ouvrir le dossier
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    @else
        <!-- Aucune recommandation -->
        <div class="py-12 text-center bg-white border border-gray-200 rounded-lg shadow-sm">
            <i class="text-4xl text-gray-400 fas fa-inbox"></i>
            <h2 class="mt-4 text-lg font-semibold text-gray-900">Aucune recommandation en attente</h2>
            <p class="mt-2 text-gray-600">Aucun point focal n'a soumis de plans d'action pour validation.</p>
            <p class="mt-1 text-sm text-gray-500">Les recommandations apparaîtront ici lorsque les points focaux auront soumis leur travail.</p>
        </div>
    @endif
</div>

<!-- Script pour confirmer les actions -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirmation avant rejet
        const rejetForms = document.querySelectorAll('form[action*="rejeter"]');
        rejetForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Êtes-vous sûr de vouloir rejeter cette recommandation ? Le point focal devra la corriger.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
@endsection
