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
    public static $pathLocation = 'user/photo';

    protected $service;

    const SORT_COLUMNS = [
        'name' => 'name',
        'email' => 'email',
        'role_name' => 'role.role_name',
    ];

    const DEFAULT_SORT = ['created_at', 'ASC'];

    public function __construct(UserService $service)
    {
        $this->service = $service;
        $this->primaryKey = User::getPrimaryKeyName();
    }

    public function bodyValidation(Request $request): array
    {
        $payload = [];
        if ($request->has('name')) {
            $payload['name'] = $request->input('name');
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

    /** Create Client */
    public function store(UserRequest $request): JsonResponse
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
        $payload = (object) array_merge($this->bodyValidation($request), [
            'photo' => null,
            'updated_at' => Carbon::now(),
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
