<!-- Modal Relance -->
<div class="modal fade" id="modal-relance" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Relancer le Point Focal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-relance" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-clock me-2"></i>
                        Cette action enverra une notification de relance au point focal.
                    </div>
                    <div class="mb-3">
                        <label for="message_relance" class="form-label">Message de relance</label>
                        <textarea class="form-control" id="message_relance" name="message" rows="3" required
                                placeholder="Veuillez saisir votre message de relance..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="nouvelle_date_limite" class="form-label">Nouvelle date limite (optionnel)</label>
                        <input type="date" class="form-control" id="nouvelle_date_limite" name="nouvelle_date_limite">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Envoyer la relance</button>
                </div>
            </form>
        </div>
    </div>
</div>
