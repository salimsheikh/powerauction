<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckAdminExists;
use App\Http\Middleware\VerifyToken;
use App\Http\Middleware\RoleMiddleware;
//use App\Http\Middleware\CustomThrottleHandler;
use Symfony\Component\HttpFoundation\Response;

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
        // $middleware->append(RoleMiddleware::class);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
           // 'permission2' => \App\Http\Middleware\CustomPermissionMiddleware::class,
        ]);

        

        
        //$middleware->append(CustomThrottleHandler::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return response()->json([                
                'message' => 'You do not have the required permission.',
                'required_permission' => '',
                'errors' => ['category_delete' => [$e->getMessage()]],
            ], Response::HTTP_FORBIDDEN);
        });
    })->create();
