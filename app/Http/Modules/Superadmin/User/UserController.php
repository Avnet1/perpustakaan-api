<?php

namespace App\Http\Modules\Superadmin\User;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    private $primaryKey;
    private $pathLocation;

    protected $service;

    const SORT_COLUMNS = [
        'name' => 'name',
        'email' => 'email',
        'role_name' => 'role_name',
    ];

    const DEFAULT_SORT = ['created_at', 'ASC'];

    public function __construct(UserService $service)
    {
        $this->service = $service;
        $this->primaryKey = User::getPrimaryKeyName();
        $this->pathLocation = config('constants.path_image.user');
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];
        if ($request->has('name')) {
            $payload['name'] = $request->input('name');
        }

        if ($request->has('image_id')) {
            $payload['image_id'] = $request->input('image_id');
        }

        if ($request->has('email')) {
            $payload['email'] = $request->input('email');
        }

        if ($request->has('password')) {
            $payload['password'] = $request->input('password');
        }

        if ($request->has('confirm_password')) {
            $payload['confirm_password'] = $request->input('confirm_password');
        }

        if ($request->has('role_id')) {
            $payload['role_id'] = $request->input('role_id');
        }

        return $payload;
    }

    /** Fetch User (Pagination) */
    public function fetch(Request $request): JsonResponse
    {
        $filters = (object) [
            "paging" => defineRequestPaginateArgs($request),
            "sorting" => defineRequestOrder($request, self::DEFAULT_SORT, self::SORT_COLUMNS),
            "query" => $request->all()
        ];
        $result = $this->service->fetch($filters);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Get User By Id */
    public function findById(Request $request): JsonResponse
    {
        $id = $request->route("{$this->primaryKey}");
        $result = $this->service->findById($id);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    public function uploadImage(UserRequest $request): JsonResponse
    {
        $user = getUser($request);
        $payload = (object) array_merge($this->bodyValidation($request), [
            'photo' => null,
            'created_by' => $user->user_id,
        ]);

        if ($request->hasFile('photo')) {
            $payload->photo = $request->file('photo')->store("{$this->pathLocation}", 'public');
        }

        $result = $this->service->uploadImage($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }


    /** Create User */
    public function store(UserRequest $request): JsonResponse
    {
        $user = getUser($request);
        $payload = (object) array_merge($this->bodyValidation($request), [
            'created_at' => Carbon::now(),
            'created_by' => $user->user_id,
        ]);

        $result = $this->service->store($payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }


    public function changeImage(UserRequest $request): JsonResponse
    {
        $user = getUser($request);
        $id = $request->route("{$this->primaryKey}");
        $payload = (object) [
            'photo' => null,
            'updated_at' => Carbon::now(),
            'updated_by' => $user->user_id
        ];

        if ($request->hasFile('photo')) {
            $payload->photo = $request->file('photo')->store("{$this->pathLocation}", 'public');
        }

        $result = $this->service->changeImage($id, $payload);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    /** Update User */
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

    /* Soft Delete User */
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
