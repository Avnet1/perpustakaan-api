<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use App\Http\Repositories\UserRepository;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $today = Carbon::today();
        $repository = new UserRepository();

        // Check if the user is authenticated (from AuthMiddleware)
        $req = \getUser($request);

        // Validate User On Database
        $user = $repository->findByCondition(["user_id" => $req->user_id]);

        if(!$user) {
            return ResponseHelper::sendResponseJson(false, 400, __('validation.custom.error.auth.unauthorized'), $user->role);
        }

        if($user->license_code == null || $user->license_code == '') {
            return ResponseHelper::sendResponseJson(false, 400, __('validation.custom.error.auth.notApproved'), $user);
        }

        if($today->gt($user->end_active_at)) {
            return ResponseHelper::sendResponseJson(false, 400, __('validation.custom.error.auth.expiredAccount'), $user);
        }

        if($user->role->role_slug != 'superadmin') {
            $parsedHost = parse_url($user->portal_url, PHP_URL_HOST);
            $requestHost = $request->getHost();

            if($parsedHost != $requestHost) {
                return ResponseHelper::sendResponseJson(false, 400, __('validation.custom.error.auth.unauthorized'), $user->role);
            }

            return $next($request);
        }

        return $next($request);
    }
}
