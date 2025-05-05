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
    public function handle(Request $request, Closure $next)
    {
        // CORS headers
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', '*'); // Ganti '*' dengan domain yang diizinkan, atau biarkan '*' untuk menerima semua
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS'); // Tentukan metode HTTP yang diizinkan
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Authorization, Origin, Accept'); // Tentukan headers yang diizinkan
        $response->headers->set('Access-Control-Allow-Credentials', 'true'); // Jika kamu ingin mengizinkan kredensial

        // Jika request adalah preflight (OPTIONS), kembalikan respons kosong 200
        if ($request->getMethod() == "OPTIONS") {
            return response()->json([], 200);
        }

        return $response;
    }
}
