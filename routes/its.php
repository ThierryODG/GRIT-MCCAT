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

    // Clôture
    Route::get('/cloture', [ClotureController::class, 'index'])->name('cloture.index');
    Route::post('/cloture/{recommandation}', [ClotureController::class, 'cloturer'])->name('cloture.cloturer');

    // Rapports
    Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/generer', [RapportController::class, 'generer'])->name('rapports.generer');
    Route::post('/rapports/generer', [RapportController::class, 'generateReport'])->name('rapports.generate');

    // Soumettre une recommandation à l'IG
    Route::post('/recommandations/{recommandation}/soumettre', [RecommandationController::class, 'soumettre'])
        ->name('recommandations.soumettre');

    // Rejeter une demande de clôture
    Route::post('/cloture/{recommandation}/rejeter', [ClotureController::class, 'rejeter'])
        ->name('cloture.rejeter');
});
