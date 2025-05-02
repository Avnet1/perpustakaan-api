<?php

namespace App\Http\Modules\Superadmin\SocialMedia;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SocialMediaRequest;
use App\Models\MasterSocialMedia;

class SocialMediaController extends Controller
{
    private $primaryKey;
    public static $pathLocation = 'social_media/logo';
    protected $service;

    public function __construct(SocialMediaService $service)
    {
        $this->service = $service;
        $this->primaryKey = MasterSocialMedia::getPrimaryKeyName();
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];

        if ($request->has('identitas_id')) {
            $payload['identitas_id'] = $request->input('identitas_id');
        }

        if ($request->has('nama_sosmed')) {
            $payload['nama_sosmed'] = $request->input('nama_sosmed');
        }

        if ($request->has('link_sosmed')) {
            $payload['link_sosmed'] = $request->input('link_sosmed');
        }
        return $payload;
    }

    /** Fetch Client (Pagination) */
    public function fetch(Request $request): JsonResponse
    {
        $where = $request->query();
        $result = $this->service->fetch($where);
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
    public function store(Request $request): JsonResponse
    {
        $user = getUser($request);
        $payload = (object) array_merge($this->bodyValidation($request), [
            'logo' => null,
            'created_by' => $user->user_id,
        ]);

        if ($request->hasFile('logo')) {
            $payload->logo = $request->file('logo')->store(self::$pathLocation, 'public');
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
            'logo' => null,
            'updated_at' => $today,
            'updated_by' => $user->user_id,
        ]);

        if ($request->hasFile('logo')) {
            $payload->logo = $request->file('logo')->store(self::$pathLocation, 'public');
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
