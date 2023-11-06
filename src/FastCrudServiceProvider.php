<?php

namespace Satriotol\Fastcrud;

use Illuminate\Support\ServiceProvider;

class FastCrudServiceProvider extends ServiceProvider
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
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/resources' => resource_path('/'),
                __DIR__ . '/routes/auth.php' => base_path('routes/auth.php'),
                __DIR__ . '/routes/web.php' => base_path('routes/web.php'),
                __DIR__ . '/lang' => base_path('lang/'),
                __DIR__ . '/app' => app_path('/'),
                __DIR__ . '/config' => config_path('/'),
                __DIR__ . '/database/migrations' => database_path('migrations/'),
                __DIR__ . '/database/seeders' => database_path('seeders/'),
                __DIR__ . '/package.json' => base_path('package.json'), // Pastikan path ini benar
            ], 'fastcrudStarter');
            $this->publishes([
                __DIR__ . '/app/Http/Controllers/CrudController.php' => app_path('Http/Controllers/CrudController.php'),
                __DIR__ . '/app/Traits' => app_path('/Traits'),
                __DIR__ . '/resources' => resource_path('/'),
                __DIR__ . '/app' => app_path('/'),
            ], 'fastcrudContinue');
            $this->publishes([
                __DIR__ . '/public' => public_path('/'),
            ], 'fastcrudPublic');
        }
    }
}
