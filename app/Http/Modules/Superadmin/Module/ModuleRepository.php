<?php

namespace App\Http\Modules\Superadmin\Module;

use App\Models\MasterModule;
use Illuminate\Support\Facades\DB;

class ModuleRepository
{
    private $primaryKey;
    private $tableName;

    public function __construct()
    {
        $this->primaryKey = MasterModule::getPrimaryKeyName();
        $this->tableName = MasterModule::getTableName();
    }

    public function insert(mixed $payload)
    {
        return MasterModule::store($payload);
    }

    public function update(string $id, mixed $payload)
    {
        $result =  DB::table("{$this->tableName}")
            ->whereNull('deleted_at')
            ->where("{$this->primaryKey}", $id)
            ->update($payload);

        if (!$result) {
            return $result;
        }

        return MasterModule::where("{$this->primaryKey}", $id)->first();
    }

    public function findById(string $id)
    {
        return MasterModule::whereNull('deleted_at')
            ->where("{$this->primaryKey}", $id)
            ->first();
    }

    public function getQuery()
    {
        return MasterModule::whereNull('deleted_at');
    }


    public function findByCondition(mixed $condition)
    {
        $query = MasterModule::whereNull('deleted_at');

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
}
