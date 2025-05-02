<?php

namespace App\Http\Modules\Superadmin\Grade;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\GradeRequest;
use App\Models\MasterJenjang;

class GradeController extends Controller
{
    private $primaryKey;

    const SORT_COLUMNS = [
        'nama_jenjang' => 'nama_jenjang',
        'urutan' => 'urutan',
    ];

    const DEFAULT_SORT = ['created_at', 'ASC'];


    protected $service;

    public function __construct(GradeService $service)
    {
        $this->service = $service;
        $this->primaryKey = MasterJenjang::getPrimaryKeyName();
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];

        if ($request->has('nama_jenjang')) {
            $payload['nama_jenjang'] = $request->input('nama_jenjang');
        }

        if ($request->has('urutan')) {
            $payload['urutan'] = $request->input('urutan');
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
    public function store(GradeRequest $request): JsonResponse
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
        $payload = (object) [
            'deleted_at' => Carbon::now(),
            'deleted_by' => $user->user_id,
        ];

        $result = $this->service->delete($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }
}
