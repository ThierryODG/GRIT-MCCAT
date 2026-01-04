<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Optimisation & Performance (Indexes)
        Schema::table('recommandations', function (Blueprint $table) {
            // Indexing frequently searched/filtered columns
            $table->index('statut');
            $table->index('priorite');
            $table->index('structure_id');
            $table->index('date_limite');
            $table->index('its_id');
            $table->index('point_focal_id');
            $table->index('responsable_id');
        });

        Schema::table('users', function (Blueprint $table) {
            // Usually email is unique (indexed), but if we search by name or role often:
            $table->index('name');
        });

        Schema::table('plan_actions', function (Blueprint $table) {
            $table->index('statut_execution');
            $table->index('recommandation_id');
            $table->index('point_focal_id');
        });

        // 2. Gestion des Paramètres Système (Settings)
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('string'); // string, integer, boolean, json
                $table->string('description')->nullable();
                $table->timestamps();
            });
            
            // Insert default settings
            DB::table('settings')->insert([
                ['key' => 'alert_deadline_1_days', 'value' => '7', 'type' => 'integer', 'description' => 'Premier rappel avant échéance (jours)'],
                ['key' => 'alert_deadline_2_days', 'value' => '3', 'type' => 'integer', 'description' => 'Deuxième rappel avant échéance (jours)'],
                ['key' => 'default_deadline_months', 'value' => '3', 'type' => 'integer', 'description' => 'Délai par défaut pour une recommandation (mois)'],
            ]);
        }

        // 3. Plan d'Action - Responsable de l'Exécution
        Schema::table('plan_actions', function (Blueprint $table) {
            if (!Schema::hasColumn('plan_actions', 'executant_type')) {
                $table->string('executant_type')->default('moi_meme')->after('action'); // moi_meme, autre
                $table->string('executant_nom')->nullable()->after('executant_type');
                $table->string('executant_role')->nullable()->after('executant_nom');
            }
        });

        // 4. Preuves d'Exécution
        if (!Schema::hasTable('preuves_executions')) {
            Schema::create('preuves_executions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('plan_action_id')->constrained('plan_actions')->onDelete('cascade');
                $table->string('file_path');
                $table->string('file_name');
                $table->string('file_type')->nullable(); // pdf, image, etc.
                $table->timestamps();
            });
        }

        // 5. Documents Joints aux Recommandations (Rapports d'Audit, Annexes)
        if (!Schema::hasTable('recommandation_documents')) {
            Schema::create('recommandation_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('recommandation_id')->constrained('recommandations')->onDelete('cascade');
                $table->string('file_path');
                $table->string('file_name');
                $table->string('description')->nullable(); // Ex: "Rapport d'audit", "Annexe 1"
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommandation_documents');
        Schema::dropIfExists('preuves_executions');
        Schema::dropIfExists('settings');

        Schema::table('plan_actions', function (Blueprint $table) {
            $table->dropColumn(['executant_type', 'executant_nom', 'executant_role']);
            $table->dropIndex(['statut_execution']);
            $table->dropIndex(['recommandation_id']);
            $table->dropIndex(['point_focal_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('recommandations', function (Blueprint $table) {
            $table->dropIndex(['statut']);
            $table->dropIndex(['priorite']);
            $table->dropIndex(['structure_id']);
            $table->dropIndex(['date_limite']);
            $table->dropIndex(['its_id']);
            $table->dropIndex(['point_focal_id']);
            $table->dropIndex(['responsable_id']);
        });
    }
};
