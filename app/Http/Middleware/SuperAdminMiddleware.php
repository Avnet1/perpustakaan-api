<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated (from AuthMiddleware)
        $user = \getUser($request);

        // Validate User On Database
        $row = User::where('user_id', $user->user_id)
                    ->whereNull('deleted_at')
                    ->with(['role' => function($query) {
                        $query->select('role_id', 'role_name', 'role_slug');
                    }])
                    ->first();

        if(!$row) {
            return ResponseHelper::sendResponseJson(false, 400, __('validation.error.auth.unauthorized'), $user->role);

        }

        // Check Role User
        if(!in_array($row->role->role_slug, ['superadmin'])) {
            return ResponseHelper::sendResponseJson(false, 400, __('validation.error.auth.unauthorized'), $user->role);
        }

        return $next($request);
    }
}
