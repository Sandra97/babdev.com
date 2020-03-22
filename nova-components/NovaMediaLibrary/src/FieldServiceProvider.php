<?php

namespace BabDev\NovaMediaLibrary;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authorize;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(static function (Application $app) {
            if ($app->routesAreCached()) {
                return;
            }

            Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/babdev/nova-media-library')
                ->group(__DIR__ . '/../routes/api.php');
        });

        Nova::serving(static function (ServingNova $event) {
            Nova::script('media-library-field', __DIR__ . '/../dist/js/field.js');
        });
    }
}
