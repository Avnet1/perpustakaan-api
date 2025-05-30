<?php

use App\Http\Middleware\ForceHttps;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\TokenValidateMiddleware;

/** Superadmin Controller */

use App\Http\Modules\Superadmin\Auth\AuthController as SA_AuthController;
use App\Http\Modules\Superadmin\Organization\OrganizationController as SA_OrganizationController;
use App\Http\Modules\Superadmin\Identity\IdentityController as SA_IdentityController;
use App\Http\Modules\Superadmin\SocialMedia\SocialMediaController as SA_SocialMediaController;
use App\Http\Modules\Superadmin\Role\RoleController as SA_RoleController;
use App\Http\Modules\Superadmin\User\UserController as SA_UserController;
use App\Http\Modules\Superadmin\Module\ModuleController as SA_ModuleController;
use App\Http\Modules\Superadmin\Menu\MenuController as SA_MenuController;
use App\Http\Modules\Superadmin\OrganizeAccessModule\OrganizeAccessModuleController as SA_OrganizeAccessModuleController;
use App\Http\Modules\Superadmin\Histories\HistoryController as SA_HistoryController;
use App\Http\Modules\Superadmin\Dashboard\DashboardController as SA_DashboardController;

use Illuminate\Support\Facades\Route;
// use App\Http\Middleware\HandleCors;
use Illuminate\Http\Middleware\HandleCors as DefaultHandleCors;

Route::prefix('v1')->group(function () {

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
                Route::put('/profile', [SA_AuthController::class, 'updateProfile'])->name(config('constants.route_name.superadmin.auth.update_profile'));
                Route::post('/profile/upload-photo', [SA_AuthController::class, 'uploadPhoto'])->name(config('constants.route_name.superadmin.auth.upload_photo'));
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

                Route::post('/upload-photo', [SA_UserController::class, 'uploadImage'])->name(config('constants.route_name.superadmin.user.upload-photo'));

                Route::post('/change-photo/{user_id}', [SA_UserController::class, 'changeImage'])->name(config('constants.route_name.superadmin.user.change-photo'));

                Route::get('/{user_id}', [SA_UserController::class, 'findById']);
                Route::put('/{user_id}', [SA_UserController::class, 'update'])->name(config('constants.route_name.superadmin.user.update'));
                Route::delete('/{user_id}', [SA_UserController::class, 'delete']);
            });

            /** Master Module  */
            Route::prefix('modules')->group(function () {
                Route::get('/', [SA_ModuleController::class, 'fetch']);
                Route::post('/', [SA_ModuleController::class, 'store'])->name(config('constants.route_name.superadmin.module.store'));

                Route::post('/upload-icon', [SA_ModuleController::class, 'uploadImage'])->name(config('constants.route_name.superadmin.module.uploadIcon'));

                Route::post('/change-icon/{modul_id}', [SA_ModuleController::class, 'changeImage'])->name(config('constants.route_name.superadmin.module.changeIcon'));

                Route::get('/{modul_id}', [SA_ModuleController::class, 'findById']);
                Route::put('/{modul_id}', [SA_ModuleController::class, 'update'])->name(config('constants.route_name.superadmin.module.update'));
                Route::delete('/{modul_id}', [SA_ModuleController::class, 'delete']);
            });


            /** Master Menu  */
            Route::prefix('menu')->group(function () {
                Route::post('/', [SA_MenuController::class, 'store'])->name(config('constants.route_name.superadmin.menu.store'));

                Route::post('/upload-icon', [SA_MenuController::class, 'uploadImage'])->name(config('constants.route_name.superadmin.menu.uploadIcon'));

                Route::post('/change-icon/{menu_id}', [SA_MenuController::class, 'changeImage'])->name(config('constants.route_name.superadmin.menu.changeIcon'));

                Route::put('/{menu_id}', [SA_MenuController::class, 'update'])->name(config('constants.route_name.superadmin.menu.update'));
                Route::get('/{menu_id}', [SA_MenuController::class, 'findById']);
                Route::delete('/{menu_id}', [SA_MenuController::class, 'delete']);
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

                Route::post('/upload-logo', [SA_OrganizationController::class, 'uploadImage'])->name(config('constants.route_name.superadmin.organization.upload-image'));

                Route::post('/info', [SA_OrganizationController::class, 'storeInfo'])->name(config('constants.route_name.superadmin.organization.store-info'));

                Route::post('/change-logo/{organisasi_id}', [SA_OrganizationController::class, 'changeImage'])->name(config('constants.route_name.superadmin.organization.change-image'));


                Route::put('/{organisasi_id}', [SA_OrganizationController::class, 'update'])->name(config('constants.route_name.superadmin.organization.update'));

                Route::put('/account/{organisasi_id}', [SA_OrganizationController::class, 'storeAccount'])->name(config('constants.route_name.superadmin.organization.store-account'));

                Route::get('/{organisasi_id}', [SA_OrganizationController::class, 'findById']);

                Route::get('/approved/{organisasi_id}', [SA_OrganizationController::class, 'approved']);

                Route::delete('/{organisasi_id}', [SA_OrganizationController::class, 'delete']);
            });


            /** Organization Access To Modules */
            Route::prefix('organization-modules')->group(function () {
                Route::post('/assign-to-modules/{organisasi_id}', [SA_OrganizeAccessModuleController::class, 'assignToModules'])->name(config('constants.route_name.superadmin.organization_modules.assign_module'));

                Route::get('/{modul_access_id}', [SA_OrganizeAccessModuleController::class, 'findAccessModule']);



                Route::put('/{modul_access_id}', [SA_OrganizeAccessModuleController::class, 'updateAccessModule'])->name(config('constants.route_name.superadmin.organization_modules.update'));

                Route::put('/change-status/{modul_access_id}', [SA_OrganizeAccessModuleController::class, 'changeStatus'])->name(config('constants.route_name.superadmin.organization_modules.change_status'));
                Route::delete('/delete-module/{modul_access_id}', [SA_OrganizeAccessModuleController::class, 'deleteAccessModule']);
            });

            Route::prefix('histories')->group(function () {
                Route::get('/subscription', [SA_HistoryController::class, 'fetchRiwayatLangganan']);
            });


            Route::prefix('dashboard')->group(function () {
                Route::get('/tab-info', [SA_DashboardController::class, 'fetchTabInfo']);
                Route::get('/list-organization', [SA_DashboardController::class, 'fetchListOrganization']);
            });
        });
    });





    Route::prefix('website')->group(function () {});
});
