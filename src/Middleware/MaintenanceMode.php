<?php

namespace Satriotol\Fastcrud\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mode maintenance: seluruh akses ditutup, kecuali
 *  - SUPERADMIN, ATAU
 *  - sesi impersonasi yang dimulai SUPERADMIN (session 'impersonator_id'),
 *    supaya superadmin tetap bisa mengecek hasil perbaikan sebagai user biasa.
 *
 * Middleware ini opt-in, tidak dipasang otomatis di mana pun. Tandai sendiri
 * route group yang mau ikut terkunci:
 *
 *   Route::middleware(['auth', 'maintenance'])->group(function () { ... });
 *
 * Dengan begitu jalur masuk (login, reset password) tidak pernah bisa ikut
 * terkunci — tidak ada daftar pengecualian yang perlu dijaga.
 */
class MaintenanceMode
{
    /**
     * Flag sekaligus penyimpan pesan: ada file = maintenance aktif.
     *
     * ponytail: flag file lokal per server, pindah ke tabel configs
     * kalau nanti aplikasi di-load-balance ke lebih dari satu mesin.
     */
    public static function file(): string
    {
        return storage_path('framework/fastcrud-maintenance');
    }

    public static function active(): bool
    {
        return is_file(static::file());
    }

    public static function message(): string
    {
        $message = static::active() ? trim((string) file_get_contents(static::file())) : '';

        return $message !== '' ? $message : 'Sistem sedang dalam perbaikan. Silakan coba beberapa saat lagi.';
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! static::active()) {
            return $next($request);
        }

        if ($request->user()?->hasRole('SUPERADMIN')) {
            return $next($request);
        }

        // 'impersonator_id' hanya di-set ImpersonateController@start setelah
        // memastikan pemulainya SUPERADMIN. Impersonasi legacy ('admin_id')
        // sengaja tidak dipercaya karena terbuka juga untuk role IMPERSONATE.
        if (session('impersonator_id')) {
            return $next($request);
        }

        return response()->view('fastcrud::maintenance.down', [
            'message' => static::message(),
        ], 503);
    }
}
