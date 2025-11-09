<?php

namespace App\Providers;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;
class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */

public function boot()
{
        $this->routes(function () {
            
            // Routes Inspecteur General
            Route::prefix('inspecteur')
                ->middleware(['web', 'auth', 'inspecteur_general'])
                ->name('inspecteur.')
                ->group(base_path('routes/inspecteur_general.php'));

            // Routes ITS
            Route::prefix('its')
                ->middleware(['web', 'auth', 'its'])
                ->name('its.')
                ->group(base_path('routes/its.php'));

            // Routes Administrateur
            Route::prefix('admin')
                ->middleware(['web', 'auth', 'administrateur'])
                ->name('admin.')
                ->group(base_path('routes/administrateur.php'));

            // Routes Responsable
            Route::prefix('responsable')
                ->middleware(['web', 'auth', 'responsable'])
                ->name('responsable.')
                ->group(base_path('routes/responsable.php'));

            // Routes Point Focal
            Route::prefix('point-focal')
                ->middleware(['web', 'auth', 'point_focal'])
                ->name('point_focal.')
                ->group(base_path('routes/point_focal.php'));

            // Routes Cabinet Ministre
            Route::prefix('cabinet-ministre')
                ->middleware(['web', 'auth', 'cabinet_ministre'])
                ->name('cabinet_ministre.')
                ->group(base_path('routes/cabinet_ministre.php'));
        });
    }
}

    /**
     * Define the routes for the application.
     */

