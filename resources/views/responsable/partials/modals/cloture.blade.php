<!-- Modal Clôture -->
<div class="modal fade" id="modal-cloture" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Valider la Clôture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-cloture" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        La validation de la clôture marquera définitivement cette recommandation comme terminée.
                    </div>
                    <div class="mb-3">
                        <label for="commentaire_cloture" class="form-label">Commentaire de clôture</label>
                        <textarea class="form-control" id="commentaire_cloture" name="commentaire" rows="3" required
                                placeholder="Veuillez saisir votre commentaire de clôture..."></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmation_cloture" required>
                            <label class="form-check-label" for="confirmation_cloture">
                                Je confirme que tous les objectifs ont été atteints et que la recommandation peut être clôturée.
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Valider la clôture</button>
                </div>
            </form>
        </div>
    </div>
</div>
