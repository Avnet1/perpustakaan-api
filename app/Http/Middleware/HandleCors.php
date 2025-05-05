<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HandleCors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $origin = $request->headers->get('Origin');

        $response->headers->set('Access-Control-Allow-Origin', $origin ?? '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        // Khusus untuk OPTIONS
        if ($request->getMethod() === 'OPTIONS') {
            return response()->json('{"method":"OPTIONS"}', 200, $response->headers->all());
        }

        return $response;
    }
}
