<?php

namespace App\Http\Modules\Superadmin\Subdistrict;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubdistrictRequest;
use App\Models\MasterKecamatan;

class SubdistrictController extends Controller
{
    private $primaryKey = 'kecamatan_id';

    const SORT_COLUMNS = [
        'nama_kecamatan' => 'nama_kecamatan',
        'kode_kecamatan' => 'kode_kecamatan',
        'kode_dikti' => 'kode_dikti',
        'nama_kabupaten_kota' => 'kabupatenKota.nama_kabupaten_kota',
        'nama_provinsi' => 'provinsi.nama_provinsi'
    ];

    const DEFAULT_SORT = ['created_at', 'ASC'];


    protected $service;

    public function __construct(SubdistrictService $service)
    {
        $this->service = $service;
        $this->primaryKey = MasterKecamatan::getPrimaryKeyName();
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];

        if ($request->has('kabupaten_kota_id')) {
            $payload['kabupaten_kota_id'] = $request->input('kabupaten_kota_id');
        }

        if ($request->has('provinsi_id')) {
            $payload['provinsi_id'] = $request->input('provinsi_id');
        }

        if ($request->has('nama_kecamatan')) {
            $payload['nama_kecamatan'] = $request->input('nama_kecamatan');
        }

        if ($request->has('kode_kecamatan')) {
            $payload['kode_kecamatan'] = $request->input('kode_kecamatan');
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
    public function store(SubdistrictRequest $request): JsonResponse
    {
        $user = getUser($request);
        $payload = (object) array_merge($this->bodyValidation($request), [
            'created_by' => $user->user_id,
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
        $today = Carbon::now();
        $payload = (object) [
            'deleted_at' => $today,
            'deleted_by' => $user->user_id,
        ];

        $result = $this->service->delete($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }
}
