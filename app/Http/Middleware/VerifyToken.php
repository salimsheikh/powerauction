<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $path = $request->path();

        if (!str_contains($path, 'api/backend/')) {
            return $next($request);
        }

        // Get the token from the Authorization header
        $token = $request->bearerToken();        

        // Check if the token is present
        if (!$token) {
            return response()->json([
                    'success' => false,
                    'message' => __('Token Missing.'),
                    'errors' => [
                        'token_error ' => [__('No token provided in the request.')]
                    ]
            ], Response::HTTP_BAD_REQUEST); // 400 Bad Request
        }

        // Attempt to authenticate the user using the token
        try {
            // Using Sanctum's built-in authentication mechanism
            if (!Auth::guard('sanctum')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Invalid Token.'),
                    'errors' => [
                        'token_error' => [__('The provided token is invalid or expired.')]
                    ]
                ], Response::HTTP_UNAUTHORIZED); // 401 Unauthorized
            }
        } catch (\Exception $e) {
            // Handle unexpected errors, such as expired tokens
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => [
                    'token_error' => [__('Token Error')]
                ],                
            ], Response::HTTP_UNAUTHORIZED); // 401 Unauthorized
        }

        // Proceed to the next middleware or controller
        return $next($request);
    }
}
