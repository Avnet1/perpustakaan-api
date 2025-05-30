<?php

use App\Http\Middleware\ForceHttps;
use App\Http\Middleware\GlobalAuthMiddleware;
use App\Http\Middleware\ValidateAuthTokenMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// use Illuminate\Http\Middleware\HandleCors;

use Illuminate\Http\Middleware\HandleCors as DefaultHandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->append(HandleCors::class);
        // $middleware->append(ForceHttps::class);
        $middleware->append(DefaultHandleCors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
