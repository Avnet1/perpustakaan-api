<?php

namespace App\Http\Modules\Superadmin\Village;

use App\Models\MasterKelurahan;
use Illuminate\Support\Facades\DB;

class VillageRepository
{
    private $primaryKey;
    private $tableName;

    public function __construct()
    {
        $this->primaryKey = MasterKelurahan::getPrimaryKeyName();
        $this->tableName = MasterKelurahan::getTableName();
    }


    public function insert(mixed $payload)
    {
        return MasterKelurahan::store($payload);
    }

    public function update(string $id, mixed $payload)
    {
        return DB::table("{$this->tableName}")
            ->where("{$this->primaryKey}", $id)
            ->update($payload);
    }


    public function findById(string $id)
    {
        return MasterKelurahan::with([
            'kecamatan',
            'provinsi',
            'kabupatenKota'
        ])->whereNull('deleted_at')->where("{$this->primaryKey}", $id)->first();
    }

    public function checkExisted(string $id, array $where)
    {
        return MasterKelurahan::whereNull('deleted_at')
            ->where($where)
            ->where("{$this->primaryKey}", '!=', $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = MasterKelurahan::with([
            'kecamatan',
            'provinsi',
            'kabupatenKota'
        ])->whereNull('deleted_at');

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
