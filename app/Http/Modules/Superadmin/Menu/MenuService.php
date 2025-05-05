<?php

namespace App\Http\Modules\Superadmin\Menu;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use Illuminate\Support\Facades\DB;
use App\Models\MasterModule;
use Exception;

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

    public function storeIconMenu(mixed $payload): LaravelResponseInterface
    {
        DB::beginTransaction();
        try {
            $result = $this->repository->insert([
                'image_path' => $payload->icon,
                'created_by' => $payload->created_by
            ]);

            if (!$result) {
                DB::rollBack();
                deleteFileInStorage($payload->icon);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.menu.upload-icon'), $result);
            }

            DB::commit();

            return new LaravelResponseContract(true, 200, __('validation.custom.success.menu.upload-icon'), (object) [
                "image_id" => $result["image_id"],
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->icon);
            return sendErrorResponse($e);
        }
    }

    public function store(mixed $payload): LaravelResponseInterface
    {
        try {

            $row = $this->repository->findByCondition([
                'nama_menu' => "%{$payload->nama_menu}%",
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Nama menu ({$payload->nama_menu})"]), $row);
            }


            $row = $this->repository->findByCondition([
                'urutan' => $payload->urutan,
                'parent_id' => $payload->parent_id
            ]);

            if ($row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "No. urut/Urutan ({$payload->urutan})"]), $row);
            }

            $pathIcon = null;

            if (isset($payload->image_id)) {
                $row = $this->repository->findImage($payload->image_id);

                if ($row) {
                    return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => "Image ID"]), $row);
                }

                $pathIcon = $row->image_path;
            }


            $mergePayload = array_merge((array) $payload, [
                "icon" => $pathIcon
            ]);

            $result = $this->repository->insert($mergePayload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.menu.create'), $result);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.menu.create'), (object) [
                "{$this->primaryKey}" => $result["{$this->primaryKey}"],
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

    public function updateIconMenu(string $id, mixed $payload): LaravelResponseInterface
    {
        $storageOldPath = null;
        DB::beginTransaction();
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                DB::rollBack();
                deleteFileInStorage($payload->icon);
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Menu']), $row);
            }

            if ($row->icon != null) {
                $storageOldPath = $row->icon;
            }

            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                DB::rollBack();
                deleteFileInStorage($payload->icon);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.menu.update'), $result);
            }

            deleteFileInStorage($storageOldPath);

            DB::commit();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.menu.update'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->icon);
            return sendErrorResponse($e);
        }
    }

    public function update(string $id, mixed $payload): LaravelResponseInterface
    {
        $row = $this->repository->findById($id);

        if (!$row) {
            return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Menu']), $row);
        }

        $row = $this->repository->checkExisted($id,  [
            'nama_menu' => "%{$payload->nama_menu}%",
        ]);

        if ($row) {
            return new LaravelResponseContract(false, 404, __('validation.custom.error.default.existedRow', ['attribute' => "Nama Menu {$payload->nama_menu}"]), $row);
        }


        $result = $this->repository->update($id, (array) $payload);

        if (!$result) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.socialMedia.update'), $result);
        }

        return new LaravelResponseContract(true, 200, __('validation.custom.success.socialMedia.update'), (object) [
            "{$this->primaryKey}" => $id,
        ]);
    }
}
