<?php

use App\Http\Controllers\Api\ResponseFormatter;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'force.password.change' => \Satriotol\Fastcrud\Middleware\ForcePasswordChange::class,
            'api_key' => \Satriotol\Fastcrud\Middleware\ApiKeyMiddleware::class,
            'permission.json' => \Satriotol\Fastcrud\Middleware\EnsurePermissionJson::class,
            '2fa' => \Satriotol\Fastcrud\Middleware\Google2FAMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return ResponseFormatter::error($e->getMessage(), $e->getMessage(), 401);
            }
        });
    })->create();
