<?php

namespace App\Http\Modules\Superadmin\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthRepository
{

    public function findIdentity($where)
    {
        return  User::where($where)
            ->whereNull('deleted_at')  // Ensure the user is not soft-deleted
            ->with([
                'role' => function ($query) {
                    $query->select('role_id', 'role_name', 'role_slug');  // Only select relevant role fields
                }
            ])
            ->first();
    }

    public function findById($id)
    {
        return User::with(['role', 'client'])->whereNull('deleted_at')
            ->where(["user_id", $id])
            ->first();
    }

    public function insertOtp($payload)
    {
        return DB::table('password_resets')->insert($payload);
    }

    public function getOtp($condition)
    {
        return DB::table('password_resets')
            ->where($condition)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function deleteOtp($condition)
    {
        DB::table('password_resets')
            ->where($condition)
            ->delete();
    }

    public function verifiedOtp($condition, $payload)
    {
        return DB::table('password_resets')
            ->where($condition)
            ->update($payload);
    }
}
