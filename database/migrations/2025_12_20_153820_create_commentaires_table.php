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
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('recommandation_id')->constrained()->onDelete('cascade');
            $table->string('destinataire_role')->nullable(); // 'point_focal', 'responsable', 'inspecteur_general'
            $table->text('contenu');
            $table->string('type')->default('general'); // 'rappel', 'cloture', 'general'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentaires');
    }
};
