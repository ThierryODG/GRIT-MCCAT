<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_actions', function (Blueprint $table) {
            $table->id();

            // ==================== CONTENU DU PLAN ====================
            $table->text('action')->nullable(); // Rempli par Point Focal
            $table->text('indicateurs')->nullable();

            // ==================== INCIDENCE FINANCIÈRE ====================
            $table->enum('incidence_financiere', ['faible', 'moyen', 'eleve'])->nullable();

            // ==================== DÉLAIS ====================
            $table->integer('delai_mois')->nullable();
            $table->date('date_debut_prevue')->nullable();
            $table->date('date_fin_prevue')->nullable();

            // ==================== WORKFLOW DE VALIDATION ====================
            $table->enum('statut_validation', [
                'en_attente_responsable',
                'valide_responsable',
                'rejete_responsable',
                'en_attente_ig',
                'valide_ig',
                'rejete_ig'
            ])->default('en_attente_responsable');

            // ==================== STATUT D'EXÉCUTION ====================
            $table->enum('statut_execution', [
                'non_demarre',
                'en_cours',
                'termine'
            ])->default('non_demarre');

            $table->integer('pourcentage_avancement')->default(0);
            $table->text('commentaire_avancement')->nullable();

            // ==================== VALIDATION RESPONSABLE ====================
            $table->foreignId('validateur_responsable_id')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('date_validation_responsable')->nullable();
            $table->text('commentaire_validation_responsable')->nullable();
            $table->text('motif_rejet_responsable')->nullable();

            // ==================== VALIDATION IG ====================
            $table->foreignId('validateur_ig_id')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('date_validation_ig')->nullable();
            $table->text('commentaire_validation_ig')->nullable();
            $table->text('motif_rejet_ig')->nullable();

            // ==================== RELATIONS ====================
            $table->foreignId('recommandation_id')->constrained()->onDelete('cascade');
            $table->foreignId('point_focal_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('responsable_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_actions');
    }
};
