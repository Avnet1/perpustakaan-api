<?php

namespace App\Http\Modules\Superadmin\Region;

use App\Models\MasterKabupatenKota;
use Illuminate\Support\Facades\DB;

class RegionRepository
{
    public static $primaryKey = 'kabupaten_kota_id';

    public function insert(mixed $payload)
    {
        return MasterKabupatenKota::create($payload);
    }

    public function findById(string $id)
    {
        return MasterKabupatenKota::with(['provinsi'])->whereNull('deleted_at')->where(self::$primaryKey, $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = MasterKabupatenKota::with([
            'provinsi' => function ($query) {
                $query->select(
                    'provinsi_id',
                    'nama_provinsi',
                    'kode_provinsi',
                    'kode_dikti',
                );
            }
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

    public function checkExisted(string $id, mixed $where)
    {
        return MasterKabupatenKota::whereNull('deleted_at')
            ->where('provinsi_id', $where->provinsi_id)
            ->where('kode_kabupaten_kota', $where->kode_kabupaten_kota)
            ->where(self::$primaryKey, '!=', $id)->first();
    }

    public function delete(string $id, array $payload)
    {
        DB::table('master_kabupaten_kota')
            ->where(self::$primaryKey, $id)
            ->update($payload);
    }
}
