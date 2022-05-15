<?php

namespace App\Domain\Users\Providers;

use App\Domain\Users\Http\Controllers\UtilController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as RouteProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\RateLimiter;

class RouteServiceProvider extends RouteProvider
{
    public function boot()
    {
        $this->configureRateLimiting();
    }

    public function map(Router $router)
    {
        if (config('register.api_routes')){
            $this->mapApiRoutes($router);
        }
    }

    protected function mapApiRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace,
            'prefix' => 'api',
            'middleware' => ['api'],
        ], function (Router $router) {
            $this->mapRoutesPing($router);
        });
    }

    private function mapRoutesPing(Router $router): void
    {
        $router
            ->get('ping', [UtilController::class, 'serverTime'])
            ->name('api.server.ping');
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', fn(Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('hard', fn(Request $request) => Limit::perMinute(2)->by($request->user()?->id ?: $request->ip()));
    }
}
