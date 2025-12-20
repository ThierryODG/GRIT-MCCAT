<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter les champs à la table recommandations
        Schema::table('recommandations', function (Blueprint $table) {
            $table->text('indicateurs')->nullable()->after('description');
            $table->enum('incidence_financiere', ['faible','moyen','eleve'])->nullable()->after('indicateurs');
            $table->integer('delai_mois')->nullable()->after('incidence_financiere');
            $table->date('date_debut_prevue')->nullable()->after('delai_mois');
            $table->date('date_fin_prevue')->nullable()->after('date_debut_prevue');
        });

        // Supprimer les mêmes champs de plan_actions si présents
        Schema::table('plan_actions', function (Blueprint $table) {
            if (Schema::hasColumn('plan_actions', 'indicateurs')) {
                $table->dropColumn(['indicateurs']);
            }
            if (Schema::hasColumn('plan_actions', 'incidence_financiere')) {
                $table->dropColumn(['incidence_financiere']);
            }
            if (Schema::hasColumn('plan_actions', 'delai_mois')) {
                $table->dropColumn(['delai_mois']);
            }
            if (Schema::hasColumn('plan_actions', 'date_debut_prevue')) {
                $table->dropColumn(['date_debut_prevue']);
            }
            if (Schema::hasColumn('plan_actions', 'date_fin_prevue')) {
                $table->dropColumn(['date_fin_prevue']);
            }
        });
    }

    public function down(): void
    {
        // Remettre les champs dans plan_actions et supprimer de recommandations
        Schema::table('plan_actions', function (Blueprint $table) {
            $table->text('indicateurs')->nullable()->after('action');
            $table->enum('incidence_financiere', ['faible','moyen','eleve'])->nullable()->after('indicateurs');
            $table->integer('delai_mois')->nullable()->after('incidence_financiere');
            $table->date('date_debut_prevue')->nullable()->after('delai_mois');
            $table->date('date_fin_prevue')->nullable()->after('date_debut_prevue');
        });

        Schema::table('recommandations', function (Blueprint $table) {
            if (Schema::hasColumn('recommandations', 'indicateurs')) {
                $table->dropColumn(['indicateurs']);
            }
            if (Schema::hasColumn('recommandations', 'incidence_financiere')) {
                $table->dropColumn(['incidence_financiere']);
            }
            if (Schema::hasColumn('recommandations', 'delai_mois')) {
                $table->dropColumn(['delai_mois']);
            }
            if (Schema::hasColumn('recommandations', 'date_debut_prevue')) {
                $table->dropColumn(['date_debut_prevue']);
            }
            if (Schema::hasColumn('recommandations', 'date_fin_prevue')) {
                $table->dropColumn(['date_fin_prevue']);
            }
        });
    }
};
