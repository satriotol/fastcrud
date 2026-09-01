<?php

/**
 * Self-check opsi soft delete pada generator CRUD. Jalankan: php tests/soft_delete_check.php
 * Sengaja tanpa PHPUnit — paket ini belum punya test suite.
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use Satriotol\Fastcrud\Traits\CrudFunction;

$base = sys_get_temp_dir() . '/fastcrud-softdelete-check';
foreach (['app/Models', 'app/Repositories', 'database/migrations', 'vendor/satriotol/fastcrud/src/stubs'] as $dir) {
    @mkdir("$base/$dir", 0777, true);
}
foreach (glob(__DIR__ . '/../src/stubs/*.stub') as $stub) {
    copy($stub, "$base/vendor/satriotol/fastcrud/src/stubs/" . basename($stub));
}

$app = new Application($base);
Facade::setFacadeApplication($app);

$generator = new class {
    use CrudFunction;

    public function run(array $data): void
    {
        $this->createMigration($data);
        $this->generateModel($data);
        $this->generateRepository($data);
    }
};

function lintOk(string $file): bool
{
    exec('php -l ' . escapeshellarg($file), $out, $code);
    return $code === 0;
}

$data = [
    'model' => 'SoftTest',
    'singular' => 'soft_test',
    'plural' => 'soft_tests',
    'table' => 'soft_tests',
    'indonesian_name' => 'Soft Test',
    'sidebarLogo' => 'device-imac',
    'columns' => [
        ['column_name' => 'nama', 'column_name_view' => 'Nama', 'type' => 'string', 'nullable' => 0, 'is_file' => 0, 'is_minio' => 0],
        ['column_name' => 'berkas', 'column_name_view' => 'Berkas', 'type' => 'string', 'nullable' => 1, 'is_file' => 1, 'is_minio' => 0],
    ],
];

foreach ([true, false] as $soft) {
    array_map('unlink', glob("$base/database/migrations/*.php"));

    $generator->run($data + ['soft_delete' => $soft]);

    $label = $soft ? 'soft_delete ON' : 'soft_delete OFF';
    $migration = file_get_contents(current(glob("$base/database/migrations/*_create_soft_tests_table.php")));
    $model = file_get_contents("$base/app/Models/SoftTest.php");
    $repository = file_get_contents("$base/app/Repositories/SoftTestRepository.php");

    assert(str_contains($migration, '$table->softDeletes();') === $soft, "$label: kolom deleted_at di migration");
    assert(str_contains($model, 'use Illuminate\Database\Eloquent\SoftDeletes;') === $soft, "$label: import SoftDeletes di model");
    assert(str_contains($model, 'use SoftDeletes;') === $soft, "$label: trait SoftDeletes di model");
    // Hanya badan delete() yang diperiksa: blok hapus file juga muncul di upload logic.
    preg_match('/public function delete\(.+?^    \}/ms', $repository, $deleteMethod);
    assert(str_contains($deleteMethod[0], "Storage::disk('public')->delete") === !$soft, "$label: file upload dipertahankan saat soft delete");

    foreach (['Dummy', '{{', '{modelName'] as $leftover) {
        foreach (['migration' => $migration, 'model' => $model, 'repository' => $repository] as $name => $content) {
            assert(!str_contains($content, $leftover), "$label: placeholder '$leftover' tersisa di $name");
        }
    }

    foreach (glob("$base/database/migrations/*.php") as $file) {
        assert(lintOk($file), "$label: syntax error di " . basename($file));
    }
    assert(lintOk("$base/app/Models/SoftTest.php"), "$label: syntax error di model");
    assert(lintOk("$base/app/Repositories/SoftTestRepository.php"), "$label: syntax error di repository");
}

echo "OK: generator soft delete aman untuk kedua kondisi checkbox\n";
