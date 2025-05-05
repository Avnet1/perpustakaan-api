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
use App\Http\Modules\Superadmin\Role\RoleController as SA_RoleController;
use App\Http\Modules\Superadmin\User\UserController as SA_UserController;

use Illuminate\Support\Facades\Route;
// use App\Http\Middleware\HandleCors;
use Illuminate\Http\Middleware\HandleCors as DefaultHandleCors;

Route::prefix('v1')->middleware([DefaultHandleCors::class])->group(function () {

    Route::prefix('superadmin')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/login', [SA_AuthController::class, 'login'])->name(config('constants.route_name.superadmin.auth.login'));
            Route::post('/forgot-password', [SA_AuthController::class, 'forgotPassword'])->name(config('constants.route_name.superadmin.auth.forgot_password'));
            Route::post('/otp-verification', [SA_AuthController::class, 'verificationOtp'])->name(config('constants.route_name.superadmin.auth.verified_otp'));
            Route::post('/reset-password', [SA_AuthController::class, 'resetPassword'])->name(config('constants.route_name.superadmin.auth.reset_password'));
        });

        Route::middleware([
            TokenValidateMiddleware::class,
            SuperAdminMiddleware::class,
        ])->group(function () {

            Route::prefix('auth')->group(function () {
                Route::get('/profile', [SA_AuthController::class, 'fetchProfile']);
                Route::post('/profile', [SA_AuthController::class, 'updateProfile'])->name(config('constants.route_name.superadmin.auth.update_profile'));
                Route::post('/manual-change-password',  [SA_AuthController::class, 'manualChangePassword'])->name(config('constants.route_name.superadmin.auth.change_password'));
            });

            /** Master Roles */
            Route::prefix('roles')->group(function () {
                Route::get('/', [SA_RoleController::class, 'fetch']);
                Route::post('/', [SA_RoleController::class, 'store'])->name(config('constants.route_name.superadmin.role.store'));
                Route::get('/{role_id}', [SA_RoleController::class, 'findById']);
                Route::post('/{role_id}', [SA_RoleController::class, 'update'])->name(config('constants.route_name.superadmin.role.update'));
                Route::delete('/{role_id}', [SA_RoleController::class, 'delete']);
            });

            /** Master User  */
            Route::prefix('users')->group(function () {
                Route::get('/', [SA_UserController::class, 'fetch']);
                Route::post('/', [SA_UserController::class, 'store'])->name(config('constants.route_name.superadmin.user.store'));
                Route::get('/{user_id}', [SA_UserController::class, 'findById']);
                Route::post('/{user_id}', [SA_UserController::class, 'update'])->name(config('constants.route_name.superadmin.user.update'));
                Route::delete('/{user_id}', [SA_UserController::class, 'delete']);
            });

            /** Master Provinsi */
            Route::prefix('provinces')->group(function () {
                Route::get('/', [SA_ProvinceController::class, 'fetch']);
                Route::post('/', [SA_ProvinceController::class, 'store'])->name(config('constants.route_name.superadmin.province.store'));
                Route::get('/{provinsi_id}', [SA_ProvinceController::class, 'findById']);
                Route::put('/{provinsi_id}', [SA_ProvinceController::class, 'update'])->name(config('constants.route_name.superadmin.province.update'));
                Route::delete('/{provinsi_id}', [SA_ProvinceController::class, 'delete']);
            });

            /** Master Kabupaten Kota */
            Route::prefix('regions')->group(function () {
                Route::get('/', [SA_RegionController::class, 'fetch']);
                Route::post('/', [SA_RegionController::class, 'store'])->name(config('constants.route_name.superadmin.region.store'));
                Route::get('/{kabupaten_kota_id}', [SA_RegionController::class, 'findById']);
                Route::put('/{kabupaten_kota_id}', [SA_RegionController::class, 'update'])->name(config('constants.route_name.superadmin.region.update'));
                Route::delete('/{kabupaten_kota_id}', [SA_RegionController::class, 'delete']);
            });

            /** Master Kecamatan */
            Route::prefix('sub-districts')->group(function () {
                Route::get('/', [SA_SubdistrictController::class, 'fetch']);
                Route::post('/', [SA_SubdistrictController::class, 'store'])->name(config('constants.route_name.superadmin.sub_district.store'));
                Route::get('/{kecamatan_id}', [SA_SubdistrictController::class, 'findById']);
                Route::put('/{kecamatan_id}', [SA_SubdistrictController::class, 'update'])->name(config('constants.route_name.superadmin.sub_district.update'));
                Route::delete('/{kecamatan_id}', [SA_SubdistrictController::class, 'delete']);
            });

            /** Master Kelurahan */
            Route::prefix('villages')->group(function () {
                Route::get('/', [SA_VillageController::class, 'fetch']);
                Route::post('/', [SA_VillageController::class, 'store'])->name(config('constants.route_name.superadmin.village.store'));
                Route::get('/{kelurahan_id}', [SA_VillageController::class, 'findById']);
                Route::put('/{kelurahan_id}', [SA_VillageController::class, 'update'])->name(config('constants.route_name.superadmin.village.update'));
                Route::delete('/{kelurahan_id}', [SA_VillageController::class, 'delete']);
            });


            /** Master Jenjang */
            Route::prefix('grades')->group(function () {
                Route::get('/', [SA_GradeController::class, 'fetch']);
                Route::post('/', [SA_GradeController::class, 'store'])->name(config('constants.route_name.superadmin.grade.store'));
                Route::get('/{jenjang_id}', [SA_GradeController::class, 'findById']);
                Route::put('/{jenjang_id}', [SA_GradeController::class, 'update'])->name(config('constants.route_name.superadmin.grade.update'));
                Route::delete('/{jenjang_id}', [SA_GradeController::class, 'delete']);
            });

            /** Master Identitas */
            Route::prefix('identity')->group(function () {
                Route::get('/', [SA_IdentityController::class, 'fetch']);
                Route::post('/', [SA_IdentityController::class, 'store'])->name(config('constants.route_name.superadmin.identity.store'));
                Route::get('/{identitas_id}', [SA_IdentityController::class, 'findById']);
                Route::post('/{identitas_id}', [SA_IdentityController::class, 'update'])->name(config('constants.route_name.superadmin.identity.update'));
                Route::delete('/{identitas_id}', [SA_IdentityController::class, 'delete']);
            });


            /** Master Social Media */
            Route::prefix('social-media')->group(function () {
                Route::get('/', [SA_SocialMediaController::class, 'fetch']);
                Route::post('/', [SA_SocialMediaController::class, 'store'])->name(config('constants.route_name.superadmin.sosmed.store'));
                Route::get('/{social_media_id}', [SA_SocialMediaController::class, 'findById']);
                Route::post('/{social_media_id}', [SA_SocialMediaController::class, 'update'])->name(config('constants.route_name.superadmin.sosmed.update'));
                Route::delete('/{social_media_id}', [SA_SocialMediaController::class, 'delete']);
            });


            /** Master Organisasi */
            Route::prefix('organizations')->group(function () {
                Route::get('/', [SA_OrganizationController::class, 'fetch']);
                Route::put('/{organisasi_id}', [SA_OrganizationController::class, 'update'])->name(config('constants.route_name.superadmin.organization.update'));
                Route::post('/info', [SA_OrganizationController::class, 'storeInfo'])->name(config('constants.route_name.superadmin.organization.storeInfo'));
                Route::put('/account/{organisasi_id}', [SA_OrganizationController::class, 'storeAccount'])->name(config('constants.route_name.superadmin.organization.storeAccount'));
                Route::get('/{organisasi_id}', [SA_OrganizationController::class, 'findById']);
                Route::delete('/{organisasi_id}', [SA_OrganizationController::class, 'delete']);
            });

            /** Master Client */
            Route::prefix('clients')->group(function () {
                Route::get('/', [SA_ClientController::class, 'fetch']);
                Route::post('/info', [SA_ClientController::class, 'storeInfo'])->name(config('constants.route_name.superadmin.client.storeInfo'));
                Route::put('/account/{client_id}', [SA_ClientController::class, 'storeAccount'])->name(config('constants.route_name.superadmin.client.storeAccount'));
                Route::get('/{client_id}', [SA_ClientController::class, 'findById']);
                Route::put('/{client_id}', [SA_ClientController::class, 'update'])->name(config('constants.route_name.superadmin.client.update'));
                Route::delete('/{client_id}', [SA_ClientController::class, 'delete']);
            });
        });
    });





    Route::prefix('website')->group(function () {});
});
