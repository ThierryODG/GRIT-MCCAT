<!-- Modal Validation Plan -->
<div class="modal fade" id="modal-validation-plan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Valider le Plan d'Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-validation-plan" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        La validation du plan d'action permettra de démarrer son exécution.
                    </div>
                    <div class="mb-3">
                        <label for="commentaire_validation" class="form-label">Commentaire de validation</label>
                        <textarea class="form-control" id="commentaire_validation" name="commentaire" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Valider le plan</button>
                </div>
            </form>
        </div>
    </div>
</div>
