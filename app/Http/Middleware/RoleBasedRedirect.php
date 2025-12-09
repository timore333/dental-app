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

            // ✅ Load role relationship if not loaded
            if (!$user->role) {
                $user->load('role');
            }

            $roleName = $user->role?->name;

            \Log::debug('Dashboard redirect', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $roleName,
            ]);

            return match($roleName) {
                'Admin' => redirect()->route('admin.dashboard'),
                'Doctor' => redirect()->route('doctor.dashboard'),
                'Receptionist' => redirect()->route('receptionist.dashboard'),
                'Accountant' => redirect()->route('accountant.dashboard'),
                default => redirect()->route('profile.edit'),
            };
        }

        return $next($request);
    }
}
