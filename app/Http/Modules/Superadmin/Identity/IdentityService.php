<?php

namespace App\Http\Modules\Superadmin\Identity;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\MasterIdentitas;
use Exception;
use Illuminate\Support\Facades\DB;

class IdentityService
{
    private $primaryKey;
    protected $repository;

    public function __construct(IdentityRepository $repository)
    {
        $this->repository = $repository;
        $this->primaryKey = MasterIdentitas::getPrimaryKeyName();
    }

    public function fetch(): LaravelResponseInterface
    {
        try {
            $result = MasterIdentitas::whereNull('deleted_at')->first();

            $result->photo = getFileUrl($result->photo);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.identity.fetch'), $result);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $result = $this->repository->findById($id);

            if (!$result) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Identitas']), $result);
            }

            $result->photo = getFileUrl($result->photo);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.identity.find'), $result);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function store(mixed $payload): LaravelResponseInterface
    {
        DB::beginTransaction();

        try {
            $row = $this->repository->checkRow();

            if ($row > 0) {
                DB::rollBack();
                deleteFileInStorage($payload->photo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Identitas"]), $row);
            }

            $result = $this->repository->insert((array) $payload);

            if (!$result) {
                DB::rollBack();
                deleteFileInStorage($payload->photo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.identity.create'), $result);
            }

            DB::commit();

            return new LaravelResponseContract(true, 200, __('validation.custom.success.identity.create'), [
                "{$this->primaryKey}" => $result["{$this->primaryKey}"],
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->photo);
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
                deleteFileInStorage($payload->photo);
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Identitas']), $row);
            }

            if ($row->photo != null) {
                $storageOldPath = $row->photo;
            }

            if ($payload->photo == null) {
                $hasPhoto = false;
                unset($payload->photo);
            }


            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                DB::rollBack();
                deleteFileInStorage($payload->photo);
                return new LaravelResponseContract(false, 404, __('validation.custom.error.identity.update'), $result);
            }

            if ($hasPhoto == true) {
                deleteFileInStorage($storageOldPath);
            }

            DB::commit();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.identity.update'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->photo);
            return sendErrorResponse($e);
        }
    }

    public function delete(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Identitas']), $row);
            }

            $this->repository->delete($id, (array) $payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.identity.delete'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
