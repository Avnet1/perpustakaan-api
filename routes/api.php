<?php

use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\TokenValidateMiddleware;

/** Superadmin Controller */

use App\Http\Modules\Superadmin\Auth\AuthController as SA_AuthController;
use App\Http\Modules\Superadmin\Client\ClientController as SA_ClientController;
use App\Http\Modules\Superadmin\Organization\OrganizationController as SA_OrganizationController;
use App\Http\Modules\Superadmin\Province\ProvinsiController as SA_ProvinceController;
use App\Http\Modules\Superadmin\Region\RegionController as SA_RegionController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    Route::prefix('superadmin')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/login', [SA_AuthController::class, 'login']);
        });

        Route::middleware([
            TokenValidateMiddleware::class,
            SuperAdminMiddleware::class,
        ])->group(function () {

            /** Master Provinsi */
            Route::prefix('provinces')->group(function () {
                Route::get('/', [SA_ProvinceController::class, 'fetch']);
                Route::post('/', [SA_ProvinceController::class, 'store']);
                Route::get('/{provinsi_id}', [SA_ProvinceController::class, 'findById']);
                Route::put('/{provinsi_id}', [SA_ProvinceController::class, 'update']);
                Route::delete('/{provinsi_id}', [SA_ProvinceController::class, 'delete']);
            });

            /** Master Kabupaten Kota */
            Route::prefix('regions')->group(function () {
                Route::get('/', [SA_RegionController::class, 'fetch']);
                Route::post('/', [SA_RegionController::class, 'store']);
                Route::get('/{kabupaten_kota_id}', [SA_RegionController::class, 'findById']);
                Route::put('/{kabupaten_kota_id}', [SA_RegionController::class, 'update']);
                Route::delete('/{kabupaten_kota_id}', [SA_RegionController::class, 'delete']);
            });

            /** Master Organisasi */
            Route::prefix('organizations')->group(function () {
                Route::get('/', [SA_OrganizationController::class, 'fetch']);
                Route::put('/{organisasi_id}', [SA_OrganizationController::class, 'update']); // Update Organisasi
                Route::post('/info', [SA_OrganizationController::class, 'storeInfo']);
                Route::put('/account/{organisasi_id}', [SA_OrganizationController::class, 'storeAccount']);
                Route::get('/{organisasi_id}', [SA_OrganizationController::class, 'findById']);
                Route::delete('/{organisasi_id}', [SA_OrganizationController::class, 'delete']);
            });

            /** Master Client */
            Route::prefix('clients')->group(function () {
                Route::get('/', [SA_ClientController::class, 'fetch']);
                Route::post('/info', [SA_ClientController::class, 'storeInfo']);
                Route::put('/account/{client_id}', [SA_ClientController::class, 'storeAccount']);
                Route::get('/{client_id}', [SA_ClientController::class, 'findById']);
                Route::put('/{client_id}', [SA_ClientController::class, 'update']);
                Route::delete('/{client_id}', [SA_ClientController::class, 'delete']);
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
