<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckAdminExists;
use App\Http\Middleware\VerifyToken;


use App\Exceptions\ApiMonitoringException;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Lottery;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(CheckAdminExists::class);
        $middleware->append(VerifyToken::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (InvalidOrderException $e) {
            return response()->json([
                'message' => 'Too Many Attempts.',
                'retry_after' => 0,
            ], 429);
        });
    })->create();
