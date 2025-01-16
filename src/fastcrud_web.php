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
        Route::middleware(['auth', 'force.password.change'])->group(function () {
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
            Route::resource('fastcrud_user', FastcrudUserController::class);
        });
    });
});
