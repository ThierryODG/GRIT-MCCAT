<?php

use App\Http\Controllers\PointFocal\DashboardController;
use App\Http\Controllers\PointFocal\PlanActionController;
use App\Http\Controllers\PointFocal\AvancementController;
use App\Http\Controllers\PointFocal\ClotureController;
use App\Http\Controllers\PointFocal\RecommandationController;
use Illuminate\Support\Facades\Route;

Route::prefix('point-focal')->middleware(['auth'])->name('point_focal.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Mes recommandations
    Route::get('/recommandations', [RecommandationController::class, 'index'])->name('recommandations.index');
    Route::get('/recommandations/{recommandation}', [RecommandationController::class, 'show'])->name('recommandations.show');
    Route::get('/recommandations/{recommandation}/edit', [RecommandationController::class, 'edit'])->name('recommandations.edit');
    Route::put('/recommandations/{recommandation}', [RecommandationController::class, 'update'])->name('recommandations.update');
    Route::post('/recommandations/{recommandation}/soumettre-planification', [RecommandationController::class, 'soumettrePlanification'])->name('recommandations.soumettre_planification');

    // Plans d'action
    Route::get('/plans-action', [PlanActionController::class, 'index'])->name('plans_action.index');
    Route::get('/plans-action/{recommandation}/create', [PlanActionController::class, 'create'])->name('plans_action.create'); // ✅ Corrigé : {planAction} au lieu de {recommandation}
    Route::post('/plans-action/{recommandation}', [PlanActionController::class, 'store'])->name('plans_action.store');
    Route::get('/plans-action/{planAction}/edit', [PlanActionController::class, 'edit'])->name('plans_action.edit');
    Route::put('/plans-action/{planAction}', [PlanActionController::class, 'update'])->name('plans_action.update');
    Route::delete('/plans-action/{planAction}', [PlanActionController::class, 'destroy'])->name('plans_action.destroy');

    // Avancement
    // Avancement
    Route::get('/avancement', [AvancementController::class, 'index'])->name('avancement.index');
    Route::get('/avancement/{recommandation}', [AvancementController::class, 'show'])->name('avancement.show');
    Route::put('/avancement/action/{planAction}', [AvancementController::class, 'updateAction'])->name('avancement.update_action');
    Route::post('/avancement/{recommandation}/cloture', [AvancementController::class, 'demanderCloture'])->name('avancement.cloture');
    Route::post('/avancement/{recommandation}/rappel', [AvancementController::class, 'rappel'])->name('avancement.rappel');
    
    // Preuves
    Route::delete('/avancement/preuve/{preuve}', [AvancementController::class, 'deletePreuve'])->name('avancement.delete_preuve'); 
    // Preuves
    Route::delete('/avancement/preuve/{preuve}', [AvancementController::class, 'deletePreuve'])->name('avancement.delete_preuve'); 
    Route::get('/avancement/preuve/{preuve}/download', [AvancementController::class, 'downloadPreuve'])->name('avancement.download_preuve');
    
    // Rapport d'exécution PDF
    Route::get('/avancement/{recommandation}/rapport', [AvancementController::class, 'downloadReport'])->name('avancement.download_report');

    // Rapports
    Route::get('/rapports', [App\Http\Controllers\PointFocal\RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/create', [App\Http\Controllers\PointFocal\RapportController::class, 'create'])->name('rapports.create');
    Route::post('/rapports', [App\Http\Controllers\PointFocal\RapportController::class, 'store'])->name('rapports.store');
    Route::get('/rapports/{rapport}', [App\Http\Controllers\PointFocal\RapportController::class, 'show'])->name('rapports.show');
    Route::delete('/rapports/{rapport}', [App\Http\Controllers\PointFocal\RapportController::class, 'destroy'])->name('rapports.destroy');

    // Ajoutez cette ligne dans vos routes point-focal
    Route::get('/dossier/its/{its}', [RecommandationController::class, 'dossierIts'])->name('dossier.its');

    // Clôture
    Route::get('/cloture', [ClotureController::class, 'index'])->name('cloture.index');
    Route::post('/cloture/{recommandation}/demander', [ClotureController::class, 'demander'])->name('cloture.demander');
});
