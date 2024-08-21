<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiKeyController;

Route::resource('api_key', ApiKeyController::class);
