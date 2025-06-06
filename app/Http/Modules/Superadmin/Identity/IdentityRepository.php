<?php

namespace App\Http\Modules\Superadmin\Identity;

use App\Models\MasterIdentitas;
use Illuminate\Support\Facades\DB;

class IdentityRepository
{
    private $primaryKey;
    private $tableName;

    public function __construct()
    {
        $this->primaryKey = MasterIdentitas::getPrimaryKeyName();
        $this->tableName = MasterIdentitas::getTableName();
    }

    public function insert(mixed $payload)
    {
        return MasterIdentitas::store($payload);
    }


    public function update(string $id, mixed $payload)
    {
        return DB::table("{$this->tableName}")
            ->where("{$this->primaryKey}", $id)
            ->update($payload);
    }

    public function findById(string $id)
    {
        return MasterIdentitas::whereNull('deleted_at')->where("{$this->primaryKey}", $id)->first();
    }

    public function checkRow()
    {
        $result = MasterIdentitas::whereNull('deleted_at')->count();
        return $result ? $result : 0;
    }

    public function checkExisted(string $id, array $where)
    {
        return MasterIdentitas::whereNull('deleted_at')
            ->where($where)
            ->where("{$this->primaryKey}", '!=', $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = MasterIdentitas::whereNull('deleted_at');

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
            ->where("{$this->primaryKey}", $id)
            ->update($payload);
    }
}
