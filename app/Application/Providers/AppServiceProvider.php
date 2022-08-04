<?php

namespace App\Application\Providers;

use App\Application\Console\CustomCommands\MakeModel;
use Illuminate\Session\SessionServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
       if ($this->app->environment('local', 'testing')){
           $this->app->register(SessionServiceProvider::class);
       }

       $this->registerCustomModelsCommand();
    }

    protected function registerCustomModelsCommand()
    {
        $this->app->singleton('command.model', function ($app) {
            return new MakeModel($app['file']);
        });
    }
}
