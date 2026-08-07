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
 * Jalur masuk (login, logout, reset password) dan health check tetap dibuka,
 * kalau tidak superadmin tidak punya pintu masuk setelah mode ini dinyalakan.
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

    /**
     * Path yang tetap terbuka saat maintenance, supaya superadmin punya jalan masuk.
     *
     * Dicocokkan lewat path, BUKAN nama route: `POST /login` di routes/auth.php
     * tidak punya nama route sama sekali (yang bernama `login` hanya GET-nya di
     * routes/web.php), jadi routeIs('login') meloloskan formnya tapi memblokir
     * submit-nya — dan tidak ada yang bisa masuk selama maintenance.
     *
     * ponytail: daftar path di-hardcode. Jadikan config('fastcrud.maintenance_except')
     * kalau nanti ada instalasi yang mengubah path login bawaan.
     */
    public static function isAllowedPath(Request $request): bool
    {
        return $request->is(
            'login',
            'logout',
            'forgot-password',
            'reset-password',
            'reset-password/*',
            'up',
        );
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! static::active()) {
            return $next($request);
        }

        // Paling murah dan paling penting: jangan sampai pintu masuk ikut terkunci.
        if (static::isAllowedPath($request)) {
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
