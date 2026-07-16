<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\AuthController;


Route::prefix('v1')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | API Authentication
    |--------------------------------------------------------------------------
    */

    Route::post('/auth/login', [
        AuthController::class,
        'login'
    ]);


    Route::middleware('auth:sanctum')->group(function () {


        Route::post('/auth/logout', [
            AuthController::class,
            'logout'
        ]);


        Route::get('/auth/me', [
            AuthController::class,
            'me'
        ]);


        /*
        |--------------------------------------------------------------------------
        | Agent Chat Management
        |--------------------------------------------------------------------------
        */

        Route::get('/chat/conversations', [
            ChatController::class,
            'conversations'
        ]);


        Route::get('/chat/conversation/{id}', [
            ChatController::class,
            'show'
        ]);


        Route::post('/chat/reply', [
            ChatController::class,
            'reply'
        ]);


        Route::post('/chat/close/{id}', [
            ChatController::class,
            'close'
        ]);

    });



    /*
    |--------------------------------------------------------------------------
    | Visitor Live Chat (No Authentication)
    |--------------------------------------------------------------------------
    */

    Route::post('/chat/start', [
        ChatController::class,
        'start'
    ]);


    Route::post('/chat/send', [
        ChatController::class,
        'send'
    ]);


    Route::get('/chat/history/{id}', [
        ChatController::class,
        'history'
    ]);

});