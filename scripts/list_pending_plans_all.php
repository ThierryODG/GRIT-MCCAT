<?php
// Debug script: list all plan_actions with certain validation statuses
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::select(
    'SELECT pa.id as plan_id, pa.recommandation_id, pa.responsable_id, r.reference, r.statut as recommandation_statut, r.point_focal_id FROM plan_actions pa LEFT JOIN recommandations r ON r.id = pa.recommandation_id WHERE r.statut IN (?,?,?) ORDER BY pa.id DESC',
    ['plan_soumis_responsable', 'plan_rejete_responsable', 'plan_rejete_ig']
);

echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
