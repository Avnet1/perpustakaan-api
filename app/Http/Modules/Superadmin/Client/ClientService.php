<?php

namespace App\Http\Modules\Superadmin\Client;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClientService
{
    public static $primaryKey = 'client_id';
    protected $repository;

    public function __construct(ClientRepository $repository)
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
            $sqlQuery = Client::with([
                'user' => function ($q) {
                    $q->with([
                        'role'
                    ])->select('user_id', 'email', 'identity_code', 'role.role_name', 'role.role_slug');
                },
                'organisasi' => function ($q) {
                    $q->with([
                        'universitas'
                    ])->select('organisasi_id', 'universitas.nama_universitas');
                }
            ])->whereNull('deleted_at');

            if ($filters?->paging?->search) {
                $search = $filters->paging->search;
                $sqlQuery->where(function ($builder) use ($search) {
                    $builder
                        ->where("client_name", "ilike", "%{$search}%")
                        ->orWhere("client_code", "ilike", '%' . "%{$search}%")
                        ->orWhere("client_job", "ilike", '%' . "%{$search}%")
                        ->orWhere("client_phone", "ilike", '%' . "%{$search}%")
                        ->orWhere("client_email", "ilike", '%' . "%{$search}%")
                        ->orWhere("organisasi.universitas.nama_universitas", "ilike", '%' . "%{$search}%");
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
            return new LaravelResponseContract(true, 200, __('validation.custom.success.client.fetch'), $response);
        } catch (\Exception $e) {
            return \sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Data Pelanggan']), $row);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.client.find'), $row);
        } catch (\Exception $e) {
            return \sendErrorResponse($e);
        }
    }

    public function storeInfo(mixed $payload): LaravelResponseInterface
    {
        DB::transaction();
        try {
            $row = $this->repository->findByCondition([
                'client_code' => $payload->client_code,
            ]);

            if ($row) {
                DB::rollBack();
                $this->deleteStorage($payload->client_photo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "ID Pelanggan ({$payload->client_code})"]), $row);
            }

            $result = $this->repository->insert($payload);

            if (!$result) {
                DB::rollBack();
                $this->deleteStorage($payload->client_photo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.client.create'), $result);
            }

            DB::commit();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.client.create'), (object) [
                "{$this->primaryKey}" => $result->client_id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->deleteStorage($payload->client_photo);
            return \sendErrorResponse($e);
        }
    }

    public function storeAccount(string $id, mixed $payload): LaravelResponseInterface
    {
        DB::transaction();
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                DB::rollBack();
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Data Pelanggan']), $row);
            }

            $row->update($payload);

            DB::commit();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.client.update'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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
            return new LaravelResponseContract(true, 200, __('validation.custom.success.client.update'), (object) [
                "{$this->primaryKey}" => $id,
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

            return new LaravelResponseContract(true, 200, __('validation.custom.success.client.delete'), (object) [
                'id' => $id,
            ]);
        } catch (\Exception $e) {
            return \sendErrorResponse($e);
        }
    }
}
