<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('path'); // Chemin du fichier PDF
            $table->integer('annee');
            $table->string('type')->default('general'); // 'execution', 'cloture', 'global'
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Créateur
            $table->foreignId('recommandation_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
