<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationFlatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingTypeController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FlatController;
use App\Http\Controllers\FlatUploadController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MainBannerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RubricController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
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

Route::group(['middleware' => 'api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:300,1');
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    Route::get('country', [CountryController::class, 'index']);
    Route::get('region', [RegionController::class, 'index']);
    Route::get('property', [PropertyController::class, 'index']);
    Route::get('building-type', [BuildingTypeController::class, 'index']);

    Route::group(['middleware' => 'throttle:30,1'], function () {
        Route::post('application', [ApplicationController::class, 'store']);
        Route::post('application-flat', [ApplicationFlatController::class, 'store']);
    });

    Route::get('main-banner', [MainBannerController::class, 'index']);
    Route::put('main-banner', [MainBannerController::class, 'update']);

    Route::post('/flat/upload', [FlatUploadController::class, 'upload']);

    Route::post('file', [FileController::class, 'store'])->middleware('throttle:500,1');

    Route::apiResources([
        'post' => PostController::class,
        'flat' => FlatController::class,
        'district' => DistrictController::class,
        'rubric' => RubricController::class,
        'collection' => CollectionController::class,
        'file' => FileController::class,
        'favorite' => FavoriteController::class,
    ]);
});

Route::get('test', [TestController::class, 'test']);
