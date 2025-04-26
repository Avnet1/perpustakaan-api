<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\TipeGMDRequest;
use App\Http\Services\TipeGMDService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TipeGMDController extends Controller
{

    protected $service;

    public function __construct(TipeGMDService $service)
    {
        $this->service = $service;
    }

    public function store(TipeGMDRequest $req): JsonResponse {
        $payload = (object) [
            "kode" => $req->kode,
            "nama" => $req->nama,
            "created_by" => $req->user->user_id
        ];

        $result = $this->service->store($req, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }
}
