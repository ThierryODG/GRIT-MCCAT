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
        // Drop the check constraint if it exists. 
        // Note: The constraint name 'rapports_type_check' is standard for Postgres but might vary.
        // We use a try-catch or raw statement that doesn't fail if it doesn't exist, or just try to drop it.
        DB::statement('ALTER TABLE rapports DROP CONSTRAINT IF EXISTS rapports_type_check');
        
        // Alternatively, if we want to redefine it with new values:
        // DB::statement("ALTER TABLE rapports ADD CONSTRAINT rapports_type_check CHECK (type IN ('execution', 'global', 'cloture'))");
        // But for flexibility, removing it is safer as validation is handled in the application.
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
