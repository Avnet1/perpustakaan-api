<?php

namespace App\Http\Modules\Superadmin\Region;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegionRequest;

class RegionController extends Controller
{
    public static $primaryKey = 'kabupaten_kota_id';

    const SORT_COLUMNS = [
        'nama_kabupaten_kota' => 'nama_kabupaten_kota',
        'status_administrasi' => 'status_administrasi',
        'kode_kabupaten_kota' => 'kode_kabupaten_kota',
        'kode_dikti' => 'kode_dikti',
        'nama_provinsi' => 'master_provinsi.nama_provinsi'
    ];

    const DEFAULT_SORT = ['created_at', 'ASC'];


    protected $service;

    public function __construct(RegionService $service)
    {
        $this->service = $service;
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];

        if ($request->has('provinsi_id')) {
            $payload['provinsi_id'] = $request->input('provinsi_id');
        }

        if ($request->has('nama_kabupaten_kota')) {
            $payload['nama_kabupaten_kota'] = $request->input('nama_kabupaten_kota');
        }

        if ($request->has('status_administrasi')) {
            $payload['status_administrasi'] = $request->input('status_administrasi');
        }

        if ($request->has('kode_kabupaten_kota')) {
            $payload['kode_kabupaten_kota'] = $request->input('kode_kabupaten_kota');
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
    public function store(RegionRequest $request): JsonResponse
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
