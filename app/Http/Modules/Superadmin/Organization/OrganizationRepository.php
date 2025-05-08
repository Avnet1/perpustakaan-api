<?php

namespace App\Http\Modules\Superadmin\Organization;

use App\Models\MasterOrganisasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrganizationRepository
{
    private $primaryKey;
    private $tableName;

    public function __construct()
    {
        $this->primaryKey = MasterOrganisasi::getPrimaryKeyName();
        $this->tableName = MasterOrganisasi::getTableName();
    }

    public function getQuery()
    {
        return MasterOrganisasi::whereNull('deleted_at');
    }

    public function getLasOrder()
    {
        return MasterOrganisasi::orderBy('order_number', 'desc')->first();
    }

    public function insert(array $payload)
    {
        return MasterOrganisasi::store($payload);
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

        return MasterOrganisasi::whereNull('deleted_at')->where("{$this->primaryKey}", $id)->first();
    }

    public function findById(string $id)
    {

        return MasterOrganisasi::with([
            'moduleAccesses' => function ($q) {
                $q->with([
                    'module' => function ($rq) {
                        $url = asset('storage');
                        $rq->selectRaw("*, (case when icon is null then null else CONCAT('$url/', icon) end) as icon");
                    }
                ]);
            }
        ])->whereNull('deleted_at')->where("{$this->primaryKey}", $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = MasterOrganisasi::whereNull('deleted_at');
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
