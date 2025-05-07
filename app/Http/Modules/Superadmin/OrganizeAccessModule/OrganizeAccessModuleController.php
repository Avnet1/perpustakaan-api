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
            $payload['kode_member'] = $request->input('kode_member');
        }

        if ($request->has('end_service')) {
            $payload['nama_organisasi'] = $request->input('nama_organisasi');
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
}
