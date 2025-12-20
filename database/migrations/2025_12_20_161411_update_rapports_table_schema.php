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
        Schema::table('rapports', function (Blueprint $table) {
            // Check if columns exist before adding/modifying to be safe, or just add them if we know they are missing.
            // Based on the error, 'annee' is missing. The table likely has the old schema: id, type, contenu, date_generation, utilisateur_id.
            
            if (!Schema::hasColumn('rapports', 'titre')) {
                $table->string('titre')->nullable(); // Make nullable first if data exists, or just string if empty.
            }
            if (!Schema::hasColumn('rapports', 'path')) {
                $table->string('path')->nullable();
            }
            if (!Schema::hasColumn('rapports', 'annee')) {
                $table->integer('annee')->nullable();
            }
            if (!Schema::hasColumn('rapports', 'recommandation_id')) {
                $table->foreignId('recommandation_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('rapports', 'description')) {
                $table->text('description')->nullable();
            }
            
            // Rename utilisateur_id to user_id if it exists and user_id doesn't
            if (Schema::hasColumn('rapports', 'utilisateur_id') && !Schema::hasColumn('rapports', 'user_id')) {
                $table->renameColumn('utilisateur_id', 'user_id');
            } elseif (!Schema::hasColumn('rapports', 'user_id')) {
                 $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }

            // Drop old columns if they exist
            if (Schema::hasColumn('rapports', 'contenu')) {
                $table->dropColumn('contenu');
            }
            if (Schema::hasColumn('rapports', 'date_generation')) {
                $table->dropColumn('date_generation');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapports', function (Blueprint $table) {
            //
        });
    }
};
