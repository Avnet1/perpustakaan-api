<?php

namespace App\Http\Modules\Superadmin\Subdistrict;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\MasterKecamatan;

class SubdistrictService
{
    public static $primaryKey = 'kecamatan_id';
    protected $repository;

    public function __construct(SubdistrictRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetch(mixed $filters): LaravelResponseInterface
    {
        try {
            $sqlQuery = MasterKecamatan::with([
                'kabupatenKota'
            ])->whereNull('deleted_at');

            if ($filters?->paging?->search) {
                $search = $filters->paging->search;
                $sqlQuery->where(function ($builder) use ($search) {
                    $builder
                        ->where("nama_kecamatan", "ilike", "%{$search}%")
                        ->orWhere("kode_kecamatan", "ilike", '%' . "%{$search}%")
                        ->orWhere("kode_dikti", "ilike", '%' . "%{$search}%")
                        ->orWhere("kabupatenKota.nama_kabupaten_kota", "ilike", '%' . "%{$search}%");
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
            return new LaravelResponseContract(true, 200, __('validation.custom.success.sub_district.fetch'), $response);
        } catch (\Exception $e) {
            return \sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Kecamatan']), $row);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.sub_district.find'), $row);
        } catch (\Exception $e) {
            return \sendErrorResponse($e);
        }
    }

    public function store(mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findByCondition([
                'kode_kecamatan' => $payload->client_code,
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Kode Kecamatan ({$row->kode_kecamatan})"]), $row);
            }

            $result = $this->repository->insert($payload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.sub_district.create'), $result);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.sub_district.create'), (object) [
                "{$this->primaryKey}" => $result->provinsi_id,
            ]);
        } catch (\Exception $e) {
            return \sendErrorResponse($e);
        }
    }

    public function update(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Kecamatan']), $row);
            }

            $row->update($payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.sub_district.update'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (\Exception $e) {
            return \sendErrorResponse($e);
        }
    }

    public function delete(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Kecamatan']), $row);
            }

            $row->update($payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.sub_district.delete'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (\Exception $e) {
            return \sendErrorResponse($e);
        }
    }
}
