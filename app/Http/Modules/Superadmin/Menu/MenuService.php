<?php

namespace App\Http\Modules\Superadmin\Menu;

use App\Helpers\ImageStorageHelper;
use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\MasterMenu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class MenuService
{
    private $primaryKey;
    protected $repository;

    public function __construct(MenuRepository $repository)
    {
        $this->repository = $repository;
        $this->primaryKey = MasterMenu::getPrimaryKeyName();
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'Menu']), $row);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.menu.find'), $row);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function uploadImage(mixed $payload): LaravelResponseInterface
    {
        if (isset($payload->menu_id)) {
            $id = $payload->menu_id;
            $payload->updated_at = Carbon::now();
            $payload->updated_by = $payload->created_by;
            unset($payload->modul_id);
            unset($payload->created_by);
            return self::changeImage($id, $payload);
        } else {
            DB::beginTransaction();
            try {
                $result = ImageStorageHelper::storeImage([
                    'image_path' => $payload->icon,
                    'created_by' => $payload->created_by
                ], 'icon');

                if (!$result->success) {
                    DB::rollBack();
                    deleteFileInStorage($payload->icon);
                } else {
                    DB::commit();
                }

                return $result;
            } catch (Exception $e) {
                DB::rollBack();
                deleteFileInStorage($payload->icon);
                return sendErrorResponse($e);
            }
        }
    }

    public function store(mixed $payload): LaravelResponseInterface
    {

        $row = $this->repository->findByCondition([
            'modul_id' => $payload->modul_id,
            'nama_menu' => $payload->nama_menu,
            'slug' => $payload->slug,
        ]);

        if ($row) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Data menu {$payload->nama_menu}"]), $row);
        }

        $pathIcon = null;

        if (isset($payload->image_id)) {
            $row =  ImageStorageHelper::getImage($payload->image_id, 'icon');

            if (!$row->success) {
                return $row;
            }

            $pathIcon =  $row->data->image_path;
            unset($payload->image_id);
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
    }

    public function delete(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'Menu']), $row);
            }

            $this->repository->delete($id, (array) $payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.module.delete'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
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
                deleteFileInStorage($payload->icon);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'Menu']), $row);
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

            $result->icon = getFileUrl($result->icon);

            DB::commit();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.menu.update'), $result);
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
            return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'Menu']), $row);
        }


        $pathFile = null;

        if (isset($payload->image_id)) {
            $row =  ImageStorageHelper::getImage($payload->image_id, 'icon');

            if (!$row->success) {
                return $row;
            }

            $pathFile =  $row->data->image_path;
            unset($payload->image_id);

            $payload = array_merge((array) $payload, [
                "icon" => $pathFile
            ]);
        }


        $result = $this->repository->update($id, (array) $payload);

        if (!$result) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.socialMedia.update'), $result);
        }

        $result->icon = getFileUrl($result->icon);

        return new LaravelResponseContract(true, 200, __('validation.custom.success.socialMedia.update'), $result);
    }
}
