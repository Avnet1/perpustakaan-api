<?php

namespace App\Http\Modules\Superadmin\OrganizeAccessModule;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
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

    public function bodyValidation(Request $request): array
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

        if ($request->has('provinsi')) {
            $payload['provinsi'] = $request->input('provinsi');
        }

        if ($request->has('kabupaten_kota')) {
            $payload['kabupaten_kota'] = $request->input('kabupaten_kota');
        }


        if ($request->has('kecamatan')) {
            $payload['kecamatan'] = $request->input('kecamatan');
        }

        if ($request->has('kelurahan_desa')) {
            $payload['kelurahan_desa'] = $request->input('kelurahan_desa');
        }

        if ($request->has('kode_pos')) {
            $payload['kode_pos'] = $request->input('kode_pos');
        }

        if ($request->has('email')) {
            $payload['email'] = $request->input('email');
        }

        if ($request->has('alamat')) {
            $payload['alamat'] = $request->input('alamat');
        }

        if ($request->has('domain_admin_url')) {
            $payload['domain_admin_url'] = $request->input('domain_admin_url');
        }

        if ($request->has('domain_website_url')) {
            $payload['domain_website_url'] = $request->input('domain_website_url');
        }

        if ($request->has('password')) {
            $payload['password'] = $request->input('password');
        }

        if ($request->has('status')) {
            $payload['status'] = $request->input('status');
        }
        return $payload;
    }

    /** Create Informasi Organisasi */
    public function assignToModules(Request $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route("{$this->primaryKey}");
        $payload = (object) array_merge($this->bodyValidation($request), [
            'created_at' => Carbon::now(),
            'created_by' => $user->user_id,
        ]);
        $result = $this->service->assignToModules($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }
}
