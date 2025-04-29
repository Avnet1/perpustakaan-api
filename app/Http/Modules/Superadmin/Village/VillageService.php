<?php

namespace App\Http\Modules\Superadmin\Village;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\MasterKelurahan;
use Exception;

class VillageService
{
    public static $primaryKey = 'kelurahan_id';
    protected $repository;

    public function __construct(VillageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetch(mixed $filters): LaravelResponseInterface
    {
        try {
            $sqlQuery = MasterKelurahan::with([
                'provinsi',
                'kabupatenKota',
                'kecamatan',
            ])->whereNull('deleted_at');

            if ($filters?->paging?->search) {
                $search = $filters->paging->search;
                $sqlQuery->where(function ($builder) use ($search) {
                    $builder
                        ->where("nama_kelurahan", "ilike", "%{$search}%")
                        ->orWhere("kode_kelurahan", "ilike", '%' . "%{$search}%")
                        ->orWhere("kode_dikti", "ilike", '%' . "%{$search}%")
                        ->orWhere("kecamatan.nama_kecamatan", "ilike", '%' . "%{$search}%")
                        ->orWhere("provinsi.nama_provinsi", "ilike", '%' . "%{$search}%")
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
            return new LaravelResponseContract(true, 200, __('validation.custom.success.village.fetch'), $response);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Kelurahan']), $row);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.village.find'), $row);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function store(mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findByCondition([
                'provinsi_id' => $payload->provinsi_id,
                'kabupaten_kota_id' => $payload->kabupaten_kota_id,
                'kecamatan_id' => $payload->kecamatan_id,
                'kode_kelurahan' => $payload->kode_kelurahan,
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Kode Kelurahan ({$row->kode_kelurahan})"]), $row);
            }

            $result = $this->repository->insert((array) $payload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.village.create'), $result);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.village.create'), (object) [
                self::$primaryKey => $result->kelurahan_id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function update(string $id, mixed $payload): LaravelResponseInterface
    {
        try {

            $row = $this->repository->checkExisted($id,  [
                'provinsi_id' => $payload->provinsi_id,
                'kabupaten_kota_id' => $payload->kabupaten_kota_id,
                'kecamatan_id' => $payload->kecamatan_id,
                'kode_kelurahan' => $payload->kode_kelurahan,
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.existedRow', ['attribute' => 'Kode Kelurahan']), $row);
            }


            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Kelurahan']), $row);
            }

            $row->update((array) $payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.village.update'), (object) [
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
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Kelurahan']), $row);
            }

            $this->repository->delete($id, (array) $payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.village.delete'), (object) [
                self::$primaryKey => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
