<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostByUserController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SongByUserController;
use App\Http\Controllers\Api\SongController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\YoutubeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::post('songs', [SongController::class, 'store']);
    Route::delete('songs/{id}/{user_id}', [SongController::class, 'destroy']);
    Route::get('user/{user_id}/songs', [SongByUserController::class, 'index']);

    Route::get('youtube/{user_id}', [YoutubeController::class, 'show']);
    Route::post('youtube', [YoutubeController::class, 'store']);
    Route::delete('youtube/{id}', [YoutubeController::class, 'destroy']);

    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{id}', [PostController::class, 'show']);
    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{id}', [PostController::class, 'update']);
    Route::delete('posts/{id}', [PostController::class, 'destroy']);

    Route::get('user/{user_id}/posts', [PostByUserController::class, 'show']);
    Route::post('logout', [AuthController::class, 'logout']);
});
