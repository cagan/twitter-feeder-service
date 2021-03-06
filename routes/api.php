<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\TokenController;
use App\Http\Controllers\TweetController;
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

// Auth
// Prefix: /api/auth/
Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'auth',
    ],
    function ($router) {
        Route::post('/login', [LoginController::class, 'login']);
        Route::post('/register', [RegisterController::class, 'register']);
        Route::post('/logout', [LogoutController::class, 'logout']);
        Route::post('/refresh', [RegisterController::class, 'refreshToken']);
        Route::post('/register/activate/{id}/{activation_token}', [RegisterController::class, 'verifyAccount']);
    }
);

Route::get('/tweets', [TweetController::class, 'index']);
Route::get('/tweets/{tweetId}', [TweetController::class, 'show']);
Route::put('/tweets/{tweet}', [TweetController::class, 'update']);
Route::put('/tweets/{tweet}/publish', [TweetController::class, 'publish']);
