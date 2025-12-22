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
            // Utilise les routes définies dans web.php pour éviter la duplication
            // ou chargez-les ici mais supprimez-les de web.php.
            // On va privilégier le chargement standard via web.php/api.php si c'est ce que le projet utilise massivement.
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }
}

    /**
     * Define the routes for the application.
     */

