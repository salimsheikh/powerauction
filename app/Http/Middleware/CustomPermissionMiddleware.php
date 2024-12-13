<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomPermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        if (!auth()->user()->can($permission)) {
            return response()->json([                
                'message' => 'You do not have the required permission.',
                'required_permission' => $permission,
                'errors' => ['error'=>['You do not have the required permission.']]
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
