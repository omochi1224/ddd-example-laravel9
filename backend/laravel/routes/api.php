<?php

use Auth\Presentation\Controllers\AuthController;
use Auth\Presentation\Controllers\RegisterUserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', RegisterUserController::class)->name('register');
//Route::post('login', [AuthController::class, 'login'])->name('login');

Route::group(['prefix' => 'auth', 'middleware' => 'auth:api'], function(){
//    Route::get('me', [AuthController::class, 'me']);
});
