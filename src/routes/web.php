<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CaptchaServiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
// URL_CRUD_GENERATOR
use App\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/reload-captcha', [CaptchaServiceController::class, 'reloadCaptcha']);
Route::post('upload/store', [UploadController::class, 'store'])->name('upload.store');
Route::delete('revert/image', [UploadController::class, 'revert'])->name('upload.revert');
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);
    Route::resource('user', UserController::class);
    Route::resource('audit', AuditController::class);
    // CRUD_GENERATOR
    Route::resource('crud', CrudController::class);
    Route::get('editProfile', [UserController::class, 'editProfile'])->name('user.editProfile');
    Route::get('user/resetPassword/{user}', [UserController::class, 'reset_password'])->name('user.resetPassword');
});
require __DIR__ . '/auth.php';
