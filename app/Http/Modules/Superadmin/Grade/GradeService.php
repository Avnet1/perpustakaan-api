<?php

namespace App\Http\Modules\Superadmin\Grade;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\MasterJenjang;
use Exception;

class GradeService
{
    private $primaryKey;
    protected $repository;

    public function __construct(GradeRepository $repository)
    {
        $this->repository = $repository;
        $this->primaryKey = MasterJenjang::getPrimaryKeyName();
    }

    public function fetch(mixed $filters): LaravelResponseInterface
    {
        try {
            $sqlQuery = MasterJenjang::whereNull('deleted_at');

            if ($filters?->paging?->search) {
                $search = $filters->paging->search;
                $sqlQuery->where(function ($builder) use ($search) {
                    $builder
                        ->where("nama_jenjang", "ilike", "%{$search}%")
                        ->orWhere("urutan", $search);
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
            return new LaravelResponseContract(true, 200, __('validation.custom.success.grade.fetch'), $response);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Master jenjang']), $row);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.grade.find'), $row);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function store(mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findByCondition([
                'nama_jenjang' => $payload->nama_jenjang,
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Nama jenjang ({$row->nama_jenjang})"]), $row);
            }

            $row = $this->repository->findByCondition([
                'urutan' => $payload->urutan,
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "No. urut ({$row->urutan})"]), $row);
            }


            $result = $this->repository->insert((array) $payload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.grade.create'), $result);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.grade.create'), (object) [
                "{$this->primaryKey}" => $result["{$this->primaryKey}"],
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function update(string $id, mixed $payload): LaravelResponseInterface
    {
        try {

            $row = $this->repository->checkExisted($id,  [
                'nama_jenjang' => $payload->nama_jenjang,
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.existedRow', ['attribute' => "Nama Jenjang {$payload->nama_jenjang}"]), $row);
            }

            $row = $this->repository->checkExisted($id,  [
                'urutan' => $payload->urutan,
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.existedRow', ['attribute' => "No. urut {$payload->urutan}"]), $row);
            }


            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Master jenjang']), $row);
            }

            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.grade.update'), $result);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.grade.update'), (object) [
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
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Kelurahan']), $row);
            }

            $this->repository->delete($id, (array) $payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.grade.delete'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
