<?php
// Script de debug : lister recommandations + plans d'action en attente pour un responsable
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Boot the framework
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$userId = 5; // changez si nécessaire

// Maintenant la logique s'appuie sur le statut de la recommandation
$rows = DB::select(
    'SELECT r.id as recommandation_id, r.reference, r.statut as recommandation_statut, pa.id as plan_id, pa.responsable_id, r.point_focal_id FROM recommandations r JOIN plan_actions pa ON pa.recommandation_id = r.id WHERE pa.responsable_id = ? AND r.statut IN (?,?,?)',
    [$userId, 'plan_soumis_responsable', 'plan_rejete_responsable', 'plan_rejete_ig']
);

echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
