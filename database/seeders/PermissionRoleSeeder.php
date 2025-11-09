<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les IDs des rôles
        $adminRoleId = DB::table('roles')->where('nom', 'admin')->value('id');
        $itsRoleId = DB::table('roles')->where('nom', 'its')->value('id');
        $igRoleId = DB::table('roles')->where('nom', 'inspecteur_general')->value('id');
        $pfRoleId = DB::table('roles')->where('nom', 'point_focal')->value('id');
        $respRoleId = DB::table('roles')->where('nom', 'responsable')->value('id');
        $cmRoleId = DB::table('roles')->where('nom', 'cabinet_ministre')->value('id');

        // Récupérer les IDs des permissions
        $permissionIds = [
            'gestion_utilisateurs' => DB::table('permissions')->where('nom', 'gestion_utilisateurs')->value('id'),
            'creer_recommandation' => DB::table('permissions')->where('nom', 'creer_recommandation')->value('id'),
            'valider_recommandation' => DB::table('permissions')->where('nom', 'valider_recommandation')->value('id'),
            'rejeter_recommandation' => DB::table('permissions')->where('nom', 'rejeter_recommandation')->value('id'),
            'creer_plan_action' => DB::table('permissions')->where('nom', 'creer_plan_action')->value('id'),
            'valider_plan_action' => DB::table('permissions')->where('nom', 'valider_plan_action')->value('id'),
            'suivi_avancement' => DB::table('permissions')->where('nom', 'suivi_avancement')->value('id'),
            'generer_rapports' => DB::table('permissions')->where('nom', 'generer_rapports')->value('id'),
            'cloturer_recommandation' => DB::table('permissions')->where('nom', 'cloturer_recommandation')->value('id'),
            'supervision_globale' => DB::table('permissions')->where('nom', 'supervision_globale')->value('id'),
        ];

        $rolePermissions = [
            // Admin - Toutes les permissions
            $adminRoleId => array_values($permissionIds),

            // ITS
            $itsRoleId => [
                $permissionIds['creer_recommandation'],
                $permissionIds['cloturer_recommandation'],
                $permissionIds['generer_rapports'],
                $permissionIds['suivi_avancement'],
            ],

            // Inspecteur Général
            $igRoleId => [
                $permissionIds['valider_recommandation'],
                $permissionIds['rejeter_recommandation'],
                $permissionIds['valider_plan_action'],
                $permissionIds['suivi_avancement'],
                $permissionIds['generer_rapports'],
            ],

            // Point Focal
            $pfRoleId => [
                $permissionIds['creer_plan_action'],
                $permissionIds['suivi_avancement'],
            ],

            // Responsable
            $respRoleId => [
                $permissionIds['valider_plan_action'],
                $permissionIds['suivi_avancement'],
                $permissionIds['generer_rapports'],
            ],

            // Cabinet Ministre
            $cmRoleId => [
                $permissionIds['supervision_globale'],
                $permissionIds['generer_rapports'],
            ],
        ];

        foreach ($rolePermissions as $roleId => $permissionList) {
            foreach ($permissionList as $permissionId) {
                DB::table('permission_role')->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Permissions associées aux rôles avec succès');
    }
}
