<?php

namespace Satriotol\Fastcrud\Traits;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

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
                '//boolean',
                '{{indonesian_name}}'
            ],
            [
                $data['model'],
                $data['plural'],
                $data['singular'],
                $validations,
                $uploads,
                $booleans,
                $data['indonesian_name']
            ],
            file_get_contents(base_path("vendor/satriotol/fastcrud/src/stubs/Controller.stub"))
        );

        file_put_contents(app_path("/Http/Controllers/{$data['model']}Controller.php"), $controllerTemplate);
    }

    protected function generateRepository($data)
    {
        $uploadLogic = '';
        $deleteLogic = '';
        $validations = [];
        $messages = []; // Tambahkan array untuk menampung pesan error

        foreach ($data['columns'] as $d) {
            $validationRule = $d['nullable'] == 0 ? 'required' : 'nullable';

            if ($d['is_file']) {
                if ($d['is_minio']) {
                    $uploadLogic .= "\n        if (isset(\$data['{$d['column_name']}']) && \$data['{$d['column_name']}']->isValid()) {";
                    $uploadLogic .= "\n            if (isset(\$model) && \$model->{$d['column_name']}) {";
                    $uploadLogic .= "\n                \$this->removeFiles(\$model->{$d['column_name']});";
                    $uploadLogic .= "\n            }";
                    $uploadLogic .= "\n            \$data['{$d['column_name']}'] = \$this->uploadImage(\$data['{$d['column_name']}'], '{$d['column_name']}');";
                    $uploadLogic .= "\n        }";

                    $deleteLogic .= "\n        if (isset(\$model) && \$model->{$d['column_name']}) {";
                    $deleteLogic .= "\n            \$this->removeFiles(\$model->{$d['column_name']});";
                    $deleteLogic .= "\n        }";
                } else {
                    $uploadLogic .= "\n        if (isset(\$data['{$d['column_name']}']) && \$data['{$d['column_name']}']->isValid()) {";
                    $uploadLogic .= "\n            if (isset(\$model) && \$model->{$d['column_name']}) {";
                    $uploadLogic .= "\n                Storage::disk('public')->delete(\$model->{$d['column_name']});";
                    $uploadLogic .= "\n            }";
                    $uploadLogic .= "\n            \$uploadedFile_{$d['column_name']} = \$data['{$d['column_name']}'];";
                    $uploadLogic .= "\n            \$fileExtension_{$d['column_name']} = \$uploadedFile_{$d['column_name']}->getClientOriginalExtension();";
                    $uploadLogic .= "\n            \$fileName_{$d['column_name']} = date('mdYHis') . '-' . Str::random(8) . '.' . \$fileExtension_{$d['column_name']};";
                    $uploadLogic .= "\n            \$directory_{$d['column_name']} = '{$d['column_name']}/' . date('Y/m/d');";
                    $uploadLogic .= "\n            \$filePath_{$d['column_name']} = \$uploadedFile_{$d['column_name']}->storeAs(\$directory_{$d['column_name']}, \$fileName_{$d['column_name']}, 'public');";
                    $uploadLogic .= "\n            \$data['{$d['column_name']}'] = \$filePath_{$d['column_name']};";
                    $uploadLogic .= "\n        }";

                    $deleteLogic .= "\n        if (isset(\$model->{$d['column_name']})) {";
                    $deleteLogic .= "\n            Storage::disk('public')->delete(\$model->{$d['column_name']});";
                    $deleteLogic .= "\n        }";
                }
            }

            // Tambahkan rule validasi
            $validationContent = "'{$d['column_name']}' => '$validationRule',";
            $validations[] = $validationContent;

            // Tambahkan pesan custom jika required (nullable == 0)
            if ($d['nullable'] == 0) {
                $messages[] = "'{$d['column_name']}.required' => '{$d['column_name_view']} wajib diisi',";
            }
        }

        // Implode dengan spasi agar rapi di file hasil generate (identasi 12 spasi)
        $validations = implode("\n            ", $validations);
        $messages = implode("\n            ", $messages);

        $repositoryTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingular}}',
                '{{validations}}',
                '{{messages}}', // Sisipkan variabel messages
                '//UPLOAD_LOGIC',
                '//DELETE_LOGIC'
            ],
            [
                $data['model'],
                $data['singular'],
                $validations,
                $messages,      // Data array string yang sudah digabung
                $uploadLogic,
                $deleteLogic
            ],
            file_get_contents(base_path("vendor/satriotol/fastcrud/src/stubs/Repository.stub"))
        );

        file_put_contents(app_path("/Repositories/{$data['model']}Repository.php"), $repositoryTemplate);
    }

    protected function generateSidebar($data)
    {
        $singular = $data['singular'];
        $model = $data['model'];
        $indonesianName = $data['indonesian_name'];
        $sidebarLogo = $data['sidebarLogo'];
        $verticalMenuFile = resource_path('views/layouts/sections/menu/verticalMenu.blade.php');
        $marker = '{{-- CRUD-GENERATOR-SIDEBAR --}}';

        if (file_exists($verticalMenuFile)) {
            $input = <<<HTML
            
            @can('{$singular}-index')
                <li class="menu-item {{ request()->routeIs('{$singular}.*') ? 'active' : '' }}">
                    <a href="{{ route('{$singular}.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-{$sidebarLogo}"></i>
                        <div>{$indonesianName}</div>
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
        $searchForm = '';

        foreach ($data['columns'] as $d) {
            $columnName = $d['column_name'];
            $columnLabel = $d['column_name_view'];
            $columnType = $d['type'];
            $columnNullable = $d['nullable'];
            $columnIsFile = $d['is_file'];

            $inputField = '';

            if (!$columnIsFile) {
                switch ($columnType) {
                    case 'string':
                    case 'longText':
                        $inputField = "{{ html()->text('$columnName', old('$columnName'))->class('form-control')->placeholder('Cari $columnLabel') }}";
                        break;

                    case 'integer':
                        $inputField = "{{ html()->number('$columnName', old('$columnName'))->class('form-control')->placeholder('Cari $columnLabel') }}";
                        break;

                    case 'unsignedBigInteger':
                        $inputField = "{{ html()->select('$columnName', [], old('$columnName'))->class('form-select select2')->placeholder('Pilih $columnLabel') }}";
                        break;

                    case 'boolean':
                        $inputField = "{{ html()->select('$columnName', ['1' => 'Ya', '0' => 'Tidak'], old('$columnName'))->class('form-select select2')->placeholder('Pilih $columnLabel') }}";
                        break;

                    case 'date':
                        $inputField = "{{ html()->date('$columnName', old('$columnName'))->class('form-control') }}";
                        break;

                    default:
                        $inputField = "{{ html()->text('$columnName', old('$columnName'))->class('form-control')->placeholder('Cari $columnLabel') }}";
                        break;
                }

                $searchForm .= <<<HTML
                <div class="col-md-4 mb-3">
                    {{ html()->label('$columnLabel')->class('form-label') }}
                    $inputField
                </div>
            HTML;
            }

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
                'TableBody',
                '{indonesian_name}',
            ],
            [
                $data['model'],
                $data['plural'],
                $data['singular'],
                $searchForm,
                $theadRows,
                $rows,
                $data['indonesian_name'],
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
                        {{ html()->file('{$d['column_name']}')->class('form-control')->id('formFile')->required(isset(\${$data['singular']}) ? false : true) }}
                        @error('{$d['column_name']}')
                            <br>
                            <small class="text-danger">{{ \$message }}</small>
                        @enderror
                        @isset(\${$data['singular']})
                            <a href="{{ \${$data['singular']}->{$d['column_name']}_url }}" target="_blank">Buka File</a>
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
                        {{html()->textarea('{$d['column_name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'))->class('form-control')->placeholder('Masukkan {$d['column_name_view']}')->required({$required})}}
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
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-2 col-form-label" for="{$d['column_name']}">{$d['column_name_view']}</label>
                        <div class="col-sm-10">
                            <div class="form-check form-switch">
                            {{html()->checkbox('{$d['column_name']}', isset(\${$data['singular']}) ? \${$data['singular']}->{$d['column_name']} : @old('{$d['column_name']}'))->class('form-check-input')->id('{$d['column_name']}')}}
                            <label class="form-check-label" for="{$d['column_name']}">
                                    Tandai jika persyaratan ini {$d['column_name_view']} dipenuhi
                                </label>
                            </div>
                            @error('{$d['column_name']}')
                                <small class="text-danger">{{ \$message }}</small>
                            @enderror
                        </div>
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
                '{indonesian_name}'

            ],
            [
                $data['model'],
                $data['plural'],
                $data['singular'],
                $view,
                $data['indonesian_name']
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
            '-delete',
            '-show',
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
        $routeFile = base_path('routes/fastcrud_web_generator.php');
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

        $this->createPermissionMigration($data);
    }
    protected function createPermissionMigration($data)
    {
        $suffixes = [
            '-index',
            '-create',
            '-edit',
            '-delete',
            '-show',
        ];

        $permissions = [];
        foreach ($suffixes as $suffix) {
            $permissions[] = "'" . $data['singular'] . $suffix . "'";
        }
        $permissions = implode(",\n" . str_repeat(' ', 8), $permissions);

        $migrationTemplate = str_replace(
            'DummyPermissions',
            $permissions,
            file_get_contents(base_path("vendor/satriotol/fastcrud/src/stubs/PermissionMigration.stub"))
        );

        $getDate = Date::now()->format('Y_m_d_His');
        file_put_contents(database_path("/migrations/{$getDate}_create_{$data['plural']}_permission.php"), $migrationTemplate);
    }
    protected function generateModel($data)
    {
        $fillable = ['"uuid"']; // tambahkan uuid default
        $appends = [];
        $accessors = [];

        foreach ($data['columns'] as $d) {
            $column = $d['column_name'];
            $fillable[] = '"' . $column . '"';

            if ($d['is_file']) {
                $studly = Str::studly($column);
                $appends[] = '"' . $column . '_url"';

                if ($d['is_minio']) {
                    // file di minio
                    $accessors[] = <<<PHP
        public function get{$studly}UrlAttribute()
        {
            return \$this->$column ? route('minio.file', ['url' => \$this->$column]) : null;
        }
    PHP;
                } else {
                    // default pakai public/storage
                    $accessors[] = <<<PHP
        public function get{$studly}UrlAttribute()
        {
            return \$this->$column ? asset('storage/' . \$this->$column) : null;
        }
    PHP;
                }
            }
        }

        $rows = implode(', ', $fillable);
        $appendsRows = !empty($appends) ? 'protected $appends = [' . implode(', ', $appends) . '];' : '';
        $accessorsCode = implode("\n\n", $accessors);

        $modelTemplate = str_replace(
            ['{{modelName}}', '{{modelNamePlural}}', 'DummyTable', 'DummyAppends', 'DummyAccessors'],
            [$data['model'], $data['plural'], $rows, $appendsRows, $accessorsCode],
            file_get_contents(base_path("vendor/satriotol/fastcrud/src/stubs/Model.stub"))
        );

        file_put_contents(app_path("/Models/{$data['model']}.php"), $modelTemplate);
    }
}
