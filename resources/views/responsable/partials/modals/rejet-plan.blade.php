<!-- Modal Rejet Plan -->
<div class="modal fade" id="modal-rejet-plan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rejeter le Plan d'Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-rejet-plan" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Le rejet du plan nécessitera une nouvelle soumission par le point focal.
                    </div>
                    <div class="mb-3">
                        <label for="motif_rejet" class="form-label">Motif du rejet</label>
                        <textarea class="form-control" id="motif_rejet" name="motif_rejet" rows="3" required
                                placeholder="Veuillez expliquer les raisons du rejet..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter le plan</button>
                </div>
            </form>
        </div>
    </div>
</div>
