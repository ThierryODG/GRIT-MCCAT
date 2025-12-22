<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InspecteurGeneral\DashboardController;
use App\Http\Controllers\InspecteurGeneral\RecommandationController;
use App\Http\Controllers\InspecteurGeneral\PlanActionController;
use App\Http\Controllers\InspecteurGeneral\SuiviController;
use App\Http\Controllers\InspecteurGeneral\RapportController;

Route::prefix('inspecteur-general')->middleware(['auth', /*'inspecteur_general'*/])->name('inspecteur_general.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Recommandations (Validation initiale)
    Route::prefix('recommandations')->name('recommandations.')->group(function () {
        Route::get('/', [RecommandationController::class, 'index'])->name('index');
        Route::get('/{recommandation}', [RecommandationController::class, 'show'])->name('show');
        Route::post('/{recommandation}/valider', [RecommandationController::class, 'valider'])->name('valider');
        Route::post('/{recommandation}/rejeter', [RecommandationController::class, 'rejeter'])->name('rejeter');
    });

    // Validation des Plans d'action (Dossiers)
    Route::prefix('plan-actions')->name('plan_actions.')->group(function () {
        Route::get('/', [PlanActionController::class, 'index'])->name('index');
        Route::get('/{planAction}', [PlanActionController::class, 'show'])->name('show');
        Route::post('/{planAction}/validate', [PlanActionController::class, 'validatePlan'])->name('validate');
        
        // Dossier consolidé
        Route::get('/recommandation/{recommandation}/dossier', [PlanActionController::class, 'dossier'])->name('recommandation_dossier');
        Route::post('/recommandation/{recommandation}/valider', [PlanActionController::class, 'validerDossier'])->name('recommandation_valider');
        Route::post('/recommandation/{recommandation}/rejeter', [PlanActionController::class, 'rejeterDossier'])->name('recommandation_rejeter');
    });

    // Suivi Global
    Route::prefix('suivi')->name('suivi.')->group(function () {
        Route::get('/', [SuiviController::class, 'index'])->name('index');
        Route::get('/{recommandation}', [SuiviController::class, 'show'])->name('show');
    });

    // Rapports
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [RapportController::class, 'index'])->name('index');
        Route::get('/{rapport}', [RapportController::class, 'show'])->name('show');
    });
});
