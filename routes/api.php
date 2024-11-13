<?php

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
    Route::post('/users/register', [AuthController::class, 'registerUser']);
    Route::post('/users/login', [AuthController::class, 'loginUser']);
    Route::post('/user/logout', [AuthController::class, 'logout']);
    Route::get('/user/profile', [AuthController::class, 'profile']);

    #Admin
    Route::post('/admin/login', [AuthController::class, 'loginAdmin']);
    Route::post('/admin/logout', [AuthController::class, 'logout']);
    Route::post('/admin/profile', [AuthController::class, 'profile']);
});
