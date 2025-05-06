<?php

namespace App\Http\Modules\Superadmin\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthRepository
{
    public function updateUser(string $id, mixed $payload)
    {
        return DB::table('users')
            ->where('user_id', $id)
            ->update($payload);
    }


    public function update(string $id, array $payload)
    {
        $result = DB::table('users')
            ->whereNull('deleted_at')
            ->where('user_id', $id)
            ->update($payload);

        if ($result) {
            return User::where('user_id', $id)->first();
        }

        return $result;
    }



    public function findIdentity(mixed $condition)
    {
        return User::with(['role'])
            ->whereNull('deleted_at')
            ->where($condition)
            ->first();
    }

    public function findById(string $id)
    {
        return User::with(['role'])
            ->whereNull('deleted_at')
            ->where('user_id', $id)
            ->first();
    }

    public function insertOtp(mixed $payload)
    {
        return DB::table('password_resets')->insert($payload);
    }

    public function getOtp(mixed $condition)
    {
        return DB::table('password_resets')
            ->where($condition)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function deleteOtp(mixed $condition)
    {
        DB::table('password_resets')
            ->where($condition)
            ->delete();
    }

    public function verifiedOtp(mixed $condition, mixed $payload)
    {
        return DB::table('password_resets')
            ->where($condition)
            ->update($payload);
    }
}
