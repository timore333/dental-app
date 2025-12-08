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

        // Check if user has any of the required roles
        $userRoles = is_array($user->role) ? $user->role : [$user->role];

        // Check if user has at least one of the required roles
        foreach ($roles as $role) {
            if (in_array($role, $userRoles) || $user->role === $role) {
                return $next($request);
            }
        }

        // User doesn't have the required role
        \Log::warning('Unauthorized role access attempt', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'required_roles' => $roles,
            'path' => $request->path(),
        ]);

        abort(403, 'Unauthorized. You do not have the required role.');
    }
}
