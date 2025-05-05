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
        $origin = $request->headers->get('Origin') ?? '*';

        // Jika preflight (OPTIONS), langsung return response dengan header
        if ($request->getMethod() === 'OPTIONS') {
            return response()->noContent(204)
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        $response = $next($request);

        // Tambahkan header CORS ke semua response lainnya
        $response->headers->set('Access-Control-Allow-Origin', $origin);
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}
