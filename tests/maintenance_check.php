<?php

/**
 * Self-check flag mode maintenance. Jalankan: php tests/maintenance_check.php
 * Sengaja tanpa PHPUnit — paket ini belum punya test suite.
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Satriotol\Fastcrud\Middleware\MaintenanceMode;

/*
 * Whitelist jalur masuk. Regresi: dulu dicek lewat routeIs('login'), padahal
 * POST /login tidak punya nama route — form login tampil tapi submit-nya 503,
 * sehingga tidak ada seorang pun bisa masuk selama maintenance aktif.
 */
$allowed = [
    ['POST', '/login'],   // <- yang dulu jebol
    ['GET', '/login'],
    ['POST', '/logout'],
    ['GET', '/forgot-password'],
    ['POST', '/forgot-password'],
    ['GET', '/reset-password/abc123'],
    ['POST', '/reset-password'],
    ['GET', '/up'],
];

$blocked = [
    ['GET', '/'],
    ['GET', '/admin/crud'],
    ['GET', '/admin/maintenance'],
    ['POST', '/register'],          // pendaftaran publik memang ditutup
    ['GET', '/daftar/abc123'],
];

foreach ($allowed as [$method, $path]) {
    assert(
        MaintenanceMode::isAllowedPath(Request::create($path, $method)) === true,
        "$method $path harus tetap terbuka saat maintenance"
    );
}

foreach ($blocked as [$method, $path]) {
    assert(
        MaintenanceMode::isAllowedPath(Request::create($path, $method)) === false,
        "$method $path harus ikut terkunci saat maintenance"
    );
}

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

echo 'OK: whitelist jalur masuk (', count($allowed), ' lolos / ', count($blocked), " terkunci) dan flag nyala/mati/pesan berperilaku benar.\n";
