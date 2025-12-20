<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;

$userId = 5;
$pendingRecStatuts = ['plan_soumis_responsable', 'plan_rejete_responsable', 'plan_rejete_ig'];

$pointFocaux = \App\Models\User::whereHas('recommandationsAssignees', function ($q) use ($userId, $pendingRecStatuts) {
    $q->whereHas('plansAction', function ($qa) use ($userId, $pendingRecStatuts) {
        $qa->where(function ($q2) use ($userId) {
                $q2->whereNull('responsable_id')
                   ->orWhere('responsable_id', $userId);
            })
           ->whereHas('recommandation', function ($qr) use ($pendingRecStatuts) {
               $qr->whereIn('statut', $pendingRecStatuts);
           });
    });
})
->with(['recommandationsAssignees' => function ($q) use ($userId, $pendingRecStatuts) {
    $q->whereHas('plansAction', function ($qa) use ($userId, $pendingRecStatuts) {
          $qa->where(function ($q2) use ($userId) {
                  $q2->whereNull('responsable_id')
                     ->orWhere('responsable_id', $userId);
              })
             ->whereHas('recommandation', function ($qr) use ($pendingRecStatuts) {
                 $qr->whereIn('statut', $pendingRecStatuts);
             });
      })
      ->with(['structure','plansAction' => function ($qa) use ($userId, $pendingRecStatuts) {
          $qa->where(function ($q2) use ($userId) {
                  $q2->whereNull('responsable_id')
                     ->orWhere('responsable_id', $userId);
              })
             ->whereHas('recommandation', function ($qr) use ($pendingRecStatuts) {
                 $qr->whereIn('statut', $pendingRecStatuts);
             });
      }])
      ->orderBy('reference', 'asc');
}])->orderBy('name','asc')->get();

$out = [];
foreach ($pointFocaux as $pf) {
    $rf = [];
    foreach ($pf->recommandationsAssignees as $r) {
        $plans = [];
        foreach ($r->plansAction as $p) {
            $plans[] = ['plan_id'=>$p->id, 'recommandation_statut'=>optional($p->recommandation)->statut, 'responsable_id'=>$p->responsable_id];
        }
        $rf[] = ['recommandation_id'=>$r->id,'reference'=>$r->reference,'plans'=>$plans];
    }
    $out[] = ['pointfocal_id'=>$pf->id,'name'=>$pf->name,'recommandations'=>$rf];
}

echo json_encode($out, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
