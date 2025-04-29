<?php

namespace App\Http\Modules\Superadmin\Grade;

use App\Models\MasterJenjang;
use Illuminate\Support\Facades\DB;

class GradeRepository
{
    public static $primaryKey = 'jenjang_id';

    public function insert(mixed $payload)
    {
        return MasterJenjang::create($payload);
    }

    public function findById(string $id)
    {
        return MasterJenjang::whereNull('deleted_at')->where(self::$primaryKey, $id)->first();
    }

    public function checkExisted(string $id, array $where)
    {
        return MasterJenjang::whereNull('deleted_at')
            ->where($where)
            ->where(self::$primaryKey, '!=', $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = MasterJenjang::whereNull('deleted_at');

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
        DB::table('master_jenjang')
            ->where(self::$primaryKey, $id)
            ->update($payload);
    }
}
