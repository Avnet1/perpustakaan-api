<?php

namespace App\Http\Modules\Superadmin\Province;

use App\Models\MasterProvinsi;
use Illuminate\Support\Facades\DB;

class ProvinsiRepository
{
    private $primaryKey;
    private $tableName;

    public function __construct()
    {
        $this->primaryKey = MasterProvinsi::getPrimaryKeyName();
        $this->tableName = MasterProvinsi::getTableName();
    }

    public function insert(mixed $payload)
    {
        return MasterProvinsi::store($payload);
    }

    public function update(string $id, mixed $payload)
    {
        return DB::table("{$this->tableName}")
            ->where("{$this->primaryKey}", $id)
            ->update($payload);
    }

    public function findById(string $id)
    {
        return MasterProvinsi::whereNull('deleted_at')->where("{$this->primaryKey}", $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = MasterProvinsi::whereNull('deleted_at');
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
