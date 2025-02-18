<?php

namespace Satriotol\Fastcrud\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\ResponseFormatter;

class EnsurePermissionJson
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!$request->user() || !$request->user()->can($permission)) {
            return ResponseFormatter::error("Anda tidak memiliki izin untuk melakukan aksi ini", 'Anda tidak memiliki izin untuk melakukan aksi ini', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
