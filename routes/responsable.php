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
    Route::post('/points-focaux/{recommandation}/assigner', [PointFocalController::class, 'assigner'])->name('points_focaux.assigner');
    Route::post('/points-focaux/{recommandation}/reassigner', [PointFocalController::class, 'reassigner'])->name('points_focaux.reassigner');

    // Validation des plans d'action
    Route::get('/validation-plans', [ValidationPlanController::class, 'index'])->name('validation_plans.index');
    Route::get('/validation-plans/{planAction}', [ValidationPlanController::class, 'show'])->name('validation_plans.show');
    Route::post('/validation-plans/{planAction}/valider', [ValidationPlanController::class, 'valider'])->name('validation_plans.valider');
    Route::post('/validation-plans/{planAction}/rejeter', [ValidationPlanController::class, 'rejeter'])->name('validation_plans.rejeter');
    Route::post('/validation-plans/{planAction}/transmettre', [ValidationPlanController::class, 'transmettre'])->name('validation_plans.transmettre');

    // Suivi des recommandations
    Route::get('/suivi', [SuiviController::class, 'index'])->name('suivi.index');
    Route::get('/suivi/{recommandation}', [SuiviController::class, 'show'])->name('suivi.show');
    Route::get('/suivi/export', [SuiviController::class, 'export'])->name('suivi.export');
});
