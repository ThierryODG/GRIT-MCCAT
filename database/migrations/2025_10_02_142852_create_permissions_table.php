<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique(); // creer_recommandation, valider_recommandation, etc.
            $table->string('description')->nullable();
            $table->string('categorie')->nullable(); // recommandations, plans_action, rapports, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
