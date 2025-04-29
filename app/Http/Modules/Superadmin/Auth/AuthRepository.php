<?php

namespace App\Http\Modules\Superadmin\Auth;

use App\Models\User;

class AuthRepository
{

    public function findIdentity($identity)
    {
        return  User::where('username', $identity)
            ->whereNull('deleted_at')  // Ensure the user is not soft-deleted
            ->with([
                'role' => function ($query) {
                    $query->select('role_id', 'role_name', 'role_slug');  // Only select relevant role fields
                },
                'client'
            ])
            ->first();
    }
}
