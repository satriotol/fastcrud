<?php

/**
 * Smoke test helper filter FastcrudAuditRepository tanpa koneksi database.
 * Jalankan: php tests/audit_filter_check.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Satriotol\Fastcrud\Repositories\FastcrudAuditRepository;

$repo = new FastcrudAuditRepository();
$call = function (string $method, ...$args) use ($repo) {
    $ref = new ReflectionMethod($repo, $method);
    $ref->setAccessible(true);
    return $ref->invoke($repo, ...$args);
};

$failures = 0;
$assert = function (bool $condition, string $message) use (&$failures) {
    echo ($condition ? '[OK]   ' : '[FAIL] ') . $message . PHP_EOL;
    $failures += $condition ? 0 : 1;
};

$assert($call('filled', null) === false, 'filled(null) = false');
$assert($call('filled', '  ') === false, 'filled("  ") = false');
$assert($call('filled', '0') === true, 'filled("0") = true');

$date = $call('parseDate', '2026-10-02');
$assert($date !== null && $date->format('Y-m-d H:i:s') === '2026-10-02 00:00:00', 'parseDate tanggal valid -> awal hari');
$assert($call('parseDate', 'bukan-tanggal') === null, 'parseDate input tidak valid -> null');
$assert($call('parseDate', '') === null, 'parseDate kosong -> null');

$query = new class {
    public array $wheres = [];
    public function where(...$args) { $this->wheres[] = $args; return $this; }
};
$call('whereInteger', $query, 'user_id', '15');
$call('whereInteger', $query, 'user_id', 'abc');
$assert($query->wheres === [['user_id', 15]], 'whereInteger hanya menerapkan nilai numerik');

echo PHP_EOL . ($failures ? "$failures pengecekan gagal" : 'Semua pengecekan lolos') . PHP_EOL;
exit($failures ? 1 : 0);
