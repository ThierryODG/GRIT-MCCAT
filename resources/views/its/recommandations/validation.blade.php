@extends('layouts.app')

@section('title', 'Validation - ITS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>Validation des Recommandations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Fonctionnalité en développement</h6>
                        <p class="mb-0">
                            Cette section permettra de valider ou rejeter les recommandations soumises par les ITS.
                            La fonctionnalité sera disponible prochainement.
                        </p>
                    </div>

                    <!-- Exemple de structure future -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Référence</th>
                                    <th>Titre</th>
                                    <th>Priorité</th>
                                    <th>Date soumission</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-clock fa-2x mb-3"></i>
                                        <br>
                                        Aucune recommandation en attente de validation
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
