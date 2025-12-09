<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Store the current user state before request
        $wasAuthenticated = Auth::check();
        $previousUserId = $wasAuthenticated ? Auth::id() : null;
        $previousUserEmail = $wasAuthenticated ? Auth::user()->email : null;
        $previousUserRole = $wasAuthenticated ? Auth::user()->role?->name : null;

        // Execute the request
        $response = $next($request);

        // Check if user just logged in
        if (!$wasAuthenticated && Auth::check()) {
            $user = Auth::user();
            // Load role if not loaded
            if (!$user->role) {
                $user->load('role');
            }

            \Log::info('User login', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role?->name,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
        }

        // Check if user just logged out
        if ($wasAuthenticated && !Auth::check()) {
            \Log::info('User logout', [
                'user_id' => $previousUserId,
                'user_email' => $previousUserEmail,
                'user_role' => $previousUserRole,
                'ip_address' => $request->ip(),
                'timestamp' => now(),
            ]);
        }

        // Log all authenticated requests (optional - can be verbose)
        if (Auth::check() && $this->shouldLogRoute($request)) {
            \Log::debug('User activity', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'method' => $request->method(),
                'path' => $request->path(),
                'status' => $response->status(),
                'ip_address' => $request->ip(),
                'timestamp' => now(),
            ]);
        }

        return $response;
    }

    /**
     * Determine if the route should be logged
     */
    private function shouldLogRoute(Request $request): bool
    {
        // Don't log certain routes to avoid excessive logging
        $excludedRoutes = [
            'set-locale',
            'debug',
            'sanctum/csrf-cookie',
        ];

        foreach ($excludedRoutes as $route) {
            if ($request->is($route) || $request->is($route . '*')) {
                return false;
            }
        }

        return true;
    }
}
