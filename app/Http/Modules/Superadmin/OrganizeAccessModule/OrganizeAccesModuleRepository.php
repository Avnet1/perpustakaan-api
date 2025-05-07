<?php

namespace App\Http\Modules\Superadmin\OrganizeAccessModule;

use App\Models\OrganizationModuleAccess;
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

    public function insert(array $payload)
    {
        return OrganizationModuleAccess::store($payload);
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

        return OrganizationModuleAccess::where("{$this->primaryKey}", $id)->first();
    }

    public function findById(string $id)
    {
        return OrganizationModuleAccess::whereNull('deleted_at')->where('organisasi_id', $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = OrganizationModuleAccess::query()->whereNull('deleted_at');
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

    public function autoCreateDB(string $dbUser, string $dbPass, string $dbName)
    {
        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS {$dbName}");
            DB::statement("CREATE ROLE {$dbUser} LOGIN PASSWORD '{$dbPass}'");

            Log::info("Database Tenant dengan User: {$dbUser}; Password: {$dbPass} dan Name: {$dbName} berhasil ditambahkan");

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
