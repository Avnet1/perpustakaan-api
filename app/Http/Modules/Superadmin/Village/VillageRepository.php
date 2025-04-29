<?php

namespace App\Http\Modules\Superadmin\Village;

use App\Models\MasterKelurahan;
use Illuminate\Support\Facades\DB;

class VillageRepository
{
    public static $primaryKey = 'kelurahan_id';

    public function insert(mixed $payload)
    {
        return MasterKelurahan::create($payload);
    }

    public function findById(string $id)
    {
        return MasterKelurahan::with([
            'kecamatan',
            'provinsi',
            'kabupatenKota'
        ])->whereNull('deleted_at')->where(self::$primaryKey, $id)->first();
    }

    public function checkExisted(string $id, array $where)
    {
        return MasterKelurahan::whereNull('deleted_at')
            ->where($where)
            ->where(self::$primaryKey, '!=', $id)->first();
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
        DB::table('master_kelurahan')
            ->where(self::$primaryKey, $id)
            ->update($payload);
    }
}
