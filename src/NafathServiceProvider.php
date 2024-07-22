<?php

namespace MohamadZatar\Nafath;

use Illuminate\Support\ServiceProvider;

class NafathServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/nafath.php', 'nafath');
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->publishes([
            __DIR__.'/config/nafath.php' => config_path('nafath.php'),
        ]);
        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations'),
        ], 'migrations');
    }
}
