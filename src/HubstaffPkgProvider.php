<?php

namespace Insyghts\Hubstaff;

use Illuminate\Support\ServiceProvider;
use Insyghts\Hubstaff\Providers\EventServiceProvider;

class HubstaffPkgProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {        
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
