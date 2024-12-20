<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckAdminExists;
use App\Http\Middleware\VerifyToken;
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

        $middleware->append(\App\Http\Middleware\ClearCacheMiddleware::class);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {

            // Check if the request is an API call, fetch(), or expects JSON
            if ($request->expectsJson() ||  $request->is('api/*') || str_contains($request->header('Content-Type'), 'application/json')) {

                // Extract the missing permission from the exception message
                $missingPermission = $e->getRequiredPermissions();

                return response()->json([                
                    'message' => 'You do not have the required permission.',
                    'required_permission' => $missingPermission,
                    'errors' => ['permission' => [$e->getMessage()]],
                    'description' => 'JSON coming from bootstrap/app.php',
                ], Response::HTTP_FORBIDDEN);
            }
        });
    })->create();
