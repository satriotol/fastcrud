<?php

namespace Satriotol\Fastcrud\Traits;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait CrudFunction
{
    protected function generateController($data)
    {
        $validations = [];
        $uploads = [];
        $booleans = []; // Array for boolean handling

        foreach ($data['columns'] as $d) {
            // Set validation rule based on nullable
            $validationRule = $d['nullable'] == 0 ? 'required' : 'nullable';

            // Prepare upload logic for file fields
            $uploadLogic = '';
            if ($d['is_file']) {
                $uploadLogic = <<<HTML
                    if (\$request->hasFile('{$d['column_name']}')) {
                        \${$d['column_name']} = \$request->file('{$d['column_name']}');
                        \${$d['column_name']}Extension = \${$d['column_name']}->getClientOriginalExtension();
                        \${$d['column_name']}Name = 'file/' . date('mdYHis') . '-' . Str::random(8) . '.' . \${$d['column_name']}Extension;
                        \${$d['column_name']}->storeAs('public', \${$d['column_name']}Name);
                        \$data['{$d['column_name']}'] = \${$d['column_name']}Name;
                    }
                HTML;
            }

            // Prepare boolean logic
            if ($d['type'] == 'boolean') {
                $booleanLogic = <<<HTML
                    \$data['{$d['column_name']}'] = \$request->has('{$d['column_name']}');
                HTML;
                $booleans[] = $booleanLogic;
            }

            // Add validation rule
            $validationContent = "'{$d['column_name']}' => '$validationRule',";
            $validations[] = $validationContent;
            $uploads[] = $uploadLogic;
        }

        $validations = implode("\n", $validations);
        $uploads = implode("\n", $uploads);
        $booleans = implode("\n", $booleans);

        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNameSingular}}',
                '{{validations}}',
                '//is_file',
                '//boolean'
            ],
            [
                $data['model'],
                $data['plural'],
                $data['singular'],
                $validations,
                $uploads,
                $booleans
            ],
            file_get_contents(base_path("vendor/satriotol/fastcrud/src/stubs/Controller.stub"))
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
                        <i class="menu-icon tf-icons ti ti-{$sidebarLogo}"></i>
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
        $searchForm = '<form action="" method="GET"><div class="row">';

        foreach ($data['columns'] as $d) {
            $columnName = $d['column_name'];
            $columnLabel = $d['column_name_view'];

            $searchForm .= <<<HTML
                <div class="col-md-4">
                    {{ html()->label('$columnLabel')->class('form-label') }}
                    {{ html()->text('$columnName')->class('form-control')->placeholder('Cari $columnLabel')->value(@old('$columnName')) }}
                </div>
            HTML;
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
                'SearchForm',
                'TableHead',
                'TableBody'
            ],
            [
                $data['model'],
                $data['plural'],
                $data['singular'],
                $searchForm,
                $theadRows,
                $rows,
            ],
            file_get_contents(base_path("vendor/satriotol/fastcrud/src/stubs/viewIndex.stub"))
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
                        {{html()->file('{$d['column_name']}')->class('form-control')->id('formFile')->required(isset(\${$data['singular']}) ? false : true)}}
                        @error('{$d['column_name']}')
                            <br>
                            <small class="text-danger">{{ \$message }}</small>
                        @enderror
                        @isset(\${$data['singular']})
                            <a href="{{asset('storage/'. \${$data['singular']}->{$d['column_name']})}}" target="_blank">Buka File</a>
                        @endisset
                    </div>
                </div>
                HTML;
            } elseif ($d["type"] == "string") {
                $input = <<<HTML
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="{$d['column_name']}">{$d['column_name_view']}</label>
                    <div class="col-sm-10">
                        {{html()->text('{$d['column_name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'))->class('form-control')->placeholder('Masukkan {$d['column_name_view']}')->required({$required})}}
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
                        {{html()->number('{$d['column_name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'))->class('form-control')->placeholder('Masukkan {$d['column_name_view']}')->required({$required})}}
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
                        {{html()->textarea('{$d['column_name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'))->class('form-control ckeditor')->placeholder('Masukkan {$d['column_name_view']}')->required({$required})}}
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
                        {{html()->date('{$d['column_name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'))->class('form-control')->placeholder('Masukkan {$d['column_name_view']}')->required({$required})}}
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
                            {{html()->select('{$d['column_name']}', '', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'))->class('form-control select2')->placeholder('Masukkan {$d['column_name_view']}')->required({$required})}}
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
                        {{html()->checkbox('{$d['column_name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'))->class('form-check-input')->id('{$d['column_name']}')}}
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
            file_get_contents(base_path("vendor/satriotol/fastcrud/src/stubs/viewCreate.stub"))
        );
        if (!file_exists(resource_path("/views/backend/" . $data['singular']))) {
            mkdir(resource_path("/views/backend/" . $data['singular']));
        }
        file_put_contents(resource_path("/views/backend/{$data['singular']}/create.blade.php"), $createTemplate);
    }
    protected function storePermission($data)
    {
        $datas = [
            '-index',
            '-create',
            '-edit',
            '-delete'
        ];

        // Create or update permissions
        $superAdminRole = Role::findByName('SUPERADMIN');
        foreach ($datas as $d) {
            Permission::updateOrCreate(
                [
                    'name' => $data['singular'] . $d,
                ],
                [
                    'guard_name' => 'web'
                ]
            );

            $superAdminRole->givePermissionTo($data['singular'] . $d);
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
                $column .= "->nullable()";
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
            file_get_contents(base_path("vendor/satriotol/fastcrud/src/stubs/Migration.stub"))
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
            file_get_contents(base_path("vendor/satriotol/fastcrud/src/stubs/Model.stub"))
        );
        file_put_contents(app_path("/Models/{$data['model']}.php"), $modelTemplate);
    }
}
