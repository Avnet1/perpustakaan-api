<?php

namespace App\Http\Repositories;

use App\Http\Contracts\LaravelResponse;
use Illuminate\Http\Request;
use App\Http\Interfaces\I_ResponseInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthRepository {

    public function findIdentity($identity) {
        return  User::where('identity_code', $identity)
            ->whereNull('deleted_at')  // Ensure the user is not soft-deleted
            ->with(['role' => function($query) {
                $query->select('role_id', 'role_name', 'role_slug');  // Only select relevant role fields
            }])
            ->first();
    }

}
