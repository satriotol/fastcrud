<?php

namespace Satriotol\Fastcrud\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Google2FAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Dapatkan daftar role yang dikecualikan dari .env (dipisahkan dengan koma)
        $excludedRoles = explode(',', env('GOOGLE2FA_EXCEPT_ROLES', ''));

        // Cek jika user login dan memiliki salah satu role yang dikecualikan
        $user = Auth::user();
        if ($user && $user->hasAnyRole($excludedRoles)) {
            return $next($request); // Lewati pengecekan 2FA
        }

        if (!session()->has('2fa_verified') && env('APP_DEBUG') == false) {
            return redirect()->route('2fa.setup');
        }

        return $next($request);
    }
}
