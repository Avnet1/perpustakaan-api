<?php

namespace App\Http\Modules\Superadmin\Module;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleRequest;
use App\Models\MasterModule;

class ModuleController extends Controller
{
    private $primaryKey;
    private $pathLocation;

    protected $service;

    const SORT_COLUMNS = [
        'nama_modul' => 'nama_modul',
        'slug' => 'slug',
        'urutan' => 'urutan',
    ];

    const DEFAULT_SORT = ['created_at', 'ASC'];

    public function __construct(ModuleService $service)
    {
        $this->service = $service;
        $this->primaryKey = MasterModule::getPrimaryKeyName();
        $this->pathLocation = config('constants.path_image.module');
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];
        if ($request->has('nama_modul')) {
            $payload['nama_modul'] = $request->input('nama_modul');
        }


        if ($request->has('slug')) {
            $payload['slug'] = $request->input('slug');
        }

        if ($request->has('urutan')) {
            $payload['urutan'] = $request->input('urutan');
        }

        if ($request->has('image_id')) {
            $payload['image_id'] = $request->input('image_id');
        }

        return $payload;
    }

    /** Fetch Module (Pagination) */
    public function fetch(Request $request): JsonResponse
    {
        $filters = (object) [
            "paging" => defineRequestPaginateArgs($request),
            "sorting" => defineRequestOrder($request, self::DEFAULT_SORT, self::SORT_COLUMNS)
        ];
        $result = $this->service->fetch($filters);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Get Module By Id */
    public function findById(Request $request): JsonResponse
    {
        $id = $request->route("{$this->primaryKey}");
        $result = $this->service->findById($id);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Create Menu */
    public function uploadIcon(ModuleRequest $request): JsonResponse
    {
        $user = getUser($request);
        $payload = (object) array_merge($this->bodyValidation($request), [
            'icon' => null,
            'created_by' => $user->user_id,
        ]);

        if ($request->hasFile('icon')) {
            $payload->icon = $request->file('icon')->store("{$this->pathLocation}", 'public');
        }

        $result = $this->service->uploadIcon($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Create Module */
    public function store(ModuleRequest $request): JsonResponse
    {
        $user = getUser($request);
        $payload = (object) array_merge($this->bodyValidation($request), [
            'created_by' => $user->user_id,
        ]);
        $result = $this->service->store($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    public function changeIcon(ModuleRequest $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route("{$this->primaryKey}");
        $payload = (object) [
            'icon' => null,
            'updated_at' => Carbon::now(),
            'updated_by' => $user->user_id
        ];

        if ($request->hasFile('icon')) {
            $payload->icon = $request->file('icon')->store("{$this->pathLocation}", 'public');
        }

        $result = $this->service->changeIcon($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Update Module */
    public function update(Request $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route("{$this->primaryKey}");
        $payload = (object) array_merge($this->bodyValidation($request), [
            'updated_at' => Carbon::now(),
            'updated_by' => $user->user_id,
        ]);

        $result = $this->service->update($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /* Soft Delete Module */
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
