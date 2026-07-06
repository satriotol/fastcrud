<?php

namespace Satriotol\Fastcrud\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mengizinkan akses jika:
 *  - user yang login benar-benar SUPERADMIN, ATAU
 *  - sedang dalam mode impersonasi yang dimulai oleh seorang SUPERADMIN
 *    (session 'impersonator_id' hanya di-set oleh ImpersonateController@start
 *     setelah memastikan pemulai adalah SUPERADMIN).
 *
 * Dengan ini superadmin tetap bisa mengatur permission role
 * selagi "melihat sebагai" user lain, tanpa harus keluar dari impersonasi.
 */
class EnsureSuperadminContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $isSuperadmin = $user && $user->hasRole('SUPERADMIN');

        $impersonatorId = session('impersonator_id');
        $impersonatorIsSuperadmin = $impersonatorId
            && optional(User::find($impersonatorId))->hasRole('SUPERADMIN');

        abort_unless($isSuperadmin || $impersonatorIsSuperadmin, 403, 'Akses ditolak.');

        return $next($request);
    }
}
