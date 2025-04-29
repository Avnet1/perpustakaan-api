<?php

namespace App\Http\Modules\Superadmin\Organization;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationRequest;

class OrganizationController extends Controller
{
    public static $primaryKey = 'organisasi_id';
    public static $pathLocation = 'organization/logo';


    protected $service;

    public function __construct(OrganizationService $service)
    {
        $this->service = $service;
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];

        if ($request->has('universitas_id')) {
            $payload['universitas_id'] = $request->input('universitas_id');
        }

        if ($request->has('provinsi_id')) {
            $payload['provinsi_id'] = $request->input('provinsi_id');
        }

        if ($request->has('kabupaten_kota_id')) {
            $payload['kabupaten_kota_id'] = $request->input('kabupaten_kota_id');
        }

        if ($request->has('kecamatan_id')) {
            $payload['kecamatan_id'] = $request->input('kecamatan_id');
        }


        if ($request->has('kelurahan_id')) {
            $payload['kelurahan_id'] = $request->input('kelurahan_id');
        }

        if ($request->has('postal_code')) {
            $payload['postal_code'] = $request->input('postal_code');
        }

        if ($request->has('email')) {
            $payload['email'] = $request->input('email');
        }

        if ($request->has('address')) {
            $payload['address'] = $request->input('address');
        }

        if ($request->has('domain_admin_url')) {
            $payload['domain_admin_url'] = $request->input('domain_admin_url');
        }

        if ($request->has('domain_website_url')) {
            $payload['domain_website_url'] = $request->input('domain_website_url');
        }

        if ($request->has('end_active_at')) {
            $payload['end_active_at'] = $request->input('end_active_at');
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
        $id = $request->route(self::$primaryKey);
        $result = $this->service->findById($id);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Create Informasi Organisasi */
    public function storeInfo(OrganizationRequest $request): JsonResponse
    {
        $user = getUser($request);
        $today = Carbon::now();
        $payload = (object) array_merge($this->bodyValidation($request), [
            'logo' => null,
            'created_at' => $today,
            'created_by' => $user->user_id,
        ]);

        if ($request->hasFile('logo')) {
            $payload->logo = $request->file('logo')->storeInfo(self::$pathLocation, 'public');
        }
        $result = $this->service->store($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Create Akun Organisasi */
    public function storeAccount(OrganizationRequest $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route(self::$primaryKey);
        $today = Carbon::now();
        $payload = (object) array_merge($this->bodyValidation($request), [
            'updated_at' => $today,
            'updated_by' => $user->user_id,
        ]);
        $result = $this->service->storeAccount($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Update Client */
    public function update(Request $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route(self::$primaryKey);
        $today = Carbon::now();
        $payload = (object) array_merge($this->bodyValidation($request), [
            'logo' => null,
            'updated_at' => $today,
            'updated_by' => $user->user_id,
        ]);

        if ($request->hasFile('logo')) {
            $payload->client_photo = $request->file('logo')->store(self::$pathLocation, 'public');
        }

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
