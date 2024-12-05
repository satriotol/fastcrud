<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiKeyController;
use Satriotol\Fastcrud\Controllers\ConfigController;
use Satriotol\Fastcrud\Controllers\CrudController;
use Satriotol\Fastcrud\Controllers\PasswordController;
use App\Http\Controllers\MinioController;
use App\Http\Controllers\UserController;

Route::prefix('admin')->group(function () {
    Route::middleware(['auth', 'force.password.change'])->group(function () {
        Route::put('/updateProfile', [UserController::class, 'updateProfile'])->name('user.updateProfile');
        Route::resource('crud', CrudController::class);
        Route::resource('api_key', ApiKeyController::class);
        Route::resource('config', ConfigController::class);
        Route::get('/change-password', [PasswordController::class, 'showChangePasswordForm'])->name('password.change.form');
        Route::post('/change-password', [PasswordController::class, 'changePassword'])->name('password.change');
        Route::post('/user/reset-password/{uuid}', [PasswordController::class, 'resetPassword'])->name('user.resetPassword');
        Route::post('/users/reset-passwords', [PasswordController::class, 'resetPasswords'])->name('users.resetPasswords');
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    });
});

Route::get('getfile', [MinioController::class, 'getfile'])->name('minio.getfile');
Route::get('getConfig/{uuid}', [ConfigController::class, 'getConfig'])->name('config.getConfig');
