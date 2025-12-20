<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ITS\DashboardController;
use App\Http\Controllers\ITS\RecommandationController;
use App\Http\Controllers\ITS\ClotureController;
use App\Http\Controllers\ITS\RapportController;

Route::prefix('its')->middleware(['auth', /*'its'*/])->name('its.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Recommandations
    Route::get('/recommandations', [RecommandationController::class, 'index'])->name('recommandations.index');
    Route::get('/recommandations/create', [RecommandationController::class, 'create'])->name('recommandations.create');
    Route::post('/recommandations', [RecommandationController::class, 'store'])->name('recommandations.store');
    Route::get('/recommandations/{recommandation}', [RecommandationController::class, 'show'])->name('recommandations.show');
    Route::get('/recommandations/{recommandation}/edit', [RecommandationController::class, 'edit'])->name('recommandations.edit');
    Route::put('/recommandations/{recommandation}', [RecommandationController::class, 'update'])->name('recommandations.update');
    Route::delete('/recommandations/{recommandation}', [RecommandationController::class, 'destroy'])
    ->name('recommandations.destroy');

    // Clôture
    Route::get('/cloture', [ClotureController::class, 'index'])->name('cloture.index');
    Route::post('/cloture/{recommandation}', [ClotureController::class, 'cloturer'])->name('cloture.cloturer');

    // Rapports
    Route::get('/rapports', [App\Http\Controllers\PointFocal\RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/{rapport}', [App\Http\Controllers\PointFocal\RapportController::class, 'show'])->name('rapports.show');

    // Soumettre une recommandation à l'IG
    Route::post('/recommandations/{recommandation}/soumettre', [RecommandationController::class, 'soumettre'])
        ->name('recommandations.soumettre');

    // Suivi de l'exécution
    Route::get('/recommandations/{recommandation}/suivi', [RecommandationController::class, 'suivi'])
        ->name('recommandations.suivi');
    Route::post('/recommandations/{recommandation}/rappel', [RecommandationController::class, 'rappel'])
        ->name('recommandations.rappel');

    // Rejeter une demande de clôture
    Route::post('/cloture/{recommandation}/rejeter', [ClotureController::class, 'rejeter'])
        ->name('cloture.rejeter');
});
