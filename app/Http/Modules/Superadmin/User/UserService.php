<?php

namespace App\Http\Modules\Superadmin\User;

use App\Helpers\ImageStorageHelper;
use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private $primaryKey;
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->primaryKey = User::getPrimaryKeyName();
    }

    public function fetch(mixed $filters): LaravelResponseInterface
    {
        $url = asset('storage');

        try {
            $sqlQuery = DB::table('users')
                ->selectRaw("
                users.*,
                (CASE
                    WHEN users.photo IS NULL THEN NULL
                    ELSE ? || '/' || users.photo
                END) AS photo,
                json_build_object(
                    'role_id', roles.role_id,
                    'role_name', roles.role_name,
                    'role_slug', roles.role_slug,
                    'created_at', roles.created_at,
                    'updated_at', roles.updated_at,
                    'created_by', roles.created_by,
                    'updated_by', roles.updated_by
                )::jsonb AS role
            ", [$url])
                ->leftJoin('roles', 'roles.role_id', '=', 'users.role_id')
                ->whereNull('users.deleted_at');

            if (!empty($filters?->paging?->search)) {
                $search = $filters->paging->search;
                $sqlQuery->where(function ($builder) use ($search) {
                    $builder
                        ->where("roles.role_name", "ilike", "%{$search}%")
                        ->orWhere("users.name", "ilike", "%{$search}%")
                        ->orWhere("users.email", "ilike", "%{$search}%");
                });
            }

            if (!empty($filters->sorting)) {
                foreach ($filters->sorting as $column => $order) {
                    if ($column === 'role_name') {
                        // Tidak bisa pakai subquery dalam orderBy di Query Builder standar untuk join table,
                        // jadi urutkan berdasarkan roles.role_name yang sudah dijoin
                        $sqlQuery->orderBy('roles.role_name', $order);
                    } else {
                        $sqlQuery->orderBy("users.$column", $order);
                    }
                }
            }

            // Clone query untuk count (tanpa pagination)
            $sqlQueryCount = clone $sqlQuery;
            $totalRows = $sqlQueryCount->count();

            // Pagination
            $rows = $sqlQuery
                ->skip($filters->paging->skip)
                ->take($filters->paging->limit)
                ->get()
                ->map(function ($item) {
                    $item->role = json_decode($item->role);
                    return $item;
                });

            $response = setPagination($rows, $totalRows, $filters->paging->page, $filters->paging->limit);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.user.fetch'), $response);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $result = $this->repository->findById($id);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'User']), $result);
            }

            $result->photo = getFileUrl($result->photo);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.user.find'), $result);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }


    public function uploadImage(mixed $payload): LaravelResponseInterface
    {
        if (isset($payload->image_id)) {
            $id = $payload->user_id;
            $payload->updated_at = Carbon::now();
            $payload->updated_by = $payload->created_by;
            unset($payload->user_id);
            unset($payload->created_by);
            return self::changeImage($id, $payload);
        } else {
            DB::beginTransaction();
            try {
                $result = ImageStorageHelper::storeImage([
                    'image_path' => $payload->photo,
                    'created_by' => $payload->created_by
                ], 'icon');

                if (!$result->success) {
                    DB::rollBack();
                    deleteFileInStorage($payload->photo);
                } else {
                    DB::commit();
                }

                return $result;
            } catch (Exception $e) {
                DB::rollBack();
                deleteFileInStorage($payload->photo);
                return sendErrorResponse($e);
            }
        }
    }

    public function store(mixed $payload): LaravelResponseInterface
    {
        if (isset($payload->confirm_password)) {
            unset($payload->confirm_password);
        }

        $row = $this->repository->findByCondition([
            'email' => $payload->email,
        ]);

        if ($row) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Email ({$payload->email})"]), $row);
        }


        $pathIcon = null;

        if (isset($payload->image_id)) {
            $row =  ImageStorageHelper::getImage($payload->image_id, 'photo');

            if (!$row->success) {
                return $row;
            }

            $pathIcon =  $row->data->image_path;
            unset($payload->image_id);
        }

        $payload->password = Hash::make($payload->password);

        $mergePayload = array_merge((array) $payload, [
            "photo" => $pathIcon
        ]);

        $result = $this->repository->insert($mergePayload);

        if (!$result) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.user.create'), $result);
        }

        $result->photo = getFileUrl($result->photo);
        return new LaravelResponseContract(true, 200, __('validation.custom.success.user.create'), $result);
    }

    public function update(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'User']), $row);
            }

            if (isset($payload->confirm_password)) {
                unset($payload->confirm_password);
            }

            if (isset($payload->password)) {
                $payload->password = Hash::make($payload->password);
            }

            if (isset($payload->email)) {
                $row = $this->repository->checkExisted($id, ["email" => $payload->email]);

                if ($row) {
                    return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Email ({$row->email})"]), $row);
                }
            }

            $pathFile = null;

            if (isset($payload->image_id)) {
                $row =  ImageStorageHelper::getImage($payload->image_id, 'photo');

                if (!$row->success) {
                    return $row;
                }

                $pathFile =  $row->data->image_path;
                unset($payload->image_id);

                $payload = array_merge((array) $payload, [
                    "photo" => $pathFile
                ]);
            }


            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.user.update'), $result);
            }

            $result->photo = getFileUrl($result->photo);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.user.update'), $result);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function changeImage(string $id, mixed $payload): LaravelResponseInterface
    {
        $storageOldPath = null;
        DB::beginTransaction();
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                DB::rollBack();
                deleteFileInStorage($payload->photo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'Modul']), $row);
            }

            if ($row->photo != null) {
                $storageOldPath = $row->photo;
            }

            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                DB::rollBack();
                deleteFileInStorage($payload->photo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.module.update'), $result);
            }

            deleteFileInStorage($storageOldPath);

            $result->photo = getFileUrl($result->photo);

            DB::commit();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.module.update'), $result);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->icon);
            return sendErrorResponse($e);
        }
    }

    public function delete(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'User']), $row);
            }

            $this->repository->delete($id, (array) $payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.user.delete'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
