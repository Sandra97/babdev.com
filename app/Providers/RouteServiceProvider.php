<?php

namespace BabDev\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    public function map(): void
    {
        $this->mapWebRoutes();
    }

    private function mapWebRoutes(): void
    {
        /** @var Router $router */
        $router = $this->app->make('router');

        $router->middleware('web')
            ->domain($this->app['config']->get('app.domain', null))
            ->group($this->app->basePath('routes/web.php'));
    }
}
