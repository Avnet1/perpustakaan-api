<?php

namespace App\Http\Modules\Superadmin\Province;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\MasterProvinsi;
use Exception;

class ProvinsiService
{
    public static $primaryKey = 'provinsi_id';
    protected $repository;

    public function __construct(ProvinsiRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetch(mixed $filters): LaravelResponseInterface
    {
        try {
            $sqlQuery = MasterProvinsi::whereNull('deleted_at');

            if ($filters?->paging?->search) {
                $search = $filters->paging->search;
                $sqlQuery->where(function ($builder) use ($search) {
                    $builder
                        ->where("nama_provinsi", "ilike", "%{$search}%")
                        ->orWhere("kode_provinsi", "ilike", '%' . "%{$search}%")
                        ->orWhere("kode_dikti", "ilike", '%' . "%{$search}%");
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
            return new LaravelResponseContract(true, 200, __('validation.custom.success.province.fetch'), $response);
        } catch (\Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Provinsi']), $row);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.province.find'), $row);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function store(mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findByCondition([
                'kode_provinsi' => $payload->kode_provinsi,
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Provinsi ({$row->kode_provinsi})"]), $row);
            }

            $result = $this->repository->insert((array) $payload);

            if (!$result || !isset($result->provinsi_id)) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.province.create'), $result);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.province.create'), [
                self::$primaryKey => $result->provinsi_id,
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
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Provinsi']), $row);
            }

            $row->update((array) $payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.province.update'), (object) [
                self::$primaryKey => $id,
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
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Provinsi']), $row);
            }

            $row->updateQuietly((array) $payload);
            $row->delete();

            return new LaravelResponseContract(true, 200, __('validation.custom.success.province.delete'), (object) [
                self::$primaryKey => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
