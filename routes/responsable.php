<?php

use App\Http\Controllers\Responsable\DashboardController;
use App\Http\Controllers\Responsable\PointFocalController;
use App\Http\Controllers\Responsable\ValidationPlanController;
use App\Http\Controllers\Responsable\SuiviController;
use Illuminate\Support\Facades\Route;

Route::prefix('responsable')->middleware(['auth'])->name('responsable.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des Points Focaux
    Route::get('/points-focaux', [PointFocalController::class, 'index'])->name('points_focaux.index');
    Route::post('/points-focaux/assigner', [PointFocalController::class, 'assigner'])->name('points_focaux.assigner');
    Route::put('/points-focaux/{its}/reassigner', [PointFocalController::class, 'reassigner'])->name('points_focaux.reassigner');
    Route::delete('/points-focaux/{its}/retirer', [PointFocalController::class, 'retirer'])->name('points_focaux.retirer');

    // Validation des plans d'action
    Route::get('/validation-plans', [ValidationPlanController::class, 'index'])->name('validation_plans.index');
    Route::get('/validation-plans/{recommandation}/dossier', [ValidationPlanController::class, 'dossier'])->name('validation_plans.dossier');
    Route::get('/validation-plans/{planAction}', [ValidationPlanController::class, 'show'])->name('validation_plans.show');
    Route::post('/validation-plans/{recommandation}/valider', [ValidationPlanController::class, 'validerRecommandation'])->name('validation_plans.valider_recommandation');
    Route::post('/validation-plans/{recommandation}/rejeter', [ValidationPlanController::class, 'rejeterRecommandation'])->name('validation_plans.rejeter_recommandation');
    // Transmettre corrections au Point Focal (suite à un rejet IG)
    Route::post('/validation-plans/{recommandation}/transmettre-pf', [ValidationPlanController::class, 'transmettreAuPointFocal'])->name('validation_plans.transmettre_pf');
    
    // Suivi des recommandations
    Route::get('/suivi', [SuiviController::class, 'index'])->name('suivi.index');
    Route::get('/suivi/{recommandation}', [SuiviController::class, 'show'])->name('suivi.show');
    Route::get('/suivi/export', [SuiviController::class, 'export'])->name('suivi.export');

    // Rapports
    Route::get('/rapports', [App\Http\Controllers\PointFocal\RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/{rapport}', [App\Http\Controllers\PointFocal\RapportController::class, 'show'])->name('rapports.show');
});
