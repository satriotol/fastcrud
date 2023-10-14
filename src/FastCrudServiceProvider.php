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
        // Publish configuration file
        if ($this->app->runningInConsole()) {
            $baseDir = __DIR__;
    
            $publishGroups = [
                'fastcrudStarter' => [
                    'resources' => 'resources',
                    'routes/auth.php' => 'routes/auth.php',
                    'routes/web.php' => 'routes/web.php',
                    'lang' => 'lang',
                    'app' => 'app',
                    'config' => 'config',
                    'database/migrations' => 'database/migrations',
                    'database/seeders' => 'database/seeders',
                    'package.json' => 'package.json',
                ],
                'fastcrudContinue' => [
                    'app/Http/Controllers/CrudController.php' => 'app/Http/Controllers/CrudController.php',
                    'app/Traits' => 'app/Traits',
                ],
                'fastcrudPublic' => [
                    'public' => 'public',
                ],
            ];
    
            foreach ($publishGroups as $group => $paths) {
                $this->publishes(array_map(function ($path) use ($baseDir) {
                    return "$baseDir/$path";
                }, $paths), $group);
            }
        }
    }
    
}
