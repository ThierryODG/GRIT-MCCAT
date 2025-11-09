<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nom' => 'admin', 'description' => 'Administrateur système'],
            ['nom' => 'its', 'description' => 'Inspecteur Technique des Services'],
            ['nom' => 'inspecteur_general', 'description' => 'Inspecteur Général'],
            ['nom' => 'point_focal', 'description' => 'Point Focal'],
            ['nom' => 'responsable', 'description' => 'Responsable de structure'],
            ['nom' => 'cabinet_ministre', 'description' => 'Cabinet du Ministre'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'nom' => $role['nom'],
                'description' => $role['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ 6 rôles créés avec succès');
    }
}
