<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

<<<<<<< HEAD
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

Route::prefix('/users')->group(function () {
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::middleware('auth:api')->group( function () {
        Route::post('/refresh_token', [\App\Http\Controllers\AuthController::class, 'refresh_token']);
        Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    });
});
=======
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
>>>>>>> 50cebde153fda58415e1672cd49ab950560e7d25
