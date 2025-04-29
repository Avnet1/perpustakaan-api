<?php

namespace App\Http\Modules\Superadmin\Region;

use App\Models\MasterKabupatenKota;

class RegionRepository
{
    public static $primaryKey = 'kabupaten_kota_id';

    public function insert(mixed $payload)
    {
        return MasterKabupatenKota::create($payload);
    }

    public function findById(string $id)
    {
        return MasterKabupatenKota::with([
            'provinsi' => function ($query) {
                $query->select(
                    'provinsi_id',
                    'nama_provinsi',
                    'kode_provinsi',
                    'kode_dikti',
                );
            }
        ])->whereNull('deleted_at')->where("{$this->primaryKey}", $id)->first();
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
        ])->query()->whereNull('deleted_at');
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
}
