<?php

namespace App\Http\Modules\Superadmin\OrganizeAccessModule;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\OrganizationModuleAccess;
use Exception;
use Illuminate\Support\Facades\DB;
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
        DB::beginTransaction();
        $errors = [];
        $temporaries = [];
        $histories = [];
        for ($i = 0; $i < count($payload['list_modules']); $i++) {
            $element = $payload['list_modules'][$i];

            $row = $this->repository->findByCondition([
                "organisasi_id" => $id,
                "modul_id" => $element["modul_id"]
            ]);

            if ($row) {
                array_push($errors, $row);
            } else {
                $strPrimaryKey = (string) Str::uuid();
                $item = array_merge($element, [
                    'organisasi_id' => $id,
                    'access_code' => generateCodAccess(),
                    "{$this->primaryKey}" => $strPrimaryKey,
                    'is_active' => true,
                    'created_at' => $payload['created_at'],
                    'created_by' => $payload['created_by'],
                ]);
                array_push($temporaries, $item);
                array_push($histories, [
                    "riwayat_id" => (string) Str::uuid(),
                    "{$this->primaryKey}" => $strPrimaryKey,
                    'start_service' => $element['start_service'],
                    'end_service' => $element['end_service'],
                    'created_at' => $payload['created_at'],
                    'created_by' => $payload['created_by'],
                ]);
            }
        }

        if (count($errors) > 0) {
            DB::rollBack();
            return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => 'List module']), $errors);
        }


        if (count($temporaries) <= 0) {
            DB::rollBack();
            return new LaravelResponseContract(false, 400, __('validation.custom.error.organizeModule.assign'), null);
        }

        $result = $this->repository->bulkInsert($temporaries);

        if (!$result) {
            DB::rollBack();
            return new LaravelResponseContract(false, 400, __('validation.custom.error.organizeModule.assign', ['attribute' => 'Data akses modul']), $result);
        }

        if (count($histories) > 0) {
            $historyResult = $this->repository->bulkHistory($histories);

            if (!$historyResult) {
                DB::rollBack();
                return new LaravelResponseContract(false, 400, __('validation.custom.error.organizeModule.create', ['attribute' => 'Data riwayat langganan']), $historyResult);
            }
        }

        DB::commit();
        return new LaravelResponseContract(true, 200, __('validation.custom.success.organizeModule.assign'), $temporaries);
        // try {

        // } catch (Exception $e) {
        //     DB::rollBack();
        //     return sendErrorResponse($e);
        // }
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

    public function changeStatus(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'ID Access Module']), $row);
            }

            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.organizeModule.update'), $result);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.organizeModule.update'), $result);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function updateAccessModule(string $id, mixed $payload): LaravelResponseInterface
    {
        DB::beginTransaction();
        try {
            $temporaries = [];
            $row = $this->repository->findById($id);

            if (!$row) {
                DB::rollBack();
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ['attribute' => 'ID Access Module']), $row);
            }

            if (isset($payload->start_service) && $payload->start_service != $row->start_service) {
                $temporaries['start_service'] = $payload->start_service;
            }

            if (isset($payload->end_service) && $payload->end_service != $row->end_service) {
                $temporaries['end_service'] = $payload->end_service;
            }

            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                DB::rollBack();
                return new LaravelResponseContract(false, 400, __('validation.custom.error.organizeModule.update'), $result);
            }

            if (count($temporaries) > 0) {
                $history = $this->repository->storeHistory(array_merge($temporaries, [
                    "{$this->primaryKey}" => $id,
                    "created_by" => $payload->updated_by
                ]));


                if (!$history) {
                    DB::rollBack();
                    return new LaravelResponseContract(false, 400, __('validation.custom.error.organizeModule.update'), $result);
                }
            }

            DB::commit();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.organizeModule.update'), $result);
        } catch (Exception $e) {
            DB::rollBack();
            return sendErrorResponse($e);
        }
    }
}
