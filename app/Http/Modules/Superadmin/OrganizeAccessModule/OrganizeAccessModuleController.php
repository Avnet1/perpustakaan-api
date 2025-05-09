<?php

namespace App\Http\Modules\Superadmin\OrganizeAccessModule;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizeAccessModuleRequest;
use App\Models\OrganizationModuleAccess;

class OrganizeAccessModuleController extends Controller
{

    private $primaryKey;
    protected $service;


    public function __construct(OrganizeAccessModuleService $service)
    {
        $this->service = $service;
        $this->primaryKey = OrganizationModuleAccess::getPrimaryKeyName();
    }

    public function bodyValidation(OrganizeAccessModuleRequest $request): array
    {
        $payload = [];

        if ($request->has('list_modules')) {
            $payload['list_modules'] = $request->input('list_modules');
        }


        if ($request->has('start_service')) {
            $payload['start_service'] = $request->input('start_service');
        }

        if ($request->has('end_service')) {
            $payload['end_service'] = $request->input('end_service');
        }

        if ($request->has('is_active')) {
            $payload['is_active'] = $request->input('is_active');
        }
        return $payload;
    }


    /** Create Informasi Organisasi */
    public function assignToModules(OrganizeAccessModuleRequest $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route("organisasi_id");
        $payload = array_merge($this->bodyValidation($request), [
            'created_at' => Carbon::now(),
            'created_by' => $user->user_id,
        ]);
        $result = $this->service->assignToModules($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }


    public function deleteAccessModule(Request $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route("{$this->primaryKey}");
        $payload = (object) [
            'deleted_at' => Carbon::now(),
            'deleted_by' => $user->user_id,
        ];

        $result = $this->service->deleteAccessModule($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }


    public function changeStatus(OrganizeAccessModuleRequest $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route("{$this->primaryKey}");
        $payload = array_merge($this->bodyValidation($request), [
            'updated_at' => Carbon::now(),
            'updated_by' => $user->user_id,
        ]);

        $result = $this->service->changeStatus($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }


    public function updateAccessModule(OrganizeAccessModuleRequest $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route("{$this->primaryKey}");
        $payload = (object) array_merge($this->bodyValidation($request), [
            'updated_at' => Carbon::now(),
            'updated_by' => $user->user_id,
        ]);

        $result = $this->service->updateAccessModule($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }


    public function findAccessModule(OrganizeAccessModuleRequest $request): JsonResponse
    {
        $id = $request->route("{$this->primaryKey}");
        $result = $this->service->findAccessModule($id);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }
}
