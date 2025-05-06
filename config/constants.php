<?php

return [
    'route_name' => [
        'superadmin' => [
            'auth' => [
                'login' => 'authLoginSuperadmin',
                'forgot_password' => 'authForgotPasswordSuperadmin',
                'verified_otp' => 'authVerifiedOtpSuperadmin',
                'reset_password' => 'authResetPasswordSuperadmin',
                'change_password' => 'authChangePasswordSuperadmin',
                'update_profile' => 'authUpdateProfileSuperadmin',
                'upload_photo' => 'authUploadPhotoProfileSuperadmin'
            ],
            'role' => [
                'store' => 'storeRoleSuperadmin',
                'update' => 'updateRoleSuperadmin',
            ],
            'user' => [
                'store' => 'storeUserSuperadmin',
                'upload-photo' => 'uploadPhotoUserSuperadmin',
                'change-photo' => 'changePhotoUserSuperadmin',
                'update' => 'updateUserSuperadmin',
            ],
            'module' => [
                'store' => 'storeModuleSuperadmin',
                'uploadIcon' => 'uploadIconModuleSuperadmin',
                'changeIcon' => 'changeIconModuleSuperAdmin',
                'update' => 'updateModuleSuperadmin',
            ],
            'menu' => [
                'store' => 'storeMenuSuperadmin',
                'uploadIcon' => 'uploadIconMenuSuperadmin',
                'changeIcon' => 'changeIconMenuSuperAdmin',
                'update' => 'updateMenuSuperadmin',
            ],
            'province' => [
                'store' => 'storeProvinceSuperadmin',
                'update' => 'updateProvinceSuperadmin',
            ],
            'region' => [
                'store' => 'storeRegionSuperadmin',
                'update' => 'updateRegionSuperadmin',
            ],
            'sub_district' => [
                'store' => 'storeSubDistrictSuperadmin',
                'update' => 'updateSubDistrictSuperadmin',
            ],
            'village' => [
                'store' => 'storeVillageSuperadmin',
                'update' => 'updateVillageSuperadmin',
            ],
            'grade' => [
                'store' => 'storeGradeSuperadmin',
                'update' => 'updateGradeSuperadmin',
            ],
            'identity' => [
                'store' => 'storeIdentitySuperadmin',
                'update' => 'updateIdentitySuperadmin',
            ],
            'sosmed' => [
                'store' => 'storeSosmedSuperadmin',
                'update' => 'updateSosmedSuperadmin',
            ],
            'organization' => [
                'storeInfo' => 'storeInfoOrganizationSuperadmin',
                'storeAccount' => 'storeAccountOrganizationSuperadmin',
                'update' => 'updateOrganizationSuperadmin',
            ],

            'client' => [
                'storeInfo' => 'storeInfoClientSuperadmin',
                'storeAccount' => 'storeAccountClientSuperadmin',
                'update' => 'updateClientSuperadmin',
            ],
        ],
    ],


    'path_image' => [
        "module" => 'module/icon',
        "menu" => 'menu/icon',
        "user" => 'user/photo'
    ]
];
