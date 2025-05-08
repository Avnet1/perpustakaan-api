<?php

namespace App\Http\Modules\Superadmin\Organization;

use App\Helpers\ImageStorageHelper;
use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\MasterOrganisasi;
use App\Services\RabbitMQPublisherService;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;

class OrganizationService
{
    private $primaryKey;
    protected $repository;

    public function __construct(OrganizationRepository $repository)
    {
        $this->repository = $repository;
        $this->primaryKey = MasterOrganisasi::getPrimaryKeyName();
    }


    public function fetch(mixed $filters): LaravelResponseInterface
    {
        try {
            $url = asset('storage');

            $sqlQuery = MasterOrganisasi::whereNull('deleted_at')->selectRaw("*, (case when logo is null then null else CONCAT('$url/', logo) end) as logo")
                ->withCount(['modules as total_layanan']);

            if ($filters?->paging?->search) {
                $search = $filters->paging->search;
                $sqlQuery->where(function ($builder) use ($search) {
                    $builder
                        ->where("kode_member", "ilike", "%{$search}%")
                        ->orWhere("nama_organisasi", "ilike", '%' . "%{$search}%")
                        ->orWhere("provinsi", "ilike", '%' . "%{$search}%")
                        ->orWhere("kabupaten_kota", "ilike", '%' . "%{$search}%")
                        ->orWhere("kecamatan", "ilike", '%' . "%{$search}%")
                        ->orWhere("kelurahan_desa", "ilike", '%' . "%{$search}%")
                        ->orWhere("alamat", "ilike", '%' . "%{$search}%")
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
                ->get()
                ->makeHidden(['db_user', 'db_pass', 'db_name']);

            $response = setPagination($rows, $totalRows, $filters->paging->page, $filters->paging->limit);
            return new LaravelResponseContract(true, 200, __('validation.custom.success.organization.fetch'), $response);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        $result = $this->repository->findById($id);
        if (!$result) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'ID Organisasi']), $result);
        }

        $result->logo = getFileUrl($result->logo);

        return new LaravelResponseContract(true, 200, __('validation.custom.success.organization.find'), $result);
        // try {

        // } catch (Exception $e) {
        //     return sendErrorResponse($e);
        // }
    }

    public function uploadImage(mixed $payload): LaravelResponseInterface
    {
        DB::beginTransaction();
        try {
            $result = ImageStorageHelper::storeImage([
                'image_path' => $payload->logo,
                'created_by' => $payload->created_by
            ], 'logo');

            if (!$result->success) {
                DB::rollBack();
                deleteFileInStorage($payload->logo);
            } else {
                DB::commit();
            }

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->logo);
            return sendErrorResponse($e);
        }
    }

    public function storeInfo(mixed $payload): LaravelResponseInterface
    {
        try {

            $row = $this->repository->findByCondition([
                'kode_member' => $payload->kode_member,
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Kode Member ({$payload->kode_member})"]), $row);
            }


            $pathFile = null;

            if (isset($payload->image_id)) {
                $row =  ImageStorageHelper::getImage($payload->image_id, 'logo');

                if (!$row->success) {
                    return $row;
                }

                $pathFile =  $row->data->image_path;
                unset($payload->image_id);
            }


            $mergePayload = array_merge((array) $payload, [
                "logo" => $pathFile
            ]);

            $result = $this->repository->insert($mergePayload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.organization.create'), $result);
            }

            $result->logo = getFileUrl($result->logo);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.organization.create'), $result);
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
                deleteFileInStorage($payload->logo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'Organisasi']), $row);
            }

            if ($row->logo != null) {
                $storageOldPath = $row->logo;
            }

            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                DB::rollBack();
                deleteFileInStorage($payload->logo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.organization.update'), $result);
            }

            deleteFileInStorage($storageOldPath);

            $result->logo = getFileUrl($result->logo);

            DB::commit();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.module.update'), $result);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->logo);
            return sendErrorResponse($e);
        }
    }

    public function storeAccount(string $id, mixed $payload): LaravelResponseInterface
    {
        try {

            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'Data Organisasi']), $row);
            }

            $row = queryCheckExisted(
                $this->repository->getQuery(),
                [
                    'email' => $payload->email,
                ],
                "{$this->primaryKey}",
                $id
            );

            if ($row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Email ({$payload->email})"]), $row);
            }

            if (isset($payload->password)) {
                $payload->password = Hash::make($payload->password);
            }


            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.organization.update'), $result);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.organization.update'), (object) [
                "{$this->primaryKey}" => $id,
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
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'Data Organisasi']), $row);
            }

            $pathFile = null;

            if (isset($payload->image_id)) {
                $row =  ImageStorageHelper::getImage($payload->image_id, 'logo');

                if (!$row->success) {
                    return $row;
                }

                $pathFile =  $row->data->image_path;
                unset($payload->image_id);

                $payload = array_merge((array) $payload, [
                    "logo" => $pathFile
                ]);
            }

            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.organization.update'), $result);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.organization.update'), $result);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function delete(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'ID Organisasi']), $row);
            }

            $this->repository->delete($id, (array) $payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.organization.delete'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function approved(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'ID Organisasi']), $row);
            }

            if ($row->is_approved) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.existApproved', ['attribute' => "Akses Database Organisasi {$row->nama_organisasi}"]), $row);
            }

            $lastOrder = $this->repository->getLasOrder();
            $payload->db_name = "db_organisasi_" . $lastOrder ? $lastOrder->order_number + 1 : 1;
            $payload->order_number = $lastOrder ? $lastOrder->order_number + 1 : 1;

            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.approved', ['attribute' => "Akses Database Organisasi {$row->nama_organisasi}"]), $result);
            }

            // $rabbitMQ = new RabbitMQPublisherService();
            // $rabbitMQ->publisher($result, config('constants.message_broker.exchange.organization'), config('constants.message_broker.queue.organization'));

            return new LaravelResponseContract(true, 200, __('validation.custom.success.default.approved', ['attribute' => "Akses Database Organisasi {$row->nama_organisasi}"]), (object) [
                "{$this->primaryKey}" => $id,
                "is_approved" => $result->is_approved
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
