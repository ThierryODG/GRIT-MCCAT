<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['nom' => 'gestion_utilisateurs', 'categorie' => 'administration'],
            ['nom' => 'creer_recommandation', 'categorie' => 'recommandations'],
            ['nom' => 'valider_recommandation', 'categorie' => 'recommandations'],
            ['nom' => 'rejeter_recommandation', 'categorie' => 'recommandations'],
            ['nom' => 'creer_plan_action', 'categorie' => 'plans_action'],
            ['nom' => 'valider_plan_action', 'categorie' => 'plans_action'],
            ['nom' => 'suivi_avancement', 'categorie' => 'suivi'],
            ['nom' => 'generer_rapports', 'categorie' => 'rapports'],
            ['nom' => 'cloturer_recommandation', 'categorie' => 'recommandations'],
            ['nom' => 'supervision_globale', 'categorie' => 'supervision'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'nom' => $permission['nom'],
                'categorie' => $permission['categorie'],
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ 10 permissions créées avec succès');
    }
}
