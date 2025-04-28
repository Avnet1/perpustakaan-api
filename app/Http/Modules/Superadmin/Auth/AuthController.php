<?php

namespace App\Http\Modules\Superadmin\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperadminAuthRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(SuperadminAuthRequest $req): JsonResponse
    {
        $payload = (object) [
            "identity_code" => $req->identity_code,
            "password" => $req->password
        ];

        $result = $this->service->login($req, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }
}
