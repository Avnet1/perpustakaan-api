<?php

namespace App\Http\Modules\Superadmin\Role;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\Role;
use Exception;

class RoleService
{
    private $primaryKey;
    protected $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
        $this->primaryKey = Role::getPrimaryKeyName();
    }

    public function fetch(mixed $filters): LaravelResponseInterface
    {
        try {
            $sqlQuery = Role::whereNull('deleted_at');

            if ($filters?->paging?->search) {
                $search = $filters->paging->search;
                $sqlQuery->where(function ($builder) use ($search) {
                    $builder
                        ->where("role_name", "ilike", "%{$search}%")
                        ->orWhere("role_slug", "ilike", '%' . "%{$search}%");
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
            return new LaravelResponseContract(true, 200, __('validation.custom.success.role.fetch'), $response);
        } catch (\Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Role']), $row);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.role.find'), $row);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function store(mixed $payload): LaravelResponseInterface
    {
        $row = $this->repository->findByCondition([
            'role_name' => $payload->role_name,
        ]);

        if ($row) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Role ({$payload->role_name})"]), $row);
        }

        $result = $this->repository->insert((array) $payload);

        if (!$result) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.role.create'), $result);
        }

        return new LaravelResponseContract(true, 200, __('validation.custom.success.role.create'), (object) [
            "{$this->primaryKey}" => $result["{$this->primaryKey}"],
        ]);
        // try {

        // } catch (Exception $e) {
        //     return sendErrorResponse($e);
        // }
    }

    public function update(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Role']), $row);
            }

            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.role.update'), $result);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.role.update'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function delete(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Role']), $row);
            }

            $this->repository->delete($id, (array) $payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.role.delete'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
