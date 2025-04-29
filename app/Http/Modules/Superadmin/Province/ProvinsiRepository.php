<?php

namespace App\Http\Modules\Superadmin\Province;

use App\Models\MasterProvinsi;

class ProvinsiRepository
{
    public function insert(mixed $payload)
    {
        return MasterProvinsi::create($payload);
    }

    public function findById(string $id)
    {
        return MasterProvinsi::whereNull('deleted_at')->where('provinsi_id', $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = MasterProvinsi::query()->whereNull('deleted_at');
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
