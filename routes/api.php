<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AlertUserController;
use App\Http\Controllers\ApplicationCompanyController;
use App\Http\Controllers\ApplicationUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingTypeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatUserController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FlatController;
use App\Http\Controllers\FlatUploadController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MainBannerController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ObjectFlatController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\RecruitmentFlatController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RubricController;
use App\Http\Controllers\SetReadController;
use App\Http\Controllers\SiteInfoController;
use App\Http\Controllers\SitePageController;
use App\Http\Controllers\SiteSeoController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
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

        Route::group(['middleware' => 'jwt'], function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::get('/me', [AuthController::class, 'me']);
        });
    });

    Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
        Route::apiResource('/user', AdminUserController::class)->only(['update']);
    });

    Route::get('country', [CountryController::class, 'index']);
    Route::get('region', [RegionController::class, 'index']);
    Route::get('property', [PropertyController::class, 'index']);
    Route::get('building-type', [BuildingTypeController::class, 'index']);

    Route::group(['middleware' => 'throttle:30,1'], function () {
        Route::apiResource('ticket', TicketController::class)->only(['store']);
    });

    Route::apiResource('ticket', TicketController::class)->only(['index', 'show', 'update', 'destroy']);


    Route::get('main-banner', [MainBannerController::class, 'index']);
    Route::put('main-banner', [MainBannerController::class, 'update']);

    Route::get('object-flat', [ObjectFlatController::class, 'index']);

    Route::post('/flat-upload', [FlatUploadController::class, 'upload']);

    Route::post('file', [FileController::class, 'store'])->middleware('throttle:500,1');

    Route::apiResource('image', ImageController::class)->only(['index', 'store', 'show', 'destroy']);

    Route::apiResource('favorite', FavoriteController::class)->only(['index', 'store', 'show', 'destroy']);

    Route::apiResource('user', UserController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::put('user-password', [UserController::class, 'update_password']);

    Route::apiResource('company', CompanyController::class)->only(['index', 'show', 'update']);

    Route::apiResource('chat', ChatController::class)->only(['index', 'store', 'show']);
    Route::apiResource('chat-user', ChatUserController::class)->only(['index', 'show', 'update', 'destroy']);

    Route::apiResource('application-user', ApplicationUserController::class)->only(['index', 'store', 'show', 'destroy']);

    Route::apiResource('site-page', SitePageController::class)->only(['index', 'show', 'update']);

    Route::apiResources([
        'post' => PostController::class,
        'flat' => FlatController::class,
        'district' => DistrictController::class,
        'rubric' => RubricController::class,
        'collection' => CollectionController::class,
        'file' => FileController::class,
        'alert' => AlertController::class,
        'alert-user' => AlertUserController::class,
        'recruitment' => RecruitmentController::class,
        'message' => MessageController::class,
        'application-company' => ApplicationCompanyController::class,
        'site-seo' => SiteSeoController::class,
        'site-info' => SiteInfoController::class,
        'recruitment-flat' => RecruitmentFlatController::class,
    ]);

    Route::group([
        'prefix' => 'site-info/{id}'
    ], function () {
        Route::patch('/restore', [SiteInfoController::class, 'restore']);
        Route::delete('/force-delete', [SiteInfoController::class, 'forceDelete']);
    });

    Route::group([
        'prefix' => 'site-seo/{id}'
    ], function () {
        Route::patch('/restore', [SiteSeoController::class, 'restore']);
        Route::delete('/force-delete', [SiteSeoController::class, 'forceDelete']);
    });

    Route::patch('/message/{id}/read', [MessageController::class, 'read'])->middleware('jwt');
})->middleware('throttle:5000,1');

Route::get('test', [TestController::class, 'test']);
