<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StructureSeeder extends Seeder
{
    /**
     * Exécuter le seeder
     */
    public function run(): void
    {
        $structures = [
            [
                'code' => 'DSI',
                'nom' => 'Direction des Systèmes d\'Information',
                'sigle' => 'DSI',
                'description' => 'Direction chargée de la gestion des systèmes informatiques et de la digitalisation',
                'active' => true,
            ],
            [
                'code' => 'DRH',
                'nom' => 'Direction des Ressources Humaines',
                'sigle' => 'DRH',
                'description' => 'Direction chargée de la gestion du personnel et des ressources humaines',
                'active' => true,
            ],
            [
                'code' => 'DGTC',
                'nom' => 'Direction Générale du Trésor et de la Comptabilité',
                'sigle' => 'DGTC',
                'description' => 'Direction chargée de la gestion des finances publiques et de la comptabilité',
                'active' => true,
            ],
            [
                'code' => 'DGB',
                'nom' => 'Direction Générale du Budget',
                'sigle' => 'DGB',
                'description' => 'Direction chargée de la préparation et de l\'exécution du budget de l\'État',
                'active' => true,
            ],
            [
                'code' => 'DGI',
                'nom' => 'Direction Générale des Impôts',
                'sigle' => 'DGI',
                'description' => 'Direction chargée de la fiscalité et du recouvrement des impôts',
                'active' => true,
            ],
            [
                'code' => 'SECRETARIAT',
                'nom' => 'Secrétariat Général',
                'sigle' => 'SG',
                'description' => 'Secrétariat général chargé de la coordination administrative',
                'active' => true,
            ],
            [
                'code' => 'CABINET',
                'nom' => 'Cabinet du Ministre',
                'sigle' => 'CAB',
                'description' => 'Cabinet ministériel chargé du suivi des dossiers stratégiques',
                'active' => true,
            ],
            [
                'code' => 'IGF',
                'nom' => 'Inspection Générale des Finances',
                'sigle' => 'IGF',
                'description' => 'Structure chargée du contrôle et de l\'audit des finances publiques',
                'active' => true,
            ],
            [
                'code' => 'ITS',
                'nom' => 'Inspection Technique Spécialisée',
                'sigle' => 'ITS',
                'description' => 'Structure d\'inspection technique et de contrôle des projets',
                'active' => true,
            ],
            [
                'code' => 'DCP',
                'nom' => 'Direction de la Comptabilité Publique',
                'sigle' => 'DCP',
                'description' => 'Direction chargée de la comptabilité publique et de la gestion des fonds',
                'active' => true,
            ],
            [
                'code' => 'DAF',
                'nom' => 'Direction des Affaires Financières',
                'sigle' => 'DAF',
                'description' => 'Direction chargée des affaires financières et de la gestion des marchés',
                'active' => true,
            ],
            [
                'code' => 'DEX',
                'nom' => 'Direction des Études et de la Planification',
                'sigle' => 'DEX',
                'description' => 'Direction chargée des études économiques et de la planification stratégique',
                'active' => true,
            ]
        ];

        foreach ($structures as $structure) {
            DB::table('structures')->insert([
                'code' => $structure['code'],
                'nom' => $structure['nom'],
                'sigle' => $structure['sigle'],
                'description' => $structure['description'],
                'active' => $structure['active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
