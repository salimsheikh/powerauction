<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckAdminExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        // Check if the current route is not 'setup-wizard'
        if (($path == 'login' || $path == 'register') && $path !== 'setup-wizard') {
            // Check if an admin user exists
            if (!User::where('role', 'administrator')->exists()) {
                // Redirect to the setup wizard if no admin is found
                return redirect('/setup-wizard');
            }
        }

        return $next($request);
    }
}
