<?php

namespace App\Http\Modules\Superadmin\Identity;

use App\Models\MasterIdentitas;
use Illuminate\Support\Facades\DB;

class IdentityRepository
{
    public static $primaryKey = 'identitas_id';
    public static $tableName = 'master_identitas';

    public function insert(mixed $payload)
    {
        return MasterIdentitas::create($payload);
    }

    public function findById(string $id)
    {
        return MasterIdentitas::whereNull('deleted_at')->where(self::$primaryKey, $id)->first();
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
            ->where(self::$primaryKey, '!=', $id)->first();
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
        DB::table(self::$tableName)
            ->where(self::$primaryKey, $id)
            ->update($payload);
    }
}
