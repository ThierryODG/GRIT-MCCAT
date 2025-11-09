<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InspecteurGeneral\DashboardController;
use App\Http\Controllers\InspecteurGeneral\ValidationController;
use App\Http\Controllers\InspecteurGeneral\PlanActionController;

Route::prefix('inspecteur-general')->middleware(['auth', /*'inspecteur_general'*/])->name('inspecteur_general.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes pour les plans d'action
    Route::get('/plan-actions', [PlanActionController::class, 'index'])->name('plan_actions.index');
    Route::get('/plan-actions/{planAction}', [PlanActionController::class, 'show'])->name('plan_actions.show');
    Route::post('/plan-actions/{planAction}/validate', [PlanActionController::class, 'validatePlan'])->name('plan_actions.validate');

    // Validation des recommandations - CORRIGÉ
    Route::get('/validation', [ValidationController::class, 'index'])->name('validation.index');
    Route::get('/validation/{recommandation}', [ValidationController::class, 'show'])->name('validation.show'); // ← AJOUTEZ CETTE LIGNE
    Route::post('/validation/{recommandation}/valider', [ValidationController::class, 'valider'])->name('validation.valider');
    Route::post('/validation/{recommandation}/rejeter', [ValidationController::class, 'rejeter'])->name('validation.rejeter');

    // Autres routes
    Route::get('/recommandations', [ValidationController::class, 'recommandations'])->name('recommandations.index');
    Route::get('/suivi', [DashboardController::class, 'suivi'])->name('suivi.index');
    Route::get('/rapports', [DashboardController::class, 'rapports'])->name('rapports.index');
});
