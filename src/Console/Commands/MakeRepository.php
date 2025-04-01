<?php

namespace Satriotol\Fastcrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRepository extends Command
{
    protected $signature = 'fastcrud:repository {name}';
    protected $description = 'Generate a repository file from a stub';

    public function handle()
    {
        $name = $this->argument('name');
        $modelName = Str::studly($name);
        $stubPath = base_path('vendor/satriotol/fastcrud/src/stubs/repository.stub');
        $targetPath = app_path("Repositories/{$modelName}Repository.php");

        if (!File::exists($stubPath)) {
            $this->error("Stub file not found: {$stubPath}");
            return;
        }

        $stub = File::get($stubPath);
        $stub = str_replace(['{{modelName}}', '{{validations}}'], [$modelName, ""], $stub);

        if (!File::exists(app_path('Repositories'))) {
            File::makeDirectory(app_path('Repositories'), 0755, true);
        }

        File::put($targetPath, $stub);

        $this->info("Repository created: {$targetPath}");
    }
}
