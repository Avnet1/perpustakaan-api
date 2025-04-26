<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\GlobalAuthMiddleware;
use App\Http\Middleware\ValidateAuthTokenMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware([ValidateAuthTokenMiddleware::class, GlobalAuthMiddleware::class])->group(function(){
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
});


