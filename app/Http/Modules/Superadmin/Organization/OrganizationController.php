<?php

namespace App\Http\Modules\Superadmin\Organization;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationRequest;
use App\Models\MasterOrganisasi;

class OrganizationController extends Controller
{

    private $primaryKey;
    private $pathLocation;


    protected $service;

    const SORT_COLUMNS = [
        'kode_member' => 'kode_member',
        'nama_organisasi' => 'nama_organisasi',
        'provinsi' => 'provinsi',
        'kabupaten_kota' => 'kabupaten_kota',
        'alamat' => 'alamat',
        'total_layanan' => 'total_layanan'
    ];

    const DEFAULT_SORT = ['created_at', 'ASC'];

    public function __construct(OrganizationService $service)
    {
        $this->service = $service;

        $this->primaryKey = MasterOrganisasi::getPrimaryKeyName();
        $this->pathLocation = config('constants.path_image.organization');
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];

        if ($request->has('image_id')) {
            $payload['image_id'] = $request->input('image_id');
        }

        if ($request->has('status')) {
            $payload['status'] = $request->input('status');
        }

        if ($request->has('list_modules')) {
            $payload['list_modules'] = $request->input('list_modules');
        }


        if ($request->has('kode_member')) {
            $payload['kode_member'] = $request->input('kode_member');
        }

        if ($request->has('nama_organisasi')) {
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

    public function uploadImage(OrganizationRequest $request): JsonResponse
    {
        $user = getUser($request);
        $payload = (object) array_merge($this->bodyValidation($request), [
            'logo' => null,
            'created_by' => $user->user_id,
        ]);

        if ($request->hasFile('logo')) {
            $payload->logo = $request->file('logo')->store("{$this->pathLocation}", 'public');
        }

        $result = $this->service->uploadImage($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Create Informasi Organisasi */
    public function storeInfo(OrganizationRequest $request): JsonResponse
    {
        $user = getUser($request);
        $payload = (object) array_merge($this->bodyValidation($request), [
            'created_at' => Carbon::now(),
            'created_by' => $user->user_id,
        ]);
        $result = $this->service->storeInfo($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }


    public function changeImage(OrganizationRequest $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route("{$this->primaryKey}");
        $payload = (object) [
            'logo' => null,
            'updated_at' => Carbon::now(),
            'updated_by' => $user->user_id
        ];

        if ($request->hasFile('logo')) {
            $payload->logo = $request->file('logo')->store("{$this->pathLocation}", 'public');
        }

        $result = $this->service->changeImage($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Create Akun Organisasi */
    public function storeAccount(OrganizationRequest $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route("{$this->primaryKey}");
        $payload = (object) array_merge($this->bodyValidation($request), [
            'updated_at' => Carbon::now(),
            'updated_by' => $user->user_id,
        ]);
        $result = $this->service->storeAccount($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Update Client */
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
