<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Check if user has any of the required permissions
        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }

        // User doesn't have the required permission
        \Log::warning('Unauthorized permission access attempt', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'required_permissions' => $permissions,
            'path' => $request->path(),
        ]);

        abort(403, 'Unauthorized. You do not have the required permission.');
    }
}
