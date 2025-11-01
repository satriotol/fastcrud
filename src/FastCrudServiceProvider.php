<?php

namespace Satriotol\Fastcrud;

use Illuminate\Support\ServiceProvider;
use Satriotol\Fastcrud\Console\Commands\CreateUserCommand;
use Illuminate\Routing\Router;

class FastCrudServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'fastcrud');
        $this->loadRoutesFrom(__DIR__ . '/fastcrud_web.php');
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
        $router = $this->app->make(Router::class);

        // Registrasi middleware
        $router->aliasMiddleware('force.password.change', \Satriotol\Fastcrud\Middleware\ForcePasswordChange::class);
        $router->aliasMiddleware('api_key', \Satriotol\Fastcrud\Middleware\ApiKeyMiddleware::class);
        $router->aliasMiddleware('permission.json', \Satriotol\Fastcrud\Middleware\EnsurePermissionJson::class);
        $router->aliasMiddleware('2fa', \Satriotol\Fastcrud\Middleware\Google2FAMiddleware::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/resources' => resource_path('/'),
                __DIR__ . '/routes/api.php' => base_path('routes/api.php'),
                __DIR__ . '/routes/auth.php' => base_path('routes/auth.php'),
                __DIR__ . '/routes/web.php' => base_path('routes/web.php'),
                __DIR__ . '/routes/fastcrud_web_generator.php' => base_path('routes/fastcrud_web_generator.php'),
                // __DIR__ . '/routes/fastcrud_web.php' => base_path('routes/fastcrud_web.php'),
                __DIR__ . '/lang' => base_path('lang/'),
                __DIR__ . '/app' => app_path('/'),
                __DIR__ . '/bootstrap' => base_path('bootstrap/'),
                __DIR__ . '/config' => config_path('/'),
                __DIR__ . '/database/seeders' => database_path('seeders/'),
                __DIR__ . '/package.json' => base_path('package.json'), // Pastikan path ini benar
            ], 'fastcrudStarter');
            $this->publishes([
                __DIR__ . '/resources' => resource_path('/'),
                __DIR__ . '/app' => app_path('/'),
                __DIR__ . '/bootstrap' => base_path('bootstrap/'),
            ], 'fastcrudContinue');
            $this->publishes([
                __DIR__ . '/public' => public_path('/'),
            ], 'fastcrudPublic');
            $this->commands([
                \Satriotol\Fastcrud\Console\Commands\MakeRepository::class,
                \Satriotol\Fastcrud\Console\Commands\CreateUserCommand::class,
                \Satriotol\Fastcrud\Console\Commands\ExportRolePermission::class,
            ]);
        }
    }
}
