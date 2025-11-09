<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recommandations', function (Blueprint $table) {
            // ==================== IDENTIFICATION ====================
            $table->id();
            $table->string('reference')->unique();
            $table->string('titre');
            $table->text('description');

            // ==================== PRIORITÉ & DÉLAIS ====================
            $table->enum('priorite', ['basse', 'moyenne', 'haute'])->default('moyenne');
            $table->date('date_limite');

            // ==================== WORKFLOW STATUT ====================
            $table->enum('statut', [
                // Phase 1 : Création et validation IG
                'brouillon',
                'soumise_ig',
                'validee_ig',
                'rejetee_ig',

                // Phase 2 : Transmission structure
                'transmise_structure',
                'point_focal_assigne',

                // Phase 3 : Plan d'action
                'plan_en_redaction',
                'plan_soumis_responsable',
                'plan_valide_responsable',
                'plan_soumis_ig',
                'plan_valide_ig',
                'plan_rejete_ig',

                // Phase 4 : Exécution
                'en_execution',
                'execution_terminee',

                // Phase 5 : Clôture
                'demande_cloture',
                'cloturee'
            ])->default('brouillon');

            // ==================== DATES ====================
            $table->datetime('date_validation_ig')->nullable();
            $table->datetime('date_cloture')->nullable();

            // ==================== COMMENTAIRES & MOTIFS ====================
            $table->text('commentaire_ig')->nullable();
            $table->text('motif_rejet_ig')->nullable();
            $table->text('commentaire_demande_cloture')->nullable();
            $table->string('documents_justificatifs')->nullable();
            $table->text('motif_rejet_cloture')->nullable();
            $table->text('commentaire_cloture')->nullable();

            // ==================== RELATIONS ====================
            $table->foreignId('its_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('inspecteur_general_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('responsable_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('point_focal_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('structure_id')->constrained('structures')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recommandations');
    }
};
