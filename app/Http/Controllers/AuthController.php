<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\MasterAuthLoginRequest;
use App\Http\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(MasterAuthLoginRequest $req): JsonResponse
    {
        $payload = (object) [
            "identity_code" => $req->identity_code,
            "password" => $req->password
        ];

        $result = $this->service->login($req, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    public function refreshToken(Request $req): JsonResponse
    {
        $result = $this->service->refreshToken();
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }
}
