<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\CategoryControlller;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SoundController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\Front\CategoryController as FrontCategory;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\Front\NovelController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\UserController;
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
Route::group(['prefix' => 'user'] , function ($router) {
    Route::post('update_role', [UserController::class, 'updateUser'])->middleware('jwt.verify');
    Route::post('forget_password', [UserController::class, 'forgetUser']);
    Route::post('check_otp', [UserController::class, 'checkOTP']);
    Route::post('reset_password', [UserController::class, 'resetPassword']);
});

Route::get('profile', [AuthController::class, 'profile'])->middleware('jwt.verify');

Route::group(['prefix' => 'author',], function ($router) {
    Route::group(['middleware' => ['jwt.verify', 'auth.admin']], function ($router) {
        Route::post('create', [AuthorController::class, 'createAuthor']);
        Route::delete('delete/{id}', [AuthorController::class, 'deleteAuthor']);
        Route::post('update', [AuthorController::class, 'updateAuthor']);
    });
    Route::get('detail/{id}', [AuthorController::class, 'detailAuthor']);
    Route::get('list', [AuthorController::class, 'listAuthors']);
});

Route::group(['prefix' => 'image'], function ($router) {
    Route::post('upload', [ImageController::class, 'upload'])->middleware('jwt.verify');
    Route::delete('delete/{id}', [ImageController::class, 'deleteImage'])->middleware(['jwt.verify', 'auth.admin']);
});

/* --------- API Category ----------- */
Route::group(['prefix' => 'category',], function ($router) {
    Route::group(['middleware' => ['jwt.verify', 'auth.admin']], function ($router) {
        Route::post('create', [CategoryControlller::class, 'createCategory']);
        Route::delete('delete/{id}', [CategoryControlller::class, 'deleteCategory']);
        Route::post('update', [CategoryControlller::class, 'updateCategory']);
    });
    Route::get('detail/{id}', [CategoryControlller::class, 'detailCategory']);
    Route::get('list', [CategoryControlller::class, 'listCategories']);
    Route::get('data', [CategoryControlller::class, 'dataCategories']);
});

/* --------- API Story ----------- */
Route::group(['prefix' => 'story',], function ($router) {
    Route::group(['middleware' => ['jwt.verify', 'auth.admin']], function ($router) {
        Route::post('create', [StoryController::class, 'createStory']);
        Route::delete('delete/{id}', [StoryController::class, 'deleteStory']);
        Route::post('update', [StoryController::class, 'updateStory']);
    });
    Route::get('list', [StoryController::class, 'listStories']);
    Route::get('detail/{id}', [StoryController::class, 'detailStory']);
    Route::get('completed-stories', [HomeController::class, 'completed_stories']);
    Route::get('data', [StoryController::class, 'dataStories']);
    Route::get('latest', [HomeController::class, 'latestStories']);
    Route::get('{slugStory}/chapters', [NovelController::class, 'chaptersOfTheStory']);
    Route::get('{slugStory}', [NovelController::class, 'detailStory']);
});


Route::group(['prefix' => 'chapter',], function ($router) {
    Route::group(['middleware' => ['jwt.verify', 'auth.admin']], function ($router) {
        Route::post('create', [ChapterController::class, 'createChapter']);
        Route::post('update', [ChapterController::class, 'updateChapter']);
        Route::delete('delete/{id}', [ChapterController::class, 'deleteChapter']);
    });
    Route::get('list', [ChapterController::class, 'listChapters']);
    Route::get('detail/{id}', [ChapterController::class, 'detailChapter']);
    Route::get('detail/{slugStory}/{slugChapter}', [NovelController::class, 'detailChapter']);
});

/* ---------API Interaction----------- */
Route::group(['prefix' => 'interaction',], function ($router) {
    Route::group(['middleware' => ['jwt.verify']], function ($router) {
        Route::post('add', [InteractionController::class, 'addInteraction']);
        Route::post('delete', [InteractionController::class, 'deleteInteraction']);
    });
    Route::post('check', [InteractionController::class, 'checkInteraction']);
});

/* ---------API Comment----------- */
Route::group(['prefix' => 'comment', 'namespace' => 'App\Http\Controllers'], function ($router) {
    Route::post('add', 'CommentController@addComment')->middleware('jwt.verify');
    Route::post('list', 'CommentController@listComments');
});

/* ---------API Ads----------- */
Route::group(['prefix' => 'ads', 'namespace' => 'App\Http\Controllers'], function ($router) {
    Route::group(['middleware' => ['jwt.verify', 'auth.admin']], function ($router) {
        Route::post('create', 'AdvertisementController@createAds');
        Route::delete('delete/{id}', [AdvertisementController::class, 'deleteAds']);
        Route::post('update', [AdvertisementController::class, 'updateAds']);
        Route::post('list', 'AdvertisementController@listAds');
    });
    Route::get('detail/{id}', [AdvertisementController::class, 'detailAds']);
    Route::get('random', 'AdvertisementController@getAdsRandom');
});

Route::group(['prefix' => 'sound', 'namespace' => 'App\Http\Controllers'], function ($router) {
    Route::post('upload', 'SoundController@upload');
    Route::post('delete', [SoundController::class, 'delete']);
});

// Api Category front-end
Route::get('categories', [FrontCategory::class, 'categories']);
Route::get('category/{slugCategory}', [FrontCategory::class, 'storiesOfCategory']);
Route::get('search', [HomeController::class, 'search']);
Route::get('/hot-stories/{slugCategory?}', [HomeController::class, 'hotStories']);

Route::group(['prefix' => 'payment'], function ($router) {
    Route::post('create', [PaymentController::class, 'createPayment']) ->middleware('jwt.verify');
});
