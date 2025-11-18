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

    // Plans d'action
    Route::get('/plans-action', [PlanActionController::class, 'index'])->name('plans_action.index');
    Route::get('/plans-action/{recommandation}/create', [PlanActionController::class, 'create'])->name('plans_action.create'); // ✅ Corrigé : {planAction} au lieu de {recommandation}
    Route::post('/plans-action/{recommandation}', [PlanActionController::class, 'store'])->name('plans_action.store');
    Route::get('/plans-action/{planAction}/edit', [PlanActionController::class, 'edit'])->name('plans_action.edit');
    Route::put('/plans-action/{planAction}', [PlanActionController::class, 'update'])->name('plans_action.update');
    Route::delete('/plans-action/{planAction}', [PlanActionController::class, 'destroy'])->name('plans_action.destroy');

    // Avancement
    Route::get('/avancement', [AvancementController::class, 'index'])->name('avancement.index');
    Route::get('/avancement/{planAction}/edit', [AvancementController::class, 'edit'])->name('avancement.edit');
    Route::put('/avancement/{planAction}', [AvancementController::class, 'update'])->name('avancement.update');


    // Ajoutez cette ligne dans vos routes point-focal
    Route::get('/dossier/its/{its}', [RecommandationController::class, 'dossierIts'])->name('dossier.its');

    // Clôture
    Route::get('/cloture', [ClotureController::class, 'index'])->name('cloture.index');
    Route::post('/cloture/{recommandation}/demander', [ClotureController::class, 'demander'])->name('cloture.demander');
});
