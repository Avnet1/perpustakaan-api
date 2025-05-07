<?php

namespace App\Http\Modules\Superadmin\OrganizeAccessModule;

use App\Models\OrganizationModuleAccess;
use App\Models\RiwayatLangganan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrganizeAccessModuleRepository
{
    private $primaryKey;
    private $tableName;

    public function __construct()
    {
        $this->primaryKey = OrganizationModuleAccess::getPrimaryKeyName();
        $this->tableName = OrganizationModuleAccess::getTableName();
    }

    public function getQuery()
    {
        return OrganizationModuleAccess::whereNull('deleted_at');
    }

    public function bulkInsert(array $payload)
    {
        return DB::table("{$this->tableName}")->insert($payload);
    }

    public function bulkHistory(array $payload)
    {
        $tableName = RiwayatLangganan::getTableName();
        return DB::table("{$tableName}")->insert($payload);
    }

    public function update(string $id, array $payload)
    {
        $result = DB::table("{$this->tableName}")
            ->whereNull('deleted_at')
            ->where("{$this->primaryKey}", $id)
            ->update($payload);

        if (!$result) {
            return $result;
        }

        return OrganizationModuleAccess::whereNull('deleted_at')->where("{$this->primaryKey}", $id)->first();
    }

    public function findById(string $id)
    {
        $url = asset('storage');
        return OrganizationModuleAccess::with([
            'module' => function ($q) use ($url) {
                $q->selectRaw("*, (case when icon is null then null else CONCAT('$url/', icon) end) as icon");
            },
            'organization' => function ($q) use ($url) {
                $q->selectRaw("*, (case when logo is null then null else CONCAT('$url/', logo) end) as logo");
            },
        ])->whereNull('deleted_at')->where("{$this->primaryKey}", $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = OrganizationModuleAccess::whereNull('deleted_at');
        foreach ($condition as $key => $value) {
            if (is_array($value)) {
                $query->whereIn($key, $value);
            } elseif (is_null($value)) {
                $query->whereNull($key);
            } elseif (strpos($value, '%') !== false) {
                $query->where($key, 'ilike', $value);
            } else {
                $query->where($key, '=', $value);
            }
        }

        return $query->first();
    }

    public function delete(string $id, array $payload)
    {
        DB::table("{$this->tableName}")
            ->whereNull('deleted_at')
            ->where("{$this->primaryKey}", $id)
            ->update($payload);
    }

    public function storeHistory(array $payload)
    {
        return RiwayatLangganan::store($payload);
    }
}
