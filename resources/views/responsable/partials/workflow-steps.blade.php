<!-- Étapes du workflow -->
<div class="workflow-steps">
    <div class="row justify-content-between text-center">
        <!-- Étape 1: Réception et Validation IG -->
        <div class="col workflow-step {{ in_array('validee_ig', array_keys($recommandationsParStatut)) ? 'active' : '' }}">
            <div class="p-3 bg-light rounded">
                <i class="fas fa-file-import fa-2x mb-2 text-primary"></i>
                <h6>Réception et Validation IG</h6>
                <small class="text-muted d-block">Recommandations validées par l'IG</small>
            </div>
        </div>

        <!-- Étape 2: Désignation Point Focal -->
        <div class="col workflow-step {{ in_array('en_attente_point_focal', array_keys($recommandationsParStatut)) ? 'active' : '' }}">
            <div class="p-3 bg-light rounded">
                <i class="fas fa-user-check fa-2x mb-2 text-warning"></i>
                <h6>Désignation Point Focal</h6>
                <small class="text-muted d-block">Attribution au point focal</small>
            </div>
        </div>

        <!-- Étape 3: Élaboration Plan d'Action -->
        <div class="col workflow-step {{ in_array('plan_en_elaboration', array_keys($recommandationsParStatut)) ? 'active' : '' }}">
            <div class="p-3 bg-light rounded">
                <i class="fas fa-edit fa-2x mb-2 text-info"></i>
                <h6>Élaboration Plan</h6>
                <small class="text-muted d-block">Création du plan d'action</small>
            </div>
        </div>

        <!-- Étape 4: Validation Plan -->
        <div class="col workflow-step {{ in_array('plan_soumis_validation', array_keys($recommandationsParStatut)) ? 'active' : '' }}">
            <div class="p-3 bg-light rounded">
                <i class="fas fa-clipboard-check fa-2x mb-2 text-primary"></i>
                <h6>Validation Plan</h6>
                <small class="text-muted d-block">Approbation du plan d'action</small>
            </div>
        </div>

        <!-- Étape 5: Exécution -->
        <div class="col workflow-step {{ in_array('en_cours_execution', array_keys($recommandationsParStatut)) ? 'active' : '' }}">
            <div class="p-3 bg-light rounded">
                <i class="fas fa-play-circle fa-2x mb-2 text-success"></i>
                <h6>Exécution</h6>
                <small class="text-muted d-block">Mise en œuvre du plan</small>
            </div>
        </div>

        <!-- Étape 6: Clôture -->
        <div class="col workflow-step {{ in_array('cloturees', array_keys($recommandationsParStatut)) ? 'active' : '' }}">
            <div class="p-3 bg-light rounded">
                <i class="fas fa-flag-checkered fa-2x mb-2 text-secondary"></i>
                <h6>Clôture</h6>
                <small class="text-muted d-block">Recommandation terminée</small>
            </div>
        </div>
    </div>

    <!-- Ligne de progression -->
    <div class="progress mt-4" style="height: 2px;">
        <div class="progress-bar" role="progressbar" style="width: 100%"></div>
    </div>
</div>
