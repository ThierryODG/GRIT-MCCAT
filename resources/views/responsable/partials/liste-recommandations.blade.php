<!-- Liste des recommandations -->
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Référence</th>
                <th>Point Focal</th>
                <th>Description</th>
                <th>Statut</th>
                <th>Date Limite</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recommandations as $recommandation)
            <tr>
                <td>
                    <strong>{{ $recommandation->reference ?? 'N/A' }}</strong>
                </td>
                <td>
                    @if($recommandation->pointFocal)
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-2">
                                <div class="avatar-title rounded-circle bg-primary">
                                    {{ strtoupper(substr($recommandation->pointFocal->name, 0, 2)) }}
                                </div>
                            </div>
                            <div>
                                {{ $recommandation->pointFocal->name }}
                                <small class="d-block text-muted">{{ $recommandation->pointFocal->direction ?? 'Non définie' }}</small>
                            </div>
                        </div>
                    @else
                        <span class="text-muted">Non assigné</span>
                    @endif
                </td>
                <td>
                    <div class="text-wrap" style="max-width: 300px;">
                        {{ Str::limit($recommandation->description, 100) }}
                    </div>
                </td>
                <td>
                    @include('shared.status-badge', ['statut' => $recommandation->statut])
                </td>
                <td>
                    @if($recommandation->date_limite)
                        <div class="d-flex align-items-center">
                            <i class="far fa-calendar-alt me-2"></i>
                            {{ $recommandation->date_limite->format('d/m/Y') }}
                        </div>
                    @else
                        <span class="text-muted">Non définie</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group">
                        @if(!empty($actions['assigner_point_focal']))
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                    onclick="afficherModalAssignation('{{ $recommandation->id }}')">
                                <i class="fas fa-user-plus"></i>
                            </button>
                        @endif

                        @if(!empty($actions['voir_details']))
                            <a href="{{ route('responsable.recommandations.show', $recommandation) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        @endif

                        @if(!empty($actions['valider_plan']))
                            <button type="button" class="btn btn-sm btn-outline-success"
                                    onclick="afficherModalValidation('{{ $recommandation->id }}')">
                                <i class="fas fa-check"></i>
                            </button>
                        @endif

                        @if(!empty($actions['rejeter_plan']))
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="afficherModalRejet('{{ $recommandation->id }}')">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif

                        @if(!empty($actions['suivi_progression']))
                            <a href="{{ route('responsable.recommandations.progression', $recommandation) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-chart-line"></i>
                            </a>
                        @endif

                        @if(!empty($actions['relancer']))
                            <button type="button" class="btn btn-sm btn-outline-warning"
                                    onclick="afficherModalRelance('{{ $recommandation->id }}')">
                                <i class="fas fa-bell"></i>
                            </button>
                        @endif

                        @if(!empty($actions['valider_cloture']))
                            <button type="button" class="btn btn-sm btn-outline-success"
                                    onclick="afficherModalCloture('{{ $recommandation->id }}')">
                                <i class="fas fa-flag-checkered"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-inbox fa-2x mb-3"></i>
                        <p class="mb-0">Aucune recommandation trouvée</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modals requis -->
@include('responsable.partials.modals.assignation-point-focal')
@include('responsable.partials.modals.validation-plan')
@include('responsable.partials.modals.rejet-plan')
@include('responsable.partials.modals.relance')
@include('responsable.partials.modals.cloture')

@push('scripts')
<script>
function afficherModalAssignation(recommandationId) {
    $('#modal-assignation-point-focal').modal('show');
    $('#form-assignation-point-focal').attr('action', `/responsable/recommandations/${recommandationId}/assigner-point-focal`);
}

function afficherModalValidation(recommandationId) {
    $('#modal-validation-plan').modal('show');
    $('#form-validation-plan').attr('action', `/responsable/recommandations/${recommandationId}/valider-plan`);
}

function afficherModalRejet(recommandationId) {
    $('#modal-rejet-plan').modal('show');
    $('#form-rejet-plan').attr('action', `/responsable/recommandations/${recommandationId}/rejeter-plan`);
}

function afficherModalRelance(recommandationId) {
    $('#modal-relance').modal('show');
    $('#form-relance').attr('action', `/responsable/recommandations/${recommandationId}/relancer`);
}

function afficherModalCloture(recommandationId) {
    $('#modal-cloture').modal('show');
    $('#form-cloture').attr('action', `/responsable/recommandations/${recommandationId}/cloturer`);
}
</script>
@endpush
