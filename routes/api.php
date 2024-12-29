<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CastsController;
use App\Http\Controllers\Api\GenresController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewsController;
use App\Http\Controllers\Api\CastMovieController;

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
Route::prefix('v1')->group(function(){
    Route::apiResource('cast', CastsController::class);
    Route::apiResource('genre', GenresController::class);
    Route::apiResource('movie', MovieController::class);
    Route::apiResource('role', RoleController::class)->middleware('auth:api','checkrole');
    Route::apiResource('cast-movie', CastMovieController::class);
    Route::prefix('auth')->group(function(){
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/me', [AuthController::class, 'getUser'])->middleware('auth:api');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::post('/verifikasi-akun', [AuthController::class, 'verifikasi'])->middleware('auth:api');
        Route::post('/generate-otp', [AuthController::class, 'generateOtp'])->middleware('auth:api');
    })->middleware('api');

    Route::post('/profile', [ProfileController::class, 'storeupdate'])->middleware('auth:api','checkverified');
    Route::post('/review', [ReviewsController::class, 'storeupdate'])->middleware('auth:api','checkverified');
});