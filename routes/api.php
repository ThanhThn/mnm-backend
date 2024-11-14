<?php

use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    #User
    Route::group(['prefix' => 'user'], function ($router) {
        Route::post('register', [AuthController::class, 'registerUser']);
        Route::post('login', [AuthController::class, 'loginUser']);
    });

    #Admin
    Route::group(['prefix' => 'admin'], function ($router) {
        Route::post('/login', [AuthController::class, 'loginAdmin']);
    });
});
Route::get('profile', [AuthController::class, 'profile'])->middleware('jwt.verify');

Route::group(['prefix' => 'image'], function ($router) {
    Route::post('upload', [ImageController::class, 'upload']);
});
