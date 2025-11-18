<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;

$userId = 5;
$pendingPlanStatuses = ['en_attente_responsable', 'rejete_responsable', 'rejete_ig'];

$pointFocaux = \App\Models\User::whereHas('recommandationsAssignees', function ($q) use ($userId, $pendingPlanStatuses) {
    $q->whereHas('plansAction', function ($qa) use ($userId, $pendingPlanStatuses) {
        $qa->where(function ($q2) use ($userId) {
                $q2->whereNull('responsable_id')
                   ->orWhere('responsable_id', $userId);
            })
           ->whereIn('statut_validation', $pendingPlanStatuses);
    });
})
->with(['recommandationsAssignees' => function ($q) use ($userId, $pendingPlanStatuses) {
    $q->whereHas('plansAction', function ($qa) use ($userId, $pendingPlanStatuses) {
          $qa->where(function ($q2) use ($userId) {
                  $q2->whereNull('responsable_id')
                     ->orWhere('responsable_id', $userId);
              })
             ->whereIn('statut_validation', $pendingPlanStatuses);
      })
      ->with(['structure','plansAction' => function ($qa) use ($userId, $pendingPlanStatuses) {
          $qa->where(function ($q2) use ($userId) {
                  $q2->whereNull('responsable_id')
                     ->orWhere('responsable_id', $userId);
              })
             ->whereIn('statut_validation', $pendingPlanStatuses);
      }])
      ->orderBy('reference', 'asc');
}])->orderBy('name','asc')->get();

$out = [];
foreach ($pointFocaux as $pf) {
    $rf = [];
    foreach ($pf->recommandationsAssignees as $r) {
        $plans = [];
        foreach ($r->plansAction as $p) {
            $plans[] = ['plan_id'=>$p->id, 'statut_validation'=>$p->statut_validation, 'responsable_id'=>$p->responsable_id];
        }
        $rf[] = ['recommandation_id'=>$r->id,'reference'=>$r->reference,'plans'=>$plans];
    }
    $out[] = ['pointfocal_id'=>$pf->id,'name'=>$pf->name,'recommandations'=>$rf];
}

echo json_encode($out, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
