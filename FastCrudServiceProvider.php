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
            __DIR__ . 'resources/views' => resource_path('views/')
        ]);
    }
}
