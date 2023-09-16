<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/ping', function () {
    return 'pong';
});

Route::post('admin/login', [\App\Http\Controllers\Api\Admin\AuthController::class, 'login'])->name('login');
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::controller(\App\Http\Controllers\Api\Admin\AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::get('me', 'me');
        Route::post('change-password', 'changePassword');
    });

    Route::prefix('users')->controller(\App\Http\Controllers\Api\Admin\UserController::class)->group(function () {
        Route::get('list', 'list');
        Route::post('create', 'create');
    });

});
