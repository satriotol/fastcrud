<?php

/**
 * Self-check flag mode maintenance. Jalankan: php tests/maintenance_check.php
 * Sengaja tanpa PHPUnit — paket ini belum punya test suite.
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Satriotol\Fastcrud\Middleware\MaintenanceMode;

$app = new Application(sys_get_temp_dir() . '/fastcrud-maintenance-check');
@mkdir($app->storagePath() . '/framework', 0777, true);

$file = MaintenanceMode::file();
@unlink($file);

assert(MaintenanceMode::active() === false, 'tanpa file, maintenance harus nonaktif');

file_put_contents($file, 'Perbaikan database');
assert(MaintenanceMode::active() === true, 'file ada, maintenance harus aktif');
assert(MaintenanceMode::message() === 'Perbaikan database', 'pesan harus dibaca dari isi file');

file_put_contents($file, "  \n ");
assert(str_contains(MaintenanceMode::message(), 'dalam perbaikan'), 'isi kosong harus jatuh ke pesan bawaan');

unlink($file);
assert(MaintenanceMode::active() === false, 'file dihapus, maintenance harus nonaktif');

echo "OK: flag maintenance nyala/mati/pesan berperilaku benar.\n";
