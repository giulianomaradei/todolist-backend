<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\IframeController;
use App\Http\Controllers\User\UserController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot', [PasswordResetController::class, 'forgot']);
Route::post('/reset', [PasswordResetController::class, 'reset']);
Route::post('/user'     ,[UserController::class,'create']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
});


