<?php

namespace App\Http\Modules\Superadmin\Region;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\MasterKabupatenKota;
use Exception;

class RegionService
{
    public static $primaryKey = 'kabupaten_kota_id';
    protected $repository;

    public function __construct(RegionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetch(mixed $filters): LaravelResponseInterface
    {
        try {
            $sqlQuery = MasterKabupatenKota::with([
                'provinsi' => function ($query) {
                    $query->select(
                        'provinsi_id',
                        'nama_provinsi',
                        'kode_provinsi',
                        'kode_dikti',
                    );
                }
            ])->whereNull('deleted_at');

            if ($filters?->paging?->search) {
                $search = $filters->paging->search;
                $sqlQuery->where(function ($builder) use ($search) {
                    $builder
                        ->where("nama_kabupaten_kota", "ilike", "%{$search}%")
                        ->orWhere("status_administrasi", "ilike", '%' . "%{$search}%")
                        ->orWhere("kode_kabupaten_kota", "ilike", '%' . "%{$search}%")
                        ->orWhere("kode_dikti", "ilike", '%' . "%{$search}%")
                        ->orWhere("provinsi.nama_provinsi", "ilike", '%' . "%{$search}%");
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
            return new LaravelResponseContract(true, 200, __('validation.custom.success.region.fetch'), $response);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Kabupaten/Kota']), $row);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.region.find'), $row);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function store(mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findByCondition([
                'provinsi_id' => $payload->provinsi_id,
                'kode_kabupaten_kota' => $payload->kode_kabupaten_kota,
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Kode Kabupaten/Kota ({$row->kode_kabupaten_kota})"]), $row);
            }

            $result = $this->repository->insert((array) $payload);

            if (!$result || !isset($result->kabupaten_kota_id)) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.region.create'),  $result);
            }

            return new LaravelResponseContract(true, 200,  __('validation.custom.success.region.create'), [
                self::$primaryKey => $result->kabupaten_kota_id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function update(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->checkExisted($id, (object) [
                'provinsi_id' => $payload->provinsi_id,
                'kode_kabupaten_kota' => $payload->kode_kabupaten_kota
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.existedRow', ['attribute' => 'Kabupaten/Kota']), $row);
            }


            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Kabupaten/Kota']), $row);
            }

            $row->update((array) $payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.region.update'), (object) [
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
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Kabupaten/Kota']), $row);
            }


            $this->repository->delete($id, (array) $payload);


            return new LaravelResponseContract(true, 200, __('validation.custom.success.region.delete'), (object) [
                self::$primaryKey => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
