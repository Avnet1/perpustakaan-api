<?php

namespace App\Http\Modules\Superadmin\Module;

use App\Helpers\ImageStorageHelper;
use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use Illuminate\Support\Facades\DB;
use App\Models\MasterModule;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;

class ModuleService
{
    private $primaryKey;
    protected $repository;

    public function __construct(ModuleRepository $repository)
    {
        $this->repository = $repository;
        $this->primaryKey = MasterModule::getPrimaryKeyName();
    }

    public function fetch(mixed $filters): LaravelResponseInterface
    {
        $url = asset('storage');

        try {
            $sqlQuery = MasterModule::whereNull('deleted_at')
                ->selectRaw("*, (case when icon is null then null else CONCAT('$url/', icon) end) as icon");

            if ($filters?->paging?->search) {
                $search = $filters->paging->search;
                $sqlQuery->where(function ($builder) use ($search) {
                    $builder
                        ->where("nama_modul", "ilike", "%{$search}%")
                        ->orWhere("slug", "ilike", '%' . "%{$search}%");
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
            return new LaravelResponseContract(true, 200, __('validation.custom.success.module.fetch'), $response);
        } catch (\Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'Modul']), $row);
            }

            $row->icon = getFileUrl($row->icon);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.module.find'), $row);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }


    public function uploadImage(mixed $payload): LaravelResponseInterface
    {
        if (isset($payload->modul_id)) {
            $id = $payload->modul_id;
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
            'nama_modul' => "%{$payload->nama_modul}%",
        ]);

        if ($row) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Nama modul ({$payload->nama_modul})"]), $row);
        }


        $row = $this->repository->findByCondition([
            'urutan' => $payload->urutan,
        ]);

        if ($row) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "No. urut/Urutan ({$payload->urutan})"]), $row);
        }

        $pathFile = null;

        if (isset($payload->image_id)) {
            $row =  ImageStorageHelper::getImage($payload->image_id, 'icon');

            if (!$row->success) {
                return $row;
            }

            $pathFile =  $row->data->image_path;
            unset($payload->image_id);
        }


        $mergePayload = array_merge((array) $payload, [
            "icon" => $pathFile
        ]);


        $result = $this->repository->insert($mergePayload);

        if (!$result) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.module.create'), $result);
        }

        return new LaravelResponseContract(true, 200, __('validation.custom.success.module.create'), (object) [
            "{$this->primaryKey}" => $result["{$this->primaryKey}"],
        ]);
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
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'Modul']), $row);
            }

            if ($row->icon != null) {
                $storageOldPath = $row->icon;
            }

            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                DB::rollBack();
                deleteFileInStorage($payload->icon);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.module.update'), $result);
            }

            deleteFileInStorage($storageOldPath);

            $result->icon = getFileUrl($result->icon);
            DB::commit();

            return new LaravelResponseContract(true, 200, __('validation.custom.success.module.update'), $result);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->icon);
            return sendErrorResponse($e);
        }
    }

    public function update(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'Modul']), $row);
            }


            $row = queryCheckExisted(
                $this->repository->getQuery(),
                [
                    'nama_modul' => "%{$payload->nama_modul}%",
                    'urutan' => $payload->urutan
                ],
                "{$this->primaryKey}",
                $id,
            );

            if ($row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.existedRow', ['attribute' => "Nama Modul {$payload->nama_modul} atau No.urut {$payload->urutan}"]), $row);
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
                return new LaravelResponseContract(false, 400, __('validation.custom.error.module.update'), $result);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.module.update'), (object) [
                "{$this->primaryKey}" => $id,
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
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'Modul']), $row);
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
