<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority:
        // 1. Session locale (user just switched)
        // 2. User's saved preference (from users.locale column)
        // 3. App default locale

        $locale = session('locale');

        if (!$locale && auth()->check()) {
            // Try to get from user's preference
            $locale = auth()->user()->locale ?? config('app.locale', 'en');
        }

        if (!$locale) {
            $locale = config('app.locale', 'en');
        }

        // Validate locale against supported locales
        $supported = ['en', 'ar'];
        if (!in_array($locale, $supported)) {
            $locale = config('app.locale', 'en');
        }

        // Set the application locale globally
        app()->setLocale($locale);

        // Store in session for persistence during user session
        session(['locale' => $locale]);

        // Update user's preference if changed
        if (auth()->check() && auth()->user()->locale !== $locale) {
            try {
                auth()->user()->update(['locale' => $locale]);
            } catch (\Exception $e) {
                \Log::warning('Failed to update user locale', [
                    'user_id' => auth()->id(),
                    'locale' => $locale,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $next($request);
    }
}
