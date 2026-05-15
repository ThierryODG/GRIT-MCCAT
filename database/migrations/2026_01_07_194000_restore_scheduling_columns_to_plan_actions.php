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
        Schema::table('plan_actions', function (Blueprint $table) {
            if (!Schema::hasColumn('plan_actions', 'date_debut_prevue')) {
                $table->date('date_debut_prevue')->nullable()->after('delai_mois');
            }
            if (!Schema::hasColumn('plan_actions', 'date_fin_prevue')) {
                $table->date('date_fin_prevue')->nullable()->after('date_debut_prevue');
            }
            if (!Schema::hasColumn('plan_actions', 'motif_rejet_ig')) {
                $table->text('motif_rejet_ig')->nullable();
            }
            if (!Schema::hasColumn('plan_actions', 'motif_rejet_responsable')) {
                $table->text('motif_rejet_responsable')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_actions', function (Blueprint $table) {
            $table->dropColumn(['date_debut_prevue', 'date_fin_prevue', 'motif_rejet_ig', 'motif_rejet_responsable']);
        });
    }
};
