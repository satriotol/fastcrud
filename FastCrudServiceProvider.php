<?php

namespace satriotol\fastcrud;

use Illuminate\Support\ServiceProvider;

class FastCrudServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'fastcrud');
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        $this->publishes([
            __DIR__ . 'resources/views' => resource_path('views/')
        ]);
    }
    public function register()
    {
    }
}
