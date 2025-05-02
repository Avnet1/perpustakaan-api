<?php

namespace App\Http\Modules\Superadmin\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperadminAuthRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuthController extends Controller
{

    protected $service;
    public static $pathLocation = 'user/photo';

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(SuperadminAuthRequest $request): JsonResponse
    {
        $payload = (object) [
            "email" => $request->email,
            "password" => $request->password
        ];

        $result = $this->service->login($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    public function forgotPassword(SuperadminAuthRequest $request): JsonResponse
    {
        $objOtp = getOtpRandomize();
        $payload = [
            "email" => $request->email,
            "otp_code" => $objOtp->otp_code,
            "otp_time" => $objOtp->otp_time,
            'created_at' => Carbon::now()
        ];

        $result = $this->service->forgotPassword($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }


    public function verificationOtp(SuperadminAuthRequest $request): JsonResponse
    {
        $payload = [
            'email' => $request->email,
            "otp_code" => $request->otp_code,
        ];

        $result = $this->service->verificationOtp($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    public function resetPassword(SuperadminAuthRequest $request): JsonResponse
    {
        $payload = [
            "permission_code" =>  $request->permission_code,
            "new_password" => $request->new_password,
            "confirm_password" => $request->confirm_password
        ];

        $result = $this->service->resetPassword($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    public function manualChangePassword(SuperadminAuthRequest $request): JsonResponse
    {

        $user = getUser($request);
        $payload = [
            "old_password" =>  $request->old_password,
            "new_password" => $request->new_password,
            "confirm_password" => $request->confirm_password
        ];

        $result = $this->service->manualChangePassword($user->user_id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }



    public function fetchProfile(Request $request): JsonResponse
    {
        $user = getUser($request);
        $result = $this->service->fetchProfile($user->user_id);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    public function updateProfile(SuperadminAuthRequest $request): JsonResponse
    {
        $user = getUser($request);

        $payload = (object) [
            "name" =>  $request->name,
            "email" => $request->email,
            "updated_at" => now(),
            "updated_by" => $user->user_id,
            "photo" => null
        ];

        if ($request->hasFile('photo')) {
            $payload->photo = $request->file('photo')->store(self::$pathLocation, 'public');
        }

        $result = $this->service->updateProfile($user->user_id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }
}
