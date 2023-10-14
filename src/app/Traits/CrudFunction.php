<?php

namespace App\Traits;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;

trait CrudFunction
{
    protected function generateController($data)
    {
        foreach ($data['columns'] as $d) {
            if ($d['nullable'] == 0) {
                $wajib = 'required';
            } else {
                $wajib = 'nullable';
            }
            $content = "'{$d['column_name']}' => '$wajib',";
            $validations[] = $content;
        }
        $validations = trim(implode("\n", $validations));
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNameSingular}}',
                '{{validations}}',
            ],
            [
                $data['model'],
                $data['plural'],
                $data['singular'],
                $validations,
            ],
            file_get_contents(resource_path("stubs/Controller.stub"))
        );
        file_put_contents(app_path("/Http/Controllers/{$data['model']}Controller.php"), $controllerTemplate);
    }
    protected function generateSidebar($data)
    {
        $singular = $data['singular'];
        $model = $data['model'];
        $sidebarLogo = $data['sidebarLogo'];
        $verticalMenuFile = resource_path('views/layouts/sections/menu/verticalMenu.blade.php');
        $marker = '{{-- CRUD-GENERATOR-SIDEBAR --}}';

        if (file_exists($verticalMenuFile)) {
            $input = <<<HTML
            
            @can('{$singular}-index')
                <li class="menu-item {{ request()->routeIs('{$singular}.*') ? 'active' : '' }}">
                    <a href="{{ route('{$singular}.index') }}" class="menu-link">
                        <i class="menu-icon {$sidebarLogo}"></i>
                        <div>{$model}</div>
                    </a>
                </li>
            @endcan
            HTML;

            $contents = file_get_contents($verticalMenuFile);
            $position = strpos($contents, $marker);

            if ($position !== false) {
                // Sisipkan sidebar setelah marker
                $position += strlen($marker);
                $updatedContents = substr_replace($contents, $input, $position, 0);
                file_put_contents($verticalMenuFile, $updatedContents);
            }
        } else {
            // Handle jika file tidak ditemukan
            echo "File verticalMenu.blade.php tidak ditemukan.";
        }
    }


    protected function viewIndex($data)
    {
        foreach ($data['columns'] as $d) {
            $column = "<td>{{\${$data['singular']}->{$d['column_name']}}}</td>";
            $thead = "<th>{$d['column_name_view']}</th>";
            $rows[] = $column;
            $theadRows[] = $thead;
        }
        $theadRows = trim(implode("\n", $theadRows));
        $rows = trim(implode("\n", $rows));
        $indexTemplate = str_replace(
            [
                '{modelName}',
                '{modelNamePlural}',
                '{modelNameSingular}',
                'TableHead',
                'TableBody'
            ],
            [
                $data['model'],
                $data['plural'],
                $data['singular'],
                $theadRows,
                $rows,
            ],
            file_get_contents(resource_path("stubs/viewIndex.stub"))
        );
        if (!file_exists(resource_path("/views/backend/" . $data['singular']))) {
            mkdir(resource_path("/views/backend/" . $data['singular']));
        }
        file_put_contents(resource_path("/views/backend/{$data['singular']}/index.blade.php"), $indexTemplate);
    }
    protected function viewCreate($data)
    {
        foreach ($data['columns'] as $d) {
            if ($d['nullable'] == '0') {
                $required = 'true';
            } else {
                $required = 'false';
            }
            if ($d['type'] == "string" && $d['is_file'] == true) {
                $input = <<<HTML
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="{$d['column_name']}">{$d['column_name_view']}</label>
                    <div class="col-sm-10">
                        {!! Form::file('{$d['column_name']}', [
                            'class' => 'form-control',
                            'id' => 'formFile',
                            'required' => {$required},
                        ]) !!}
                        @error('{$d['column_name']}')
                            <br>
                            <small class="text-danger">{{ \$message }}</small>
                        @enderror
                    </div>
                </div>
                HTML;
            } elseif ($d["type"] == "string") {
                $input = <<<HTML
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="{$d['column_name']}">{$d['column_name_view']}</label>
                    <div class="col-sm-10">
                        {!! Form::text('{$d['column_name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'), [
                            'class' => 'form-control',
                            'placeholder' => 'Masukkan {$d['column_name_view']}',
                            'required' => {$required},
                        ]) !!}
                        @error('{$d['column_name']}')
                            <small class="text-danger">{{ \$message }}</small>
                        @enderror
                    </div>
                </div>
                    
                HTML;
            }
            if ($d['type'] == 'integer') {
                $input = <<<HTML
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="{$d['column_name']}">{$d['column_name_view']}</label>
                    <div class="col-sm-10">
                        {!! Form::number('{$d['column_name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'), [
                            'class' => 'form-control',
                            'placeholder' => 'Masukkan {$d['column_name_view']}',
                            'required' => {$required},
                        ]) !!}
                        @error('{$d['column_name']}')
                            <small class="text-danger">{{ \$message }}</small>
                        @enderror
                    </div>
                </div>

                HTML;
            }
            if ($d['type'] == 'longText') {
                $input = <<<HTML
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="{$d['column_name']}">{$d['column_name_view']}</label>
                    <div class="col-sm-10">
                        {!! Form::textarea('{$d['column_name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'), [
                            'class' => 'form-control ckeditor',
                            'placeholder' => 'Masukkan {$d['column_name_view']}',
                            'required' => {$required},
                        ]) !!}
                        @error('{$d['column_name']}')
                            <small class="text-danger">{{ \$message }}</small>
                        @enderror
                    </div>
                </div>
        
                HTML;
            }
            if ($d['type'] == 'date') {
                $input = <<<HTML
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="{$d['column_name']}">{$d['column_name_view']}</label>
                    <div class="col-sm-10">
                        {!! Form::date('{$d['column_name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'), [
                            'class' => 'form-control ckeditor',
                            'placeholder' => 'Masukkan {$d['column_name_view']}',
                            'required' => {$required},
                        ]) !!}
                        @error('{$d['column_name']}')
                            <small class="text-danger">{{ \$message }}</small>
                        @enderror
                    </div>
                </div>
        
                HTML;
            }
            if ($d['type'] == 'unsignedBigInteger') {
                $input = <<<HTML
                    <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="{$d['column_name']}">{$d['column_name_view']}</label>
                        <div class="col-sm-10">
                            {!! Form::select('{$d['column_name']}', '', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'), [
                                'class' => 'form-control select2',
                                'placeholder' => 'Masukkan {$d['column_name_view']}',
                                'required' => {$required},
                            ]) !!}
                            @error('{$d['column_name']}')
                                <small class="text-danger">{{ \$message }}</small>
                            @enderror
                        </div>
                    </div>
                HTML;
            }
            if ($d['type'] == 'boolean') {
                $input = <<<HTML
                        <div class="form-check mt-3">
                            {!! Form::checkbox('{$d['column_name']}', true, isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'), [
                                'class' => 'form-check-input',
                                'id' => '{$d['column_name']}',
                                'required' => {$required},
                            ]) !!}
                    <label class="form-check-label" for="{$d['column_name']}"> {$d['column_name_view']} </label>     
                    </div>
                HTML;
            }

            $view[] = $input;
        }
        $view = trim(implode("\n", $view));
        $createTemplate = str_replace(
            [
                '{modelName}',
                '{modelNamePlural}',
                '{modelNameSingular}',
                '{createForm}',

            ],
            [
                $data['model'],
                $data['plural'],
                $data['singular'],
                $view
            ],
            file_get_contents(resource_path("stubs/viewCreate.stub"))
        );
        if (!file_exists(resource_path("/views/backend/" . $data['singular']))) {
            mkdir(resource_path("/views/backend/" . $data['singular']));
        }
        file_put_contents(resource_path("/views/backend/{$data['singular']}/create.blade.php"), $createTemplate);
    }
    protected function storePermission($data)
    {
        $datas = [
            '-index', '-create', '-edit', '-delete'
        ];
        foreach ($datas as $d) {
            Permission::updateOrCreate(
                [
                    'name' => $data['singular'] . $d,
                ],
                [
                    'guard_name' => 'web'
                ]
            );
        }
    }
    protected function addRoute($data)
    {
        $routeFile = base_path('routes/web.php');
        $route = "\nRoute::resource('" . $data['singular'] . "', " . $data['model'] . "Controller::class);";
        $urlRoute = "\n" . 'use App\Http\Controllers\\' . $data['model'] . "Controller;";
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
    protected function createMigration($data)
    {
        foreach ($data['columns'] as $d) {
            $column = "\$table->{$d['type']}('{$d['column_name']}')";

            if ($d['nullable']) {
                $column .= "->{$d['nullable']}()";
            }
            $rows[] = $column . ";\n";
        }
        $rows = trim(implode(str_repeat(' ', 12), $rows), "\n");
        $migrationTemplate = str_replace(
            [
                'DummyStructure',
                'DummyTable',
            ],
            [
                $rows,
                $data['plural'],
            ],
            file_get_contents(resource_path("stubs/Migration.stub"))
        );
        $getDate = Date::now()->format('Y_m_d_His');
        file_put_contents(database_path("/migrations/{$getDate}_create_{$data['plural']}_table.php"), $migrationTemplate);
    }
    protected function generateModel($data)
    {
        foreach ($data['columns'] as $d) {
            $column = $d['column_name'];
            $array = explode(" ", $d['column_name']);
            $array_quoted = array_map(function ($word) {
                return '"' . $word . '"';
            }, $array);
            $string_with_quotes = implode(",", $array_quoted);
            $rows[] = $string_with_quotes;
        }
        $rows = trim(implode(",", $rows));
        $modelTemplate = str_replace(
            ['{{modelName}}', '{{modelNamePlural}}', 'DummyTable'],
            [$data['model'], $data['plural'], $rows],
            file_get_contents(resource_path("stubs/Model.stub"))
        );
        file_put_contents(app_path("/Models/{$data['model']}.php"), $modelTemplate);
    }
}
