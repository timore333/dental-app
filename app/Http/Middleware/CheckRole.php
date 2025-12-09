<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Make sure role relationship is loaded
        if (!$user->role) {
            \Log::error('User role not loaded', [
                'user_id' => $user->id,
                'user_email' => $user->email,
            ]);
            abort(403, 'User role configuration error. Contact administrator.');
        }

        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // User doesn't have the required role
        \Log::warning('Unauthorized role access attempt', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role?->name,
            'required_roles' => $roles,
            'path' => $request->path(),
            'timestamp' => now(),
        ]);

        abort(403, 'Unauthorized. You do not have the required role.');
    }
}
