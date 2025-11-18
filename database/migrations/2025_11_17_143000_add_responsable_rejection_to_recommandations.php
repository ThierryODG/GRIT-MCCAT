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
        Schema::table('recommandations', function (Blueprint $table) {
            // Ajouter les champs de rejet au niveau Responsable
            $table->text('motif_rejet_responsable')->nullable()->after('commentaire_ig');
            $table->datetime('date_rejet_responsable')->nullable()->after('motif_rejet_responsable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recommandations', function (Blueprint $table) {
            $table->dropColumn(['motif_rejet_responsable', 'date_rejet_responsable']);
        });
    }
};
