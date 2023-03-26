<?php

namespace App\Traits;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;

trait CrudFunction
{
    protected function generateController($data)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNameSingular}}'
            ],
            [
                $data['model'],
                $data['plural'],
                $data['singular']
            ],
            file_get_contents(resource_path("stubs/Controller.stub"))
        );
        file_put_contents(app_path("/Http/Controllers/{$data['model']}Controller.php"), $controllerTemplate);
    }
    protected function viewIndex($data)
    {
        foreach ($data['tables'] as $d) {
            $column = "<td>{{\${$data['singular']}->{$d['name']}}}</td>";
            $thead = "<th>{$d['tampilan']}</th>";
            $rows[] = $column;
            $theadRows[] = $thead;
        }
        $theadRows = trim(implode("\n", $theadRows));
        $rows = trim(implode("\n", $rows));
        $indexTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNameSingular}}',
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
        foreach ($data['tables'] as $d) {
            $first = "<div class='form-group'>\n";
            $label = "{!! Form::label('{$d['name']}', '{$d['tampilan']}') !!}\n";
            if ($d["type"] == "string") {
                $input = "{!! Form::text('{$d['name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['name']} : @old('{$d['name']}'), [
                    'required',
                    'class' => 'form-control',
                    'placeholder' => 'Masukkan {$d['tampilan']}',
                ]) !!}";
            } elseif ($d["type"] == "uuid" || $d["type"] == "unsignedBigInteger") {
                $input = "{!! Form::select('{$d['name']}', '', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['name']} : @old('{$d['name']}'), [
                    'class' => 'form-control select2',
                    'required',
                    'placeholder' => 'Pilih {$d['tampilan']}'
                ]) !!}";
            } elseif ($d['type'] == "longText") {
                $input = "{!! Form::textarea('{$d['name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['name']} : @old('{$d['name']}'), [
                    'required',
                    'class' => 'form-control summernote',
                    'placeholder' => 'Masukkan {$d['tampilan']}',
                ]) !!}";
            } elseif ($d['type'] == "date") {
                $input = "{!! Form::date('{$d['name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['name']} : @old('{$d['name']}'), [
                    'required',
                    'class' => 'form-control',
                    'placeholder' => 'Masukkan {$d['tampilan']}',
                ]) !!}";
            }
            $end = "</div>\n";
            $view[] = $first . $label . $input . $end;
        }
        $view = trim(implode("\n", $view));
        $createTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNameSingular}}',
                '{{createForm}}',

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
        foreach ($data['tables'] as $d) {
            $column = "\$table->{$d['type']}('{$d['name']}')";

            if ($d['is_null']) {
                $column .= "->{$d['is_null']}()";
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
        foreach ($data['tables'] as $d) {
            $column = $d['name'];
            $array = explode(" ", $d['name']);
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
