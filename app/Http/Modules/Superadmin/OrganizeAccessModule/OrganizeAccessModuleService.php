<?php

namespace App\Http\Modules\Superadmin\OrganizeAccessModule;

use App\Helpers\ImageStorageHelper;
use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\OrganizationModuleAccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Str;

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
        $errors = [];
        $temporaries = [];
        for ($i = 0; $i < count($payload['list_modules']); $i++) {
            $element = $payload['list_modules'][$i];

            $row = $this->repository->findByCondition([
                "organisasi_id" => $id,
                "modul_id" => $element["modul_id"]
            ]);

            if ($row) {
                array_push($errors, $row);
            } else {
                $item = array_merge($element, [
                    'organisasi_id' => $id,
                    'access_code' => generateCodAccess(),
                    "{$this->primaryKey}" => (string) Str::uuid(),
                    'is_active' => true,
                    'created_at' => $payload['created_at'],
                    'created_by' => $payload['created_by'],
                ]);
                array_push($temporaries, $item);
            }
        }

        if (count($errors) > 0) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => 'List module']), $errors);
        }


        if (count($temporaries) <= 0) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.organizeModule.assign'), null);
        }

        $result = $this->repository->bulkInsert($temporaries);

        if (!$result) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.organizeModule.assign', ['attribute' => 'Data Organisasi']), $result);
        }

        return new LaravelResponseContract(true, 200, __('validation.custom.success.organizeModule.assign'), $temporaries);
        return sendErrorResponse($e);
    }

    public function deleteAccessModule(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'ID Access Modul']), $row);
            }

            $this->repository->delete($id, (array) $payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.organizeModule.delete'), (object) [
                "{$this->primaryKey}" => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
