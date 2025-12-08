<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply redirect logic to /dashboard route
        if (auth()->check() && $request->path() === 'dashboard') {
            $user = auth()->user();
            $role = $user->role;

            \Log::debug('Dashboard redirect', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $role,
            ]);

            return match($role) {
                'admin' => redirect()->route('admin.dashboard'),
                'doctor' => redirect()->route('doctor.dashboard'),
                'receptionist' => redirect()->route('receptionist.dashboard'),
                'accountant' => redirect()->route('accountant.dashboard'),
                default => redirect()->route('profile.edit'),
            };
        }

        return $next($request);
    }
}
