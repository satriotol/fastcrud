<?php

/** Smoke test struktur fitur FastcrudNote. Jalankan: php tests/fastcrud_note_check.php */

$root = dirname(__DIR__);
$failures = 0;
$assert = function (bool $condition, string $message) use (&$failures): void {
    echo ($condition ? '[OK]   ' : '[FAIL] ') . $message . PHP_EOL;
    $failures += $condition ? 0 : 1;
};

$model = file_get_contents($root . '/src/Models/FastcrudNote.php');
$controller = file_get_contents($root . '/src/Controllers/FastcrudNoteController.php');
$routes = file_get_contents($root . '/src/fastcrud_web.php');
$migration = file_get_contents($root . '/src/Migrations/2026_10_16_000000_create_fastcrud_notes_table.php');
$permissions = file_get_contents($root . '/src/Migrations/2026_10_16_000100_create_fastcrud_notes_permissions.php');
$editor = file_get_contents($root . '/src/Views/fastcrud_note/editor.blade.php');

$assert(str_contains($model, "['user_id', 'title', 'content']"), 'model menyimpan user_id, title, content');
$assert(str_contains($migration, "foreignId('user_id')"), 'migration memiliki foreign key user_id');
foreach (['index', 'create', 'update', 'delete'] as $action) {
    $assert(str_contains($permissions, "fastcrud_note-$action"), "permission $action tersedia");
}
$assert(str_contains($controller, "['required', 'image'"), 'upload divalidasi sebagai gambar');
$assert(str_contains($controller, "['user_id' => \$request->user()->id]"), 'user_id berasal dari user login');
$assert(str_contains($controller, 'sanitizeHtml'), 'konten HTML disanitasi server-side');
$assert(str_contains($routes, "name('fastcrud_note.autosave')"), 'route autosave tersedia');
$assert(str_contains($routes, "name('fastcrud_note.upload-image')"), 'route upload tersedia');
$assert(str_contains($editor, 'setTimeout(save, 2000)'), 'autosave inline memakai debounce');
$assert(str_contains($editor, "addHandler('image'"), 'Quill inline memakai upload gambar server');
$assert(!file_exists($root . '/src/public/assets/js/fastcrud-note.js'), 'tidak ada file JavaScript catatan terpisah');

exit($failures ? 1 : 0);
