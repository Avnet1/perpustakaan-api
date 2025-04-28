<?php

namespace App\Http\Modules\Superadmin\Organization;

use App\Models\MasterOrganisasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrganizationRepository
{
    public function insert(mixed $payload)
    {
        return MasterOrganisasi::create($payload);
    }

    public function findById(string $id)
    {
        return MasterOrganisasi::whereNull('deleted_at')->where('organisasi_id', $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = MasterOrganisasi::query()->whereNull('deleted_at');
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

    public function autoCreateDB(string $dbUser, string $dbPass, string $dbName)
    {
        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS {$dbName}");
            DB::statement("CREATE ROLE {$dbUser} LOGIN PASSWORD '{$dbPass}'");

            Log::info("Database Tenant dengan User: {$dbUser}; Password: {$dbPass} dan Name: {$dbName} berhasil ditambahkan");

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
