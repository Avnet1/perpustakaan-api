<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class HandleCors
{
    public function handle(Request $request, Closure $next): Response
    {
        // Mendapatkan Origin dari header request
        $origin = $request->headers->get('Origin') ?? '*';

        // Cek apakah request adalah preflight (OPTIONS)
        if ($request->getMethod() === 'OPTIONS') {
            return response()->noContent(204)  // Kembalikan response 204 untuk preflight
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With')
                ->header('Access-Control-Allow-Credentials', 'true')  // Membolehkan kredensial (cookies, session)
                ->header('Access-Control-Max-Age', 3600);  // Durasi cache preflight request
        }

        // Lanjutkan request ke middleware berikutnya
        $response = $next($request);

        // Menambahkan header CORS pada response
        $response->headers->set('Access-Control-Allow-Origin', $origin);
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');  // Membolehkan kredensial

        return $response;
    }
}
