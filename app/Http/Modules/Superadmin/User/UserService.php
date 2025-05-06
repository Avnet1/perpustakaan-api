<?php

namespace App\Http\Modules\Superadmin\User;

use App\Helpers\ImageStorageHelper;
use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\User;
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
        try {
            $sqlQuery = User::with(['role'])->whereNull('deleted_at');

            if ($filters?->paging?->search) {
                $search = $filters->paging->search;
                $sqlQuery->where(function ($builder) use ($search) {
                    $builder
                        ->where("role.role_name", "ilike", "%{$search}%")
                        ->orWhere("name", "ilike", '%' . "%{$search}%")
                        ->orWhere("email", "ilike", '%' . "%{$search}%");
                });
            }

            foreach ($filters->sorting as $column => $order) {
                if ($column == 'role_name') {
                    $sqlQuery->orderBy(
                        Role::select('role_name')
                            ->whereColumn('roles.role_id', 'users.role_id'),
                        $order
                    );
                } else {
                    $sqlQuery->orderBy($column, $order);
                }
            }


            $sqlQueryCount = $sqlQuery;
            $sqlQueryRows = $sqlQuery;

            $totalRows = $sqlQueryCount->count();
            $rows =  $sqlQueryRows
                ->skip($filters->paging->skip)
                ->take($filters->paging->limit)
                ->get();

            $response = setPagination($rows, $totalRows, $filters->paging->page, $filters->paging->limit);
            return new LaravelResponseContract(true, 200, __('validation.custom.success.user.fetch'), $response);
        } catch (\Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'User']), $row);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.user.find'), $row);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }


    public function uploadImage(mixed $payload): LaravelResponseInterface
    {
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

    public function store(mixed $payload): LaravelResponseInterface
    {
        try {
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
                "icon" => $pathIcon
            ]);


            $result = $this->repository->insert($mergePayload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.user.create'), $result);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.user.create'), (object) [
                "{$this->primaryKey}" => $result["{$this->primaryKey}"],
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function update(string $id, mixed $payload): LaravelResponseInterface
    {

        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'User']), $row);
            }

            if (isset($payload->password)) {
                unset($payload->password);
            }

            if (isset($payload->email)) {
                $row = $this->repository->checkExisted($id, ["email" => $payload->email]);

                if ($row) {
                    return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Email ({$row->email})"]), $row);
                }
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
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Modul']), $row);
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
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'User']), $row);
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
