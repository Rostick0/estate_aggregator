<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationFlatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ImageController;
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
    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    Route::get('district', [DistrictController::class, 'index']);
    Route::get('country', [CountryController::class, 'index']);
    Route::get('region', [RegionController::class, 'index']);
    Route::get('rubric', [RubricController::class, 'index']);
    Route::get('property', [PropertyController::class, 'index']);
    
    Route::post('application', [ApplicationController::class, 'store']);
    Route::post('application-flat', [ApplicationFlatController::class, 'store']);

    Route::apiResources([
        'post' => PostController::class
    ]);

    Route::put('/image/{id}', [ImageController::class, 'update']);
});

// Route::get('test', [TestController::class, 'test']);
