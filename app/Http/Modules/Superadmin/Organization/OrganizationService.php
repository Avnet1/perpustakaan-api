<?php

namespace App\Http\Modules\Superadmin\Organization;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\MasterOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrganizationService
{
    protected $repository;

    public function __construct(OrganizationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function deleteStorage($path)
    {
        if ($path != null) {
            Storage::disk('public')->delete($path);
        }
    }

    public function fetch(mixed $filters): LaravelResponseInterface
    {
        try {
            $sqlQuery = MasterOrganisasi::with([
                'universitas' => function ($q) {
                    $q->with(['jenisUniversitas'])->select('universitas_id', 'nama_universitas', 'singkatan', 'jenisUniversitas.nama_jenis');
                },
                'provinsi' => function ($q) {
                    $q->select('provinsi_id', 'nama_provinsi', 'kode_provinsi', 'kode_dikti');
                },
                'kabupatenKota' => function ($q) {
                    $q->select('kabupaten_kota_id', 'nama_kabupaten_kota', 'status_administrasi', 'kode_kabupaten_kota', 'kode_dikti');
                },
                'kecamatan' => function ($q) {
                    $q->select(
                        'kecamatan_id',
                        'nama_kecamatan',
                        'kode_kecamatan',
                        'kode_dikti'
                    );
                },
                'kelurahan' => function ($q) {
                    $q->select('kelurahan_id', 'nama_kelurahan',  'kode_kelurahan', 'kode_dikti');
                },

            ])->whereNull('deleted_at');

            if ($filters?->paging?->search) {
                $search = $filters->paging->search;
                $sqlQuery->where(function ($builder) use ($search) {
                    $builder
                        ->where("universitas.nama_universitas", "ilike", "%{$search}%")
                        ->orWhere("provinsi.nama_provinsi", "ilike", '%' . "%{$search}%")
                        ->orWhere("kabupatenKota.nama_kabupaten_kota", "ilike", '%' . "%{$search}%")
                        ->orWhere("kecamatan.nama_kecamatan", "ilike", '%' . "%{$search}%")
                        ->orWhere("kelurahan.nama_kelurahan", "ilike", '%' . "%{$search}%")
                        ->orWhere("postal_code", "ilike", '%' . "%{$search}%")
                        ->orWhere("email", "ilike", '%' . "%{$search}%")
                        ->orWhere("address", "ilike", '%' . "%{$search}%");
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
            return new LaravelResponseContract(true, 200, __('validation.custom.success.organization.fetch'), $response);
        } catch (\Exception $e) {
            return \sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'ID Organisasi']), $row);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.organization.find'), $row);
        } catch (\Exception $e) {
            return \sendErrorResponse($e);
        }
    }

    public function store(mixed $payload): LaravelResponseInterface
    {
        DB::transaction();
        try {
            $row = $this->repository->findByCondition([
                '' => $payload->client_code,
            ]);

            if ($row) {
                DB::rollBack();
                $this->deleteStorage($payload->client_photo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "ID Client ({$payload->client_code})"]), $row);
            }

            $result = $this->repository->insert($payload);

            if (!$result) {
                DB::rollBack();
                $this->deleteStorage($payload->client_photo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.organization.create'), $result);
            }

            DB::commit();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.organization.create'), (object) [
                'client_id' => $result->client_id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->deleteStorage($payload->client_photo);
            return \sendErrorResponse($e);
        }
    }

    public function update(string $id, mixed $payload): LaravelResponseInterface
    {
        $storageOldPath = null;
        $hasPhoto = true;
        DB::transaction();
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                DB::rollBack();
                $this->deleteStorage($payload->client_photo);
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'ID Pelanggan']), $row);
            }

            if ($row->client_photo != null) {
                $storageOldPath = $row->client_photo;
            }

            if ($payload->client_photo == null) {
                $hasPhoto = false;
                unset($payload->client_photo);
            }

            $row->update($payload);

            if ($hasPhoto == true) {
                $this->deleteStorage($storageOldPath);
            }


            DB::commit();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.organization.update'), (object) [
                'client_id' => $id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($payload->client_photo != null) {
                Storage::disk('public')->delete($payload->client_photo);
            }

            return \sendErrorResponse($e);
        }
    }

    public function delete(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'ID Pelanggan']), $row);
            }

            $row->update($payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.organization.delete'), (object) [
                'client_id' => $id,
            ]);
        } catch (\Exception $e) {
            return \sendErrorResponse($e);
        }
    }
}
