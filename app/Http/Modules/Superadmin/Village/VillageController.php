<?php

namespace App\Http\Modules\Superadmin\Village;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\VillageRequest;
use App\Models\MasterKelurahan;

class VillageController extends Controller
{
    private $primaryKey;

    const SORT_COLUMNS = [
        'nama_kelurahan' => 'nama_kelurahan',
        'kode_kelurahan' => 'kode_kelurahan',
        'kode_dikti' => 'kode_dikti',
        'nama_kecamatan' => 'kecamatan.nama_kecamatan',
        'nama_provinsi' => 'provinsi.nama_provinsi',
        'nama_kabupaten_kota' => 'kabupatenKota.nama_kabupaten_kota'
    ];

    const DEFAULT_SORT = ['created_at', 'ASC'];


    protected $service;

    public function __construct(VillageService $service)
    {
        $this->service = $service;
        $this->primaryKey = MasterKelurahan::getPrimaryKeyName();
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];

        if ($request->has('kecamatan_id')) {
            $payload['kecamatan_id'] = $request->input('kecamatan_id');
        }

        if ($request->has('provinsi_id')) {
            $payload['provinsi_id'] = $request->input('provinsi_id');
        }

        if ($request->has('kabupaten_kota_id')) {
            $payload['kabupaten_kota_id'] = $request->input('kabupaten_kota_id');
        }

        if ($request->has('nama_kelurahan')) {
            $payload['nama_kelurahan'] = $request->input('nama_kelurahan');
        }

        if ($request->has('kode_kelurahan')) {
            $payload['kode_kelurahan'] = $request->input('kode_kelurahan');
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
        $id = $request->route("{$this->primaryKey}");
        $result = $this->service->findById($id);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Create Client */
    public function store(VillageRequest $request): JsonResponse
    {
        $user = getUser($request);
        $today = Carbon::now();
        $payload = (object) array_merge($this->bodyValidation($request), [
            'created_by' => $user->user_id
        ]);
        $result = $this->service->store($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Update Client */
    public function update(Request $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route("{$this->primaryKey}");
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
        $id = $request->route("{$this->primaryKey}");
        $payload = (object) [
            'deleted_at' => Carbon::now(),
            'deleted_by' => $user->user_id,
        ];

        $result = $this->service->delete($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }
}
