<?php

namespace Satriotol\Fastcrud;

use Illuminate\Support\ServiceProvider;

class FastcrudServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register any package services.
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Bootstrap any package services.

        // Publish configuration file
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/')
        ]);
        $this->publishes([
            __DIR__ . '/resources/stubs' => resource_path('stubs/')
        ]);
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations/')
        ]);
        $this->publishes([
            __DIR__ . '/database/seeders' => database_path('seeders/')
        ]);
        $this->publishes([
            __DIR__ . '/public' => public_path('/')
        ]);
        $this->publishes([
            __DIR__ . '/routes' => base_path('routes/')
        ]);
    }
}
