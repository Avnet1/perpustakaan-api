<?php

namespace App\Http\Modules\Superadmin\OrganizeAccessModule;

use App\Helpers\ImageStorageHelper;
use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\OrganizationModuleAccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Hash;

class OrganizeAccessModuleService
{
    private $primaryKey;
    protected $repository;

    public function __construct(OrganizeAccessModuleRepository $repository)
    {
        $this->repository = $repository;
        $this->primaryKey = OrganizationModuleAccess::getPrimaryKeyName();
    }

    public function assignToModules(string $id, mixed $payload): LaravelResponseInterface
    {
        try {

            if ($payload->list_modules)

                $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Data Organisasi']), $row);
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
        $storageOldPath = null;
        $hasPhoto = true;
        DB::beginTransaction();
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                DB::rollBack();
                deleteFileInStorage($payload->logo);
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Data Organisasi']), $row);
            }

            if ($row->logo != null) {
                $storageOldPath = $row->logo;
            }

            if ($payload->logo == null) {
                $hasPhoto = false;
                unset($payload->logo);
            }

            $row->update($payload);

            if ($hasPhoto == true) {
                deleteFileInStorage($storageOldPath);
            }


            DB::commit();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.organization.update'), (object) [
                'id' => $id,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Storage::disk('public')->delete($payload->logo);
            return sendErrorResponse($e);
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
                'id' => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
