<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/register", [UserController::class, 'register']);

Route::group([
    'middleware' => 'api',
], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me'])->name("auth-me");
    });

    Route::group(['prefix' => 'kosts'], function () {
        Route::group(['middleware' => 'role:owner'], function () {
            Route::get('/me', [KostController::class, 'me']);
            Route::post('/', [KostController::class, 'store']);
            Route::put('/{id}', [KostController::class, 'update']);
            Route::delete('/{id}', [KostController::class, 'destroy']);
        });

        Route::get('/', [KostController::class, 'index']);
    });
});
