<?php

use Illuminate\Support\Facades\Route;
use Satriotol\Fastcrud\Controllers\MenuController;
use Satriotol\Fastcrud\Controllers\MediaController;
use Satriotol\Fastcrud\Controllers\ConfigController;
use Satriotol\Fastcrud\Controllers\CrudController;
use Satriotol\Fastcrud\Controllers\PasswordController;
use App\Http\Controllers\MinioController;
use App\Http\Controllers\UserController;
use Satriotol\Fastcrud\Controllers\ApiKeyController;
use Satriotol\Fastcrud\Controllers\AppSpecsController;
use Satriotol\Fastcrud\Controllers\AuditController;
use Satriotol\Fastcrud\Controllers\FastcrudImpersonateController;
use Satriotol\Fastcrud\Controllers\FastcrudTwoFactorController;
use Satriotol\Fastcrud\Controllers\FastcrudUserController;
use Satriotol\Fastcrud\Controllers\PermissionController;
use Satriotol\Fastcrud\Controllers\RoleController;

Route::prefix('admin')->group(function () {
    Route::middleware(['web'])->group(function () {
        Route::get('getMedia/media/{uuid}', [MediaController::class, 'getMedia'])->name('media.getMedia');
        Route::get('getfile', [MinioController::class, 'getfile'])->name('minio.getfile');
        Route::get('getConfig/{uuid}', [ConfigController::class, 'getConfig'])->name('config.getConfig');
        Route::middleware(['auth'])->group(function () {
            Route::get('/change-password', [PasswordController::class, 'showChangePasswordForm'])->name('password.change.form');
            Route::post('/change-password', [PasswordController::class, 'changePassword'])->name('password.change');
        });
        Route::get('/login-back', [FastcrudImpersonateController::class, 'loginBack'])->name('fastcrud_user.impersonate.login_back');
        Route::middleware(['auth', 'force.password.change'])->group(function () {
            Route::prefix('fastcrud_user')->group(function () {
                Route::get('reset2Fa/{uuid}', [FastcrudUserController::class, 'reset2Fa'])->name('fastcrud_user.reset2Fa');
                Route::get('/login-as/{id}', [FastcrudImpersonateController::class, 'loginAs'])->name('fastcrud_user.impersonate.login_as');
            });
            Route::prefix('2fa')->group(function () {
                Route::get('/', [FastcrudTwoFactorController::class, 'show2FASetup'])->name('2fa.setup');
                Route::post('verify', [FastcrudTwoFactorController::class, 'verify2FA'])->name('2fa.verify');
            });
            Route::get('app-specs', [AppSpecsController::class, 'index'])->name('app-specs.index');
            Route::get('audit', [AuditController::class, 'index'])->name('audit.index');
            Route::get('profile', [UserController::class, 'profile'])->name('user.profile');
            Route::put('/updateProfile', [UserController::class, 'updateProfile'])->name('user.updateProfile');
            Route::resource('crud', CrudController::class);
            Route::resource('api_key', ApiKeyController::class);
            Route::resource('config', ConfigController::class);
            Route::post('/user/reset-password/{uuid}', [PasswordController::class, 'resetPassword'])->name('user.resetPassword');
            Route::post('/users/reset-passwords', [PasswordController::class, 'resetPasswords'])->name('users.resetPasswords');
            Route::put('password', [PasswordController::class, 'update'])->name('password.update');
            Route::resource('menu', MenuController::class);
            Route::resource('media', MediaController::class);
            Route::resource('permission', PermissionController::class);
            Route::resource('role', RoleController::class);
            Route::as('role.')->prefix('role')->group(function () {
                Route::get('only/view', [RoleController::class, 'view'])->name('view');
            });
            Route::resource('fastcrud_user', FastcrudUserController::class);
            Route::get('setMustChangePassword/fastcrud_user/{uuid}', [FastcrudUserController::class, 'setMustChangePassword'])->name('fastcrud_user.setMustChangePassword');
        });
    });
});
