<?php

namespace App\Http\Modules\Superadmin\Subdistrict;

use App\Models\MasterKecamatan;

class SubdistrictRepository
{
    public static $primaryKey = 'kecamatan_id';

    public function insert(mixed $payload)
    {
        return MasterKecamatan::create($payload);
    }

    public function findById(string $id)
    {
        return MasterKecamatan::with([
            'kabupatenKota',
        ])->whereNull('deleted_at')->where("{$this->primaryKey}", $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = MasterKecamatan::with([
            'kabupatenKota'
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
