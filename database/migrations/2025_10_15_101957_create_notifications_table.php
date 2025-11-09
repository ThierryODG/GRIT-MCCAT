<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                'recommandation_soumise',
                'recommandation_validee',
                'recommandation_rejetee',
                'point_focal_assigne',
                'plan_a_remplir',
                'plan_soumis',
                'plan_valide',
                'plan_rejete',
                'demande_cloture',
                'recommandation_cloturee'
            ]);
            $table->text('contenu');
            $table->datetime('date_envoi')->default(now());
            $table->enum('statut', ['non_lu', 'lu'])->default('non_lu');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('recommandation_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
