<?php

namespace Satriotol\Fastcrud\Middleware;

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
        }

        return $next($request);
    }
}
