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
            // Validation Responsable
            if (!Schema::hasColumn('plan_actions', 'validateur_responsable_id')) {
                $table->foreignId('validateur_responsable_id')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('plan_actions', 'date_validation_responsable')) {
                $table->datetime('date_validation_responsable')->nullable();
            }
            if (!Schema::hasColumn('plan_actions', 'commentaire_validation_responsable')) {
                $table->text('commentaire_validation_responsable')->nullable();
            }

            // Validation IG
            if (!Schema::hasColumn('plan_actions', 'validateur_ig_id')) {
                $table->foreignId('validateur_ig_id')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('plan_actions', 'date_validation_ig')) {
                $table->datetime('date_validation_ig')->nullable();
            }
            if (!Schema::hasColumn('plan_actions', 'commentaire_validation_ig')) {
                $table->text('commentaire_validation_ig')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_actions', function (Blueprint $table) {
            $table->dropColumn([
                'validateur_responsable_id',
                'date_validation_responsable',
                'commentaire_validation_responsable',
                'validateur_ig_id',
                'date_validation_ig',
                'commentaire_validation_ig',
            ]);
        });
    }
};
