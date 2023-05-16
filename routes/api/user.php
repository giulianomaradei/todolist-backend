<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

Route::group(['namespace' => 'User', 'prefix' => 'user'], function () {

    Route::get('/'                     , [UserController::class,'get']);
    Route::post('/profile'             , [UserController::class,'updateProfile']);
    Route::post('/password'            , [UserController::class,'updatePassword']);

    Route::group(['middleware' => 'ability:usuario-busca'], function () {
        Route::put('/{id}'             , [UserController::class,'update']);
        Route::get('/all'              , [UserController::class,'list']);
        Route::get('/all/{skip}'       , [UserController::class,'list']);
        Route::get('/all/{skip}/{take}', [UserController::class,'list']);
        Route::delete('/{id}'          , [UserController::class,'delete']);
        Route::get('/{id}'             , [UserController::class,'getById']);
        Route::patch('/{id}'           , [UserController::class,'resetPassword']);
    });
});
