<?php

namespace App\Http\Modules\Superadmin\Menu;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Models\MasterMenu;

class MenuController extends Controller
{
    private $primaryKey;
    private $pathLocation;
    protected $service;


    public function __construct(MenuService $service)
    {
        $this->service = $service;
        $this->primaryKey = MasterMenu::getPrimaryKeyName();
        $this->pathLocation = config('constants.path_image.menu');
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];

        if ($request->has('image_id')) {
            $payload['image_id'] = $request->input('image_id');
        }

        if ($request->has('modul_id')) {
            $payload['modul_id'] = $request->input('modul_id');
        }

        if ($request->has('parent_id')) {
            $payload['parent_id'] = $request->input('parent_id');
        }

        if ($request->has('nama_menu')) {
            $payload['nama_menu'] = $request->input('nama_menu');
        }

        if ($request->has('slug')) {
            $payload['slug'] = $request->input('slug');
        }

        if ($request->has('urutan')) {
            $payload['urutan'] = $request->input('urutan');
        }

        return $payload;
    }


    /** Get Menu By Id */
    public function findById(Request $request): JsonResponse
    {
        $id = $request->route("{$this->primaryKey}");
        $result = $this->service->findById($id);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Create Menu */
    public function uploadIcon(MenuRequest $request): JsonResponse
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

    /** Create Sub Menu */
    public function store(MenuRequest $request): JsonResponse
    {
        $user = getUser($request);
        $payload = (object) array_merge($this->bodyValidation($request), [
            'created_by' => $user->user_id,
        ]);
        $result = $this->service->store($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    public function changeIcon(MenuRequest $request): JsonResponse
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


    /* Soft Delete Menu */
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
