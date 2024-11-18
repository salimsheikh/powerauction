<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckAdminExists;
use App\Http\Middleware\VerifyToken;
//use App\Http\Middleware\CustomThrottleHandler;

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
        //$middleware->append(CustomThrottleHandler::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
       
    })->create();
