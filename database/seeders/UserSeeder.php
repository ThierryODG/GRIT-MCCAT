<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les IDs des rôles
        $roleIds = [
            'admin' => DB::table('roles')->where('nom', 'admin')->value('id'),
            'its' => DB::table('roles')->where('nom', 'its')->value('id'),
            'inspecteur_general' => DB::table('roles')->where('nom', 'inspecteur_general')->value('id'),
            'point_focal' => DB::table('roles')->where('nom', 'point_focal')->value('id'),
            'responsable' => DB::table('roles')->where('nom', 'responsable')->value('id'),
            'cabinet_ministre' => DB::table('roles')->where('nom', 'cabinet_ministre')->value('id'),
        ];

        $users = [
            [
                'name' => 'Admin System',
                'email' => 'admin@grit.com',
                'role_id' => $roleIds['admin'],
                'structure_id' => null,
                'password' => Hash::make('010203123'),
                'telephone' => '+226 70 00 00 00',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Inspecteur ITS',
                'email' => 'its@grit.com',
                'role_id' => $roleIds['its'],
                'structure_id' => null,
                'password' => Hash::make('010203123'),
                'telephone' => '+226 70 00 00 01',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jean Dupont - IG',
                'email' => 'inspecteur@grit.com',
                'role_id' => $roleIds['inspecteur_general'],
                'structure_id' => null,
                'password' => Hash::make('010203123'),
                'telephone' => '+226 70 00 00 02',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Marie Martin - PF',
                'email' => 'pointfocal@grit.com',
                'role_id' => $roleIds['point_focal'],
                'structure_id' => null,
                'password' => Hash::make('010203123'),
                'telephone' => '+226 70 00 00 03',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pierre Durand - Resp',
                'email' => 'responsable@grit.com',
                'role_id' => $roleIds['responsable'],
                'structure_id' => null,
                'password' => Hash::make('010203123'),
                'telephone' => '+226 70 00 00 04',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ministère Cabinet',
                'email' => 'cabinet@grit.com',
                'role_id' => $roleIds['cabinet_ministre'],
                'structure_id' => null,
                'password' => Hash::make('010203123'),
                'telephone' => '+226 70 00 00 05',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                ...$user,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ 6 utilisateurs créés avec succès');
        $this->command->info('🔑 Mot de passe pour tous: 010203123');
    }
}
