<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionTimeout
{
    /**
     * Session timeout in seconds (30 minutes)
     */
    private int $timeout = 1800;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check timeout for authenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        $now = time();
        $lastActivity = session('last_activity');

        // Check if session has timed out
        if ($lastActivity && ($now - $lastActivity) > $this->timeout) {
            // Log the timeout
            \Log::info('Session timeout', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'inactive_duration' => $now - $lastActivity,
            ]);

            // Log out the user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Your session has expired. Please log in again.');
        }

        // Update last activity timestamp
        session(['last_activity' => $now]);

        return $next($request);
    }
}
