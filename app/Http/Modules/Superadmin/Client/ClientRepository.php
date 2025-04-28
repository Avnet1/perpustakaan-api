<?php

namespace App\Http\Modules\Superadmin\Client;

use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientRepository
{
    public function insert(mixed $payload)
    {
        return Client::create($payload);
    }

    public function findById(string $id)
    {
        return Client::whereNull('deleted_at')->where('client_id', $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = Client::query()->whereNull('deleted_at');
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
