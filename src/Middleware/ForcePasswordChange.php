<?php

namespace Satriotol\Fastcrud\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();



            // Jika user mencoba mengakses password.change.form tanpa must_change_password = 1
            if ($request->route()->getName() === 'password.change.form' && !$user->must_change_password) {
                return redirect('/')->withErrors('Anda tidak diizinkan mengakses halaman ini.');
            }

            // Jika must_change_password = 1, arahkan ke password.change.form jika mencoba mengakses halaman lain
            if ($user->must_change_password && $request->route()->getName() !== 'password.change.form') {
                return redirect()->route('password.change.form');
            }
            // Jika user adalah SUPERADMIN, lewati pemeriksaan perubahan password
            if ($user->hasRole('SUPERADMIN')) {
                return $next($request);
            }
            
            // Ambil nilai jumlah bulan dari konfigurasi ENV
            $expiryMonths = (int) env('PASSWORD_EXPIRY_MONTHS', 0);
            if ($expiryMonths <= 0) {
                return $next($request);
            }

            // Jika user belum pernah mengganti password, paksa untuk mengganti
            if (!$user->last_password_change) {
                return redirect()->route('password.change.form')
                    ->with('error', 'Anda harus mengganti password setiap ' . $expiryMonths . ' bulan.');
            }

            // Cek apakah password sudah kedaluwarsa
            $lastChange = Carbon::parse($user->last_password_change);
            if ($lastChange->addMonths($expiryMonths)->isPast()) {
                return redirect()->route('password.change.form')
                    ->with('warning', 'Anda harus mengganti password setiap ' . $expiryMonths . ' bulan.');
            }
        }

        return $next($request);
    }
}
