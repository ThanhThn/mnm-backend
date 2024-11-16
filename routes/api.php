<?php

use App\Http\Controllers\CategoryControlller;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AuthorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoryController;

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

Route::group(['prefix' => 'author',], function ($router) {
    Route::group(['middleware' => ['jwt.verify', 'auth.admin']], function ($router) {
        Route::post('create', [AuthorController::class, 'createAuthor']);
        Route::post('delete/{id}', [AuthorController::class, 'deleteAuthor']);
        Route::post('update', [AuthorController::class, 'updateAuthor']);
    });
    Route::get('list', [AuthorController::class, 'listAuthors']);
});

Route::group(['prefix' => 'image'], function ($router) {
    Route::post('upload', [ImageController::class, 'upload'])->middleware('jwt.verify');
});

Route::group(['prefix' => 'category',], function ($router) {
    Route::group(['middleware' => ['jwt.verify', 'auth.admin']], function ($router) {
        Route::post('create', [CategoryControlller::class, 'createCategory']);
        Route::delete('delete/{id}', [CategoryControlller::class, 'deleteCategory']);
        Route::post('update', [CategoryControlller::class, 'updateCategory']);
    });
    Route::get('list', [CategoryControlller::class, 'listCategories']);
});

Route::group(['prefix' => 'story',], function ($router) {
    Route::group(['middleware' => ['jwt.verify', 'auth.admin']], function ($router) {
        Route::post('create', [StoryController::class, 'createStory']);
        Route::delete('delete/{id}', [StoryController::class, 'deleteStory']);
        Route::post('update', [StoryController::class, 'updateStory']);
    });
    Route::get('list', [StoryController::class, 'listStories']);
});
