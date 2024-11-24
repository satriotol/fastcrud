<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiKeyController;
use Satriotol\Fastcrud\Controllers\ConfigController;
use Satriotol\Fastcrud\Controllers\CrudController;
use App\Http\Controllers\MinioController;

Route::prefix('admin')->group(function () {
    Route::middleware(['auth', 'force.password.change'])->group(function () {
        Route::resource('crud', CrudController::class);
        Route::resource('api_key', ApiKeyController::class);
        Route::resource('config', ConfigController::class);
    });
});

Route::get('getfile', [MinioController::class, 'getfile'])->name('minio.getfile');
Route::get('getConfig/{uuid}', [ConfigController::class, 'getConfig'])->name('config.getConfig');
