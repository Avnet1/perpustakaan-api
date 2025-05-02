<?php

namespace App\Http\Modules\Superadmin\User;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
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
                $sqlQuery->orderBy($column, $order);
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

    public function store(mixed $payload): LaravelResponseInterface
    {
        DB::beginTransaction();

        try {
            $row = $this->repository->findByCondition([
                'email' => $payload->email,
            ]);

            if ($row) {
                DB::rollBack();
                deleteFileInStorage($payload->photo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Email ({$payload->email})"]), $row);
            }

            $payload->password = Hash::make($payload->password);


            $result = $this->repository->insert((array) $payload);

            if (!$result) {
                DB::rollBack();
                deleteFileInStorage($payload->photo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.user.create'), $result);
            }

            DB::commit();

            return new LaravelResponseContract(true, 200, __('validation.custom.success.user.create'), (object) [
                "{$this->primaryKey}" => $result["{$this->primaryKey}"],
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->photo);
            return sendErrorResponse($e);
        }
    }

    public function update(string $id, mixed $payload): LaravelResponseInterface
    {
        $storageOldPath = null;
        $hasPhoto = true;
        DB::beginTransaction();
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                DB::rollBack();
                deleteFileInStorage($payload->photo);
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'User']), $row);
            }

            if (isset($payload->password)) {
                unset($payload->password);
            }

            if ($row->photo != null) {
                $storageOldPath = $row->photo;
            }

            if ($payload->photo == null) {
                $hasPhoto = false;
                unset($payload->photo);
            }


            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                DB::rollBack();
                deleteFileInStorage($payload->photo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.user.update'), $result);
            }

            if ($hasPhoto == true) {
                deleteFileInStorage($storageOldPath);
            }

            DB::commit();

            return new LaravelResponseContract(true, 200, __('validation.custom.success.user.update'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->photo);
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
