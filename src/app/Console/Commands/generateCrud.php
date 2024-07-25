<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class generateCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:crud {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Berhasil Membuat CRUD';
    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }
    protected function controller($name)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNameSingular}}'
            ],
            [
                $name,
                strtolower(Str::plural($name)),
                strtolower($name)
            ],
            $this->getStub('Controller')
        );
        file_put_contents(app_path("/Http/Controllers/{$name}Controller.php"), $controllerTemplate);
    }
    protected function model($name)
    {
        $modelTemplate = str_replace(
            ['{{modelName}}', '{{modelNamePlural}}'],
            [$name, strtolower(Str::plural($name))],
            $this->getStub('Model')
        );
        file_put_contents(app_path("/Models/{$name}.php"), $modelTemplate);
    }
    protected function viewIndex($name)
    {
        $indexTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNameSingular}}'
            ],
            [
                $name,
                strtolower(Str::plural($name)),
                strtolower($name)
            ],
            $this->getStub('viewIndex')
        );
        if (!file_exists(resource_path("/views/backend/" . strtolower($name)))) {
            mkdir(resource_path("/views/backend/" . strtolower($name)));
        }
        file_put_contents(resource_path("/views/backend/{$name}/index.blade.php"), $indexTemplate);
    }
    protected function viewCreate($name)
    {
        $createTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNameSingular}}'
            ],
            [
                $name,
                strtolower(Str::plural($name)),
                strtolower($name)
            ],
            $this->getStub('viewCreate')
        );
        if (!file_exists(resource_path("/views/backend/" . strtolower($name)))) {
            mkdir(resource_path("/views/backend/" . strtolower($name)));
        }
        file_put_contents(resource_path("/views/backend/{$name}/create.blade.php"), $createTemplate);
    }
    protected function storePermission($name)
    {
        $data = [
            '-index', '-create', '-edit', '-delete'
        ];
        foreach ($data as $d) {
            Permission::updateOrCreate(
                [
                    'name' => strtolower($name) . $d,
                ],
                [
                    'guard_name' => 'web'
                ]
            );
        }
    }
    protected function addRoute($name)
    {
        $routeFile = base_path('routes/web.php');
        $route = "\nRoute::resource('" . strtolower($name) . "', " . $name . "Controller::class);";
        $urlRoute = "\n" . 'use App\Http\Controllers\\' . $name . "Controller;";
        $after = '// CRUD_GENERATOR';
        $after_url = '// URL_CRUD_GENERATOR';
        if ($after) {
            $contents = file_get_contents($routeFile);
            $line = strpos($contents, $after);

            if ($line !== false) {
                $line += strlen($after) + 1;
                $contents = substr_replace($contents, $route, $line, 0);
                File::put($routeFile, $contents);
            } else {
                File::append($routeFile, $route);
            }
        } else {
            File::append($routeFile, $route);
        }
        if ($after_url) {
            $contents = file_get_contents($routeFile);
            $line = strpos($contents, $after_url);

            if ($line !== false) {
                $line += strlen($after_url) + 1;
                $contents = substr_replace($contents, $urlRoute, $line, 0);
                File::put($routeFile, $contents);
            } else {
                File::append($routeFile, $urlRoute);
            }
        } else {
            File::append($routeFile, $urlRoute);
        }
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $this->controller($name);
        $this->model($name);
        $this->viewIndex($name);
        $this->viewCreate($name);
        Artisan::call('make:migration create_' . strtolower(Str::plural($name)) . '_table');
        $this->addRoute($name);
        // $this->storePermission($name);

        $this->info('Sukses Membuat CRUD.');
        //create api route
        // File::append(
        //     base_path('routes/api.php'),
        //     "Route::post('" . Str::plural(strtolower($name)) . "/create', '{$name}Controller@create');
        //    Route::post('" . Str::plural(strtolower($name)) . "/show', '{$name}Controller@show');
        //    Route::post('" . Str::plural(strtolower($name)) . "/update/{id}', '{$name}Controller@update');
        //    Route::delete('" . Str::plural(strtolower($name)) . "/delete/{id}', '{$name}Controller@delete');"
        // );
        return 'Sukses';
    }
}