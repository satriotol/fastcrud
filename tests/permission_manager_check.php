<?php

/**
 * Self-check permission manager. Jalankan: php tests/permission_manager_check.php
 */

$root = dirname(__DIR__);
$controller = file_get_contents($root . '/src/Controllers/PermissionManagerController.php');
$routes = file_get_contents($root . '/src/fastcrud_web.php');
$index = file_get_contents($root . '/src/Views/permission_manager/index.blade.php');
$edit = file_get_contents($root . '/src/Views/permission_manager/edit.blade.php');

assert(!str_contains($controller, "abort_if(\$role->name === 'SUPERADMIN'"), 'SUPERADMIN tidak boleh diblokir saat update');
assert(!str_contains($index, '> Terkunci</span>'), 'SUPERADMIN harus punya tombol edit');
assert(str_contains($routes, "->name('missing_permissions.store')"), 'route pembuat permission hilang wajib ada');
assert(str_contains($controller, 'Permission::firstOrCreate'), 'pembuatan permission harus idempotent');
assert(str_contains($controller, "'permissions.*' => ['required', 'string', 'distinct', 'max:255']"), 'nama permission wajib divalidasi');
assert(str_contains($edit, 'Buat Permission Tidak Tersedia'), 'tombol pembuat permission hilang wajib ada');
assert(str_contains($edit, 'missingPermissionsForm'), 'form pembuat permission hilang wajib ada');

echo "OK: SUPERADMIN dapat diedit dan permission yang hilang dapat dibuat.\n";
