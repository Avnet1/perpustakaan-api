<?php

namespace App\Http\Modules\Superadmin\Province;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProvinceRequest;

class ProvinsiController extends Controller
{
    public static $primaryKey = 'provinsi_id';

    const SORT_COLUMNS = [
        'nama_provinsi' => 'nama_provinsi',
        'kode_provinsi' => 'kode_provinsi',
        'kode_dikti' => 'kode_dikti'
    ];

    const DEFAULT_SORT = ['created_at', 'ASC'];


    protected $service;

    public function __construct(ProvinsiService $service)
    {
        $this->service = $service;
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];

        if ($request->has('nama_provinsi')) {
            $payload['nama_provinsi'] = $request->input('nama_provinsi');
        }

        if ($request->has('kode_provinsi')) {
            $payload['kode_provinsi'] = $request->input('kode_provinsi');
        }

        if ($request->has('kode_dikti')) {
            $payload['kode_dikti'] = $request->input('kode_dikti');
        }

        return $payload;
    }

    /** Fetch Client (Pagination) */
    public function fetch(Request $request): JsonResponse
    {
        $filters = (object) [
            "paging" => defineRequestPaginateArgs($request),
            "sorting" => defineRequestOrder($request, self::DEFAULT_SORT, self::SORT_COLUMNS)
        ];
        $result = $this->service->fetch($filters);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Get Client By Id */
    public function findById(Request $request): JsonResponse
    {
        $id = $request->route(self::$primaryKey);
        $result = $this->service->findById($id);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Create Client */
    public function store(ProvinceRequest $request): JsonResponse
    {
        $user = getUser($request);
        $today = Carbon::now();
        $payload = (object) array_merge($this->bodyValidation($request), [
            'created_at' => $today,
            'created_by' => $user->user_id,
        ]);
        $result = $this->service->store($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Update Client */
    public function update(Request $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route(self::$primaryKey);
        $today = Carbon::now();
        $payload = (object) array_merge($this->bodyValidation($request), [
            'updated_at' => $today,
            'updated_by' => $user->user_id,
        ]);

        $result = $this->service->update($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /* Soft Delete Client */
    public function delete(Request $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route(self::$primaryKey);
        $payload = (object) [
            'deleted_at' => Carbon::now(),
            'deleted_by' => $user->user_id,
        ];

        $result = $this->service->delete($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }
}
