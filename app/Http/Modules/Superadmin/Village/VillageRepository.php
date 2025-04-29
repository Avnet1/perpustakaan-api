<?php

namespace App\Http\Modules\Superadmin\Village;

use App\Models\MasterKelurahan;

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
        ])->whereNull('deleted_at')->where("{$this->primaryKey}", $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = MasterKelurahan::with([
            'kecamatan'
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
