<?php

/**
 * Smoke test migration index created_at pada tabel audits (SQLite in-memory).
 * Jalankan: php tests/audit_index_migration_check.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Schema;

$app = new Container();
Container::setInstance($app);
$app->instance('config', new Config([
    'database' => ['default' => 'default'],
    'audit' => ['drivers' => ['database' => ['connection' => 'default', 'table' => 'audits']]],
]));

$capsule = new Capsule($app);
$capsule->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
$capsule->setAsGlobal();
$app->instance('db', $capsule->getDatabaseManager());
Facade::setFacadeApplication($app);

$failures = 0;
$assert = function (bool $condition, string $message) use (&$failures) {
    echo ($condition ? '[OK]   ' : '[FAIL] ') . $message . PHP_EOL;
    $failures += $condition ? 0 : 1;
};

(require __DIR__ . '/../src/Migrations/2023_10_11_020351_create_audits_table.php');
(new CreateAuditsTable())->up();

$migration = require __DIR__ . '/../src/Migrations/2026_10_02_000000_add_created_at_index_to_audits_table.php';
$schema = Schema::connection('default');

$migration->up();
$assert($schema->hasIndex('audits', 'audits_created_at_index'), 'up() membuat index audits_created_at_index');

$migration->up();
$assert(true, 'up() kedua kali tidak error (idempoten)');

$plan = $capsule->getConnection()->select(
    "EXPLAIN QUERY PLAN SELECT * FROM audits WHERE created_at >= '2026-10-02 00:00:00' AND created_at < '2026-10-03 00:00:00'"
);
$detail = implode(' | ', array_map(fn($row) => $row->detail, $plan));
$assert(str_contains($detail, 'audits_created_at_index'), "query rentang tanggal memakai index ($detail)");

$migration->down();
$assert(! $schema->hasIndex('audits', 'audits_created_at_index'), 'down() menghapus index');

$migration->down();
$assert(true, 'down() kedua kali tidak error');

echo PHP_EOL . ($failures ? "$failures pengecekan gagal" : 'Semua pengecekan lolos') . PHP_EOL;
exit($failures ? 1 : 0);
