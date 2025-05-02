<?php

namespace App\Http\Modules\Superadmin\Identity;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\IdentityRequest;
use App\Models\MasterIdentitas;

class IdentityController extends Controller
{
    private $primaryKey;
    public static $pathLocation = 'identity/photo';
    protected $service;

    public function __construct(IdentityService $service)
    {
        $this->service = $service;
        $this->primaryKey = MasterIdentitas::getPrimaryKeyName();
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];

        if ($request->has('nama_perusahaan')) {
            $payload['nama_perusahaan'] = $request->input('nama_perusahaan');
        }

        if ($request->has('kota')) {
            $payload['kota'] = $request->input('kota');
        }

        if ($request->has('email')) {
            $payload['email'] = $request->input('email');
        }

        if ($request->has('telepon')) {
            $payload['telepon'] = $request->input('telepon');
        }
        if ($request->has('website')) {
            $payload['website'] = $request->input('website');
        }

        if ($request->has('alamat')) {
            $payload['alamat'] = $request->input('alamat');
        }
        if ($request->has('footer')) {
            $payload['footer'] = $request->input('footer');
        }

        if ($request->has('deskripsi')) {
            $payload['deskripsi'] = $request->input('deskripsi');
        }
        if ($request->has('privacy_policy')) {
            $payload['privacy_policy'] = $request->input('privacy_policy');
        }

        return $payload;
    }

    /** Fetch Client (Pagination) */
    public function fetch(Request $request): JsonResponse
    {
        $result = $this->service->fetch();
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
    public function store(IdentityRequest $request): JsonResponse
    {
        $user = getUser($request);
        $payload = (object) array_merge($this->bodyValidation($request), [
            'photo' => null,
            'created_by' => $user->user_id,
        ]);

        if ($request->hasFile('photo')) {
            $payload->photo = $request->file('photo')->store(self::$pathLocation, 'public');
        }

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
            'photo' => null,
            'updated_at' => $today,
            'updated_by' => $user->user_id,
        ]);

        if ($request->hasFile('photo')) {
            $payload->photo = $request->file('photo')->store(self::$pathLocation, 'public');
        }

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
