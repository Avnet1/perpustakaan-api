<?php

use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\TokenValidateMiddleware;
use App\Http\Modules\Superadmin\Auth\AuthController as SuperadminAuthController;
use App\Http\Modules\Superadmin\Client\ClientController as SuperadminClientController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    Route::prefix('superadmin')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/login', [SuperadminAuthController::class, 'login']);
        });

        Route::middleware([
            TokenValidateMiddleware::class,
            SuperAdminMiddleware::class,
        ])->group(function () {

            Route::prefix('clients')->group(function () {
                Route::get('/', [SuperadminClientController::class, 'fetch']);
                Route::post('/', [SuperadminClientController::class, 'store']);
                Route::get('/{client_id}', [SuperadminClientController::class, 'findById']);
                Route::put('/{client_id}', [SuperadminClientController::class, 'update']);
                Route::put('/{client_id}', [SuperadminClientController::class, 'delete']);
            });
        });
    });


    Route::prefix('organization')->group(function () {
        Route::prefix('auth')->group(function () {
            // Route::post('/login', [AuthController::class, 'login']);

            // Route::middleware([ValidateAuthTokenMiddleware::class, GlobalAuthMiddleware::class])->group(function(){
            //     Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
            // });
        });
    });


    Route::prefix('website')->group(function () {});
});
