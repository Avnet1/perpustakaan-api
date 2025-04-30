<?php

use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\TokenValidateMiddleware;

/** Superadmin Controller */

use App\Http\Modules\Superadmin\Auth\AuthController as SA_AuthController;
use App\Http\Modules\Superadmin\Client\ClientController as SA_ClientController;
use App\Http\Modules\Superadmin\Organization\OrganizationController as SA_OrganizationController;
use App\Http\Modules\Superadmin\Province\ProvinsiController as SA_ProvinceController;
use App\Http\Modules\Superadmin\Region\RegionController as SA_RegionController;
use App\Http\Modules\Superadmin\Subdistrict\SubdistrictController as SA_SubdistrictController;
use App\Http\Modules\Superadmin\Village\VillageController as SA_VillageController;
use App\Http\Modules\Superadmin\Grade\GradeController as SA_GradeController;
use App\Http\Modules\Superadmin\Identity\IdentityController as SA_IdentityController;
use App\Http\Modules\Superadmin\SocialMedia\SocialMediaController as SA_SocialMediaController;

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

            /** Master Kecamatan */
            Route::prefix('sub-districts')->group(function () {
                Route::get('/', [SA_SubdistrictController::class, 'fetch']);
                Route::post('/', [SA_SubdistrictController::class, 'store']);
                Route::get('/{kecamatan_id}', [SA_SubdistrictController::class, 'findById']);
                Route::put('/{kecamatan_id}', [SA_SubdistrictController::class, 'update']);
                Route::delete('/{kecamatan_id}', [SA_SubdistrictController::class, 'delete']);
            });

            /** Master Kelurahan */
            Route::prefix('villages')->group(function () {
                Route::get('/', [SA_VillageController::class, 'fetch']);
                Route::post('/', [SA_VillageController::class, 'store']);
                Route::get('/{kelurahan_id}', [SA_VillageController::class, 'findById']);
                Route::put('/{kelurahan_id}', [SA_VillageController::class, 'update']);
                Route::delete('/{kelurahan_id}', [SA_VillageController::class, 'delete']);
            });


            /** Master Jenjang */
            Route::prefix('grades')->group(function () {
                Route::get('/', [SA_GradeController::class, 'fetch']);
                Route::post('/', [SA_GradeController::class, 'store']);
                Route::get('/{jenjang_id}', [SA_GradeController::class, 'findById']);
                Route::put('/{jenjang_id}', [SA_GradeController::class, 'update']);
                Route::delete('/{jenjang_id}', [SA_GradeController::class, 'delete']);
            });

            /** Master Identitas */
            Route::prefix('identity')->group(function () {
                Route::get('/', [SA_IdentityController::class, 'fetch']);
                Route::post('/', [SA_IdentityController::class, 'store']);
                Route::get('/{identitas_id}', [SA_IdentityController::class, 'findById']);
                Route::post('/{identitas_id}', [SA_IdentityController::class, 'update']);
                Route::delete('/{identitas_id}', [SA_IdentityController::class, 'delete']);
            });


            /** Master Social Media */
            Route::prefix('social-media')->group(function () {
                Route::get('/', [SA_SocialMediaController::class, 'fetch']);
                Route::post('/', [SA_SocialMediaController::class, 'store'])->name('storeSosmed_superadmin');
                Route::get('/{social_media_id}', [SA_SocialMediaController::class, 'findById']);
                Route::post('/{social_media_id}', [SA_SocialMediaController::class, 'update'])->name('updateSosmed_superadmin');
                Route::delete('/{social_media_id}', [SA_SocialMediaController::class, 'delete']);
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
