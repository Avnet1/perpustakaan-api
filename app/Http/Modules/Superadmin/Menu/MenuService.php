<?php

namespace App\Http\Modules\Superadmin\Menu;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use Illuminate\Support\Facades\DB;
use App\Models\MasterModule;
use Exception;
use Illuminate\Support\Facades\Hash;

class MenuService
{
    private $primaryKey;
    protected $repository;

    public function __construct(MenuRepository $repository)
    {
        $this->repository = $repository;
        $this->primaryKey = MasterModule::getPrimaryKeyName();
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Menu']), $row);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.menu.find'), $row);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function storeMenu(mixed $payload): LaravelResponseInterface
    {
        DB::beginTransaction();
        try {

            $row = $this->repository->findByCondition([
                'nama_menu' => "%{$payload->nama_menu}%",
            ]);

            if ($row) {
                DB::rollBack();
                deleteFileInStorage($payload->icon);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Nama modul ({$payload->nama_modul})"]), $row);
            }


            $row = $this->repository->findByCondition([
                'urutan' => $payload->urutan,
            ]);

            if ($row) {
                DB::rollBack();
                deleteFileInStorage($payload->icon);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "No. urut/Urutan ({$payload->urutan})"]), $row);
            }

            $result = $this->repository->insert((array) $payload);

            if (!$result) {
                DB::rollBack();
                deleteFileInStorage($payload->icon);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.module.create'), $result);
            }

            DB::commit();

            return new LaravelResponseContract(true, 200, __('validation.custom.success.module.create'), (object) [
                "{$this->primaryKey}" => $result["{$this->primaryKey}"],
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->icon);
            return sendErrorResponse($e);
        }
    }

    public function update(string $id, mixed $payload): LaravelResponseInterface
    {
        $storageOldPath = null;
        $hasIcon = true;
        DB::beginTransaction();
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                DB::rollBack();
                deleteFileInStorage($payload->icon);
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Modul']), $row);
            }


            $row = $this->repository->checkExisted($id,  [
                'nama_modul' => "%{$payload->nama_modul}%",
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.existedRow', ['attribute' => "Nama Modul {$payload->nama_modul}"]), $row);
            }


            $row = $this->repository->checkExisted($id,  [
                'urutan' => $payload->urutan,
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.existedRow', ['attribute' => "No. Urut/Urutan {$payload->urutan}"]), $row);
            }


            if ($row->icon != null) {
                $storageOldPath = $row->icon;
            }

            if ($payload->icon == null) {
                $hasIcon = false;
                unset($payload->icon);
            }


            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                DB::rollBack();
                deleteFileInStorage($payload->icon);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.module.update'), $result);
            }

            if ($hasIcon == true) {
                deleteFileInStorage($storageOldPath);
            }

            DB::commit();

            return new LaravelResponseContract(true, 200, __('validation.custom.success.module.update'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->icon);
            return sendErrorResponse($e);
        }
    }

    public function delete(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Modul']), $row);
            }

            $this->repository->delete($id, (array) $payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.module.delete'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
