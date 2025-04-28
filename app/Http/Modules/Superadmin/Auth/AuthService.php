<?php

namespace App\Http\Modules\Superadmin\Auth;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{

    protected $repository;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function login(Request $req, $payload): LaravelResponseInterface
    {
        $user = $this->repository->findIdentity($payload->identity_code);

        if(!$user) {
            return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ["attribute" => "ID Anggota"]), $user);
        }

        if (!Hash::check($payload->password, $user->password)) {
            return new LaravelResponseContract(false, 404, __('validation.custom.error.auth.passwordInvalid'), $payload);
        }

        $credentials = [
            'identity_code' => $user->identity_code, // This should be the custom field you want to use
            'password' => $payload->password,        // The password provided by the user
        ];

        // Attempt to generate the JWT token using the custom credentials
        if (!$token = JWTAuth::attempt($credentials)) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.auth.tokenGenerate'), $token);
        }

        $jwtPayload = [
            'user_id' => $user->user_id,
            'identity_code' => $user->identity_code,
            'role_id' => $user->role->role_id,
            'role_name' => $user->role->role_name,
            'role_slug' => $user->role->role_slug,
        ];

        $token = JWTAuth::fromUser($user, $jwtPayload);

        if (!$token) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.auth.tokenGenerate'), $token);
        }


        return new LaravelResponseContract(true, 200, __('validation.custom.success.auth.login'), (object) [
            "access_token" => $token
        ]);
    }

    public function refreshToken():LaravelResponseInterface
    {
        // Get the token from the request (usually in the Authorization header as a Bearer token)
        $token = JWTAuth::getToken();

        // If no token is provided, return an error
        if (!$token) {
            return new LaravelResponseContract(false, 404, __('validation.custom.error.auth.noProvideToken'), $token);

        }

        try {
            // Refresh the token
            $newToken = JWTAuth::refresh($token);
            return new LaravelResponseContract(true, 200, __('validation.custom.success.auth.refreshToken'), [
                "access_token" => $newToken
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return new LaravelResponseContract(false, 500, $e->getMessage(), $e);
        }
    }
}
