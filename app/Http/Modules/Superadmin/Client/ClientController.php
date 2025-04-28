<?php

namespace App\Http\Modules\Superadmin\Client;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    protected $service;

    public function __construct(ClientService $service)
    {
        $this->service = $service;
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];

        if ($request->has('client_code')) {
            $payload['client_code'] = $request->input('client_code');
        }

        if ($request->has('client_name')) {
            $payload['client_name'] = $request->input('client_name');
        }

        if ($request->has('client_job')) {
            $payload['client_job'] = $request->input('client_job');
        }

        if ($request->has('client_phone')) {
            $payload['client_phone'] = $request->input('client_phone');
        }


        if ($request->has('client_address')) {
            $payload['client_address'] = $request->input('client_address');
        }

        return $payload;
    }

    /** Fetch Client (Pagination) */
    public function fetch(Request $request): JsonResponse
    {
        $filters = (object) [
            "paging" => defineRequestPaginateArgs($request),
            "sorting" => defineRequestOrder($request)
        ];
        $result = $this->service->fetch($filters);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Get Client By Id */
    public function findById(Request $request): JsonResponse
    {
        $id = $request->route('client_id');
        $result = $this->service->findById($id);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Create Client */
    public function store(ClientRequest $request): JsonResponse
    {
        $user = getUser($request);
        $today = Carbon::now();
        $payload = (object) array_merge($this->bodyValidation($request), [
            'client_photo' => null,
            'created_at' => $today,
            'created_by' => $user->user_id,
        ]);

        if ($request->hasFile('photo')) {
            $payload->client_photo = $request->file('photo')->store('clients/photo', 'public');
        }
        $result = $this->service->store($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Update Client */
    public function update(Request $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route('client_id');
        $today = Carbon::now();
        $payload = (object) array_merge($this->bodyValidation($request), [
            'client_photo' => null,
            'updated_at' => $today,
            'updated_by' => $user->user_id,
        ]);

        if ($request->hasFile('photo')) {
            $payload->client_photo = $request->file('photo')->store('clients/photo', 'public');
        }

        $result = $this->service->update($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /* Soft Delete Client */
    public function delete(Request $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route('client_id');
        $payload = (object) [
            'deleted_at' => Carbon::now(),
            'deleted_by' => $user->user_id,
        ];

        $result = $this->service->delete($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }
}
