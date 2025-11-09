@extends('layouts.app')

@section('title', 'Dashboard Point Focal')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Dashboard Point Focal</h1>
        <div class="btn-group">
            <a href="{{ route('recommandations.index') }}" class="btn btn-primary">
                <i class="fas fa-clipboard-list me-2"></i>Mes Recommandations
            </a>
            <a href="{{ route('plan-actions.index') }}" class="btn btn-success">
                <i class="fas fa-tasks me-2"></i>Mes Plans d'Action
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Recommandations Assignées</div>
                            <div class="h5 mb-0">{{ $stats['recommandations_assignees'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Plans à Créer</div>
                            <div class="h5 mb-0">{{ $stats['plans_a_creer'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plus-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Plans en Cours</div>
                            <div class="h5 mb-0">{{ $stats['plans_en_cours'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Plans Terminés</div>
                            <div class="h5 mb-0">{{ $stats['plans_termines'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recommandations récentes assignées -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-clipboard-list me-1"></i>
                    Mes Dernières Recommandations
                </div>
                <div class="card-body">
                    @if($recommandationsRecent->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Titre</th>
                                    <th>Priorité</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recommandationsRecent as $recommandation)
                                <tr>
                                    <td>{{ Str::limit($recommandation->titre, 30) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $recommandation->priorite === 'haute' ? 'danger' : ($recommandation->priorite === 'moyenne' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($recommandation->priorite) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $recommandation->statut === 'validee' ? 'success' : ($recommandation->statut === 'en_cours' ? 'info' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $recommandation->statut)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($recommandation->statut === 'validee' && !$recommandation->planAction)
                                            <a href="{{ route('plan-actions.create-from-recommandation', $recommandation->id) }}"
                                               class="btn btn-sm btn-success">
                                                Créer Plan
                                            </a>
                                        @else
                                            <a href="{{ route('recommandations.show', $recommandation->id) }}"
                                               class="btn btn-sm btn-primary">
                                                Voir
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Aucune recommandation assignée</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mes plans d'action -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-tasks me-1"></i>
                    Mes Plans d'Action
                </div>
                <div class="card-body">
                    @if($mesPlans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Plan</th>
                                    <th>Progression</th>
                                    <th>Échéance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mesPlans as $plan)
                                <tr>
                                    <td>{{ Str::limit($plan->titre, 25) }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $plan->progression == 100 ? 'success' : ($plan->progression > 50 ? 'info' : 'warning') }}"
                                                 style="width: {{ $plan->progression }}%">
                                                {{ $plan->progression }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="{{ $plan->date_fin_prevue < now() && $plan->statut != 'termine' ? 'text-danger fw-bold' : '' }}">
                                        {{ $plan->date_fin_prevue->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('plan-actions.show', $plan->id) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('plan-actions.edit', $plan->id) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-tasks fa-3x mb-3"></i>
                        <p>Aucun plan d'action créé</p>
                        <a href="{{ route('recommandations.sans-plan') }}" class="btn btn-primary">
                            Voir les recommandations sans plan
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes et notifications -->
    <div class="row">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Alertes Importantes
                </div>
                <div class="card-body">
                    @if($plansEnRetard->count() > 0)
                    <div class="alert alert-danger">
                        <h5 class="alert-heading">
                            <i class="fas fa-clock me-2"></i>Plans d'Action en Retard
                        </h5>
                        <ul class="mb-0">
                            @foreach($plansEnRetard as $plan)
                            <li>
                                <strong>{{ $plan->titre }}</strong> -
                                Échéance: {{ $plan->date_fin_prevue->format('d/m/Y') }} -
                                <a href="{{ route('plan-actions.show', $plan->id) }}" class="alert-link">Intervenir</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($recommandationsSansPlan->count() > 0)
                    <div class="alert alert-info">
                        <h5 class="alert-heading">
                            <i class="fas fa-plus-circle me-2"></i>Recommandations sans Plan d'Action
                        </h5>
                        <p class="mb-2">{{ $recommandationsSansPlan->count() }} recommandation(s) validée(s) attendent un plan d'action</p>
                        <a href="{{ route('recommandations.sans-plan') }}" class="btn btn-sm btn-primary">
                            Créer les plans manquants
                        </a>
                    </div>
                    @endif

                    @if($plansEnRetard->count() == 0 && $recommandationsSansPlan->count() == 0)
                    <div class="text-center text-muted">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <p>Aucune alerte importante</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
