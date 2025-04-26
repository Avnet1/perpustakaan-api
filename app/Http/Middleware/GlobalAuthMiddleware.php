<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GlobalAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated (from AuthMiddleware)
        $user = $request->attributes->get('user');

        if(!in_array($user->role->role_slug, ['admin', 'member'])) {
            return ResponseHelper::sendResponseJson(false, 400, __('validation.error.auth.unauthorized'), $user->role);
        }

        $request->user = $user;

        return $next($request);
    }
}
