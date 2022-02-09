<?php

namespace Insyghts\Hubstaff;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use yourpackageauthor\YourPackageName\App\Http\Middleware\YourMiddlwareClass;

class HubstaffPkgProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {   
        // Registering all controllers 
        $this->app->make('Insyghts\Hubstaff\Controllers\AttendanceController');
        $this->app->make('Insyghts\Hubstaff\Controllers\ActivitiesController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        // $router->middlewareGroup('yourMiddlwareName', ['namespace\yourmiddlewareclass']);
    }
}
