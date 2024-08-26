<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\MinioController;

Route::resource('api_key', ApiKeyController::class);
Route::get('getfile', [MinioController::class, 'getfile'])->name('minio.getfile');
