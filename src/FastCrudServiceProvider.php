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
                __DIR__ . '/resources/views' => resource_path('views/'),

                __DIR__ . '/routes/auth.php' => base_path('routes/auth.php'),
                __DIR__ . '/routes/web.php' => base_path('routes/web.php'),
                __DIR__ . '/lang' => base_path('lang/'),
                __DIR__ . '/app/console' => app_path('/console'),
                __DIR__ . '/app/Http' => app_path('/Http'),
                __DIR__ . '/app/Models' => app_path('/Models'),
                __DIR__ . '/app/Providers/MenuServiceProvider.php' => app_path('/Providers/MenuServiceProvider.php'),
                __DIR__ . '/app/Helpers/Helpers.php' => app_path('/Helpers/Helpers.php'),
                __DIR__ . '/config' => config_path('/'),
                __DIR__ . '/database/migrations' => database_path('migrations/'),
                __DIR__ . '/database/seeders' => database_path('seeders/'),
                __DIR__ . '/resources/views' => resource_path('views/'),
                __DIR__ . '/resources/menu' => resource_path('menu/'),
            ], 'fastcrudStarter');
            $this->publishes([
                __DIR__ . '/app/Http/Controllers/CrudController.php' => app_path('Http/Controllers/CrudController.php'),
                __DIR__ . '/app/Traits' => app_path('/Traits'),
                __DIR__ . '/resources/stubs' => resource_path('stubs/'),
                __DIR__ . '/resources/views' => resource_path('views/backend/'),
            ], 'fastcrudContinue');
            $this->publishes([
                __DIR__ . '/public' => public_path('/'),
            ], 'fastcrudPublic');
        }
    }
}
