<?php

namespace Insyghts\Hubstaff;

use Illuminate\Support\ServiceProvider;

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
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
