<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session, user preference, or default
        $locale = session('locale') ??
                  (auth()->check() ? auth()->user()->locale : null) ??
                  config('app.locale', 'en');

        // Validate locale
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = config('app.locale', 'en');
        }

        // Set the application locale
        app()->setLocale($locale);

        // Store in session for persistence
        session(['locale' => $locale]);

        return $next($request);
    }
}
