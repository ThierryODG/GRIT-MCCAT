<!-- Modal Assignation Point Focal -->
<div class="modal fade" id="modal-assignation-point-focal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assigner un Point Focal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-assignation-point-focal" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="point_focal_id" class="form-label">Point Focal</label>
                        <select class="form-select" id="point_focal_id" name="point_focal_id" required>
                            <option value="">Sélectionner un point focal</option>
                            @foreach($users->where('role', 'point_focal') as $pointFocal)
                                <option value="{{ $pointFocal->id }}">
                                    {{ $pointFocal->name }} - {{ $pointFocal->direction }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="commentaire" class="form-label">Commentaire (optionnel)</label>
                        <textarea class="form-control" id="commentaire" name="commentaire" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Assigner</button>
                </div>
            </form>
        </div>
    </div>
</div>
