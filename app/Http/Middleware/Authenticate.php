<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Get the locale from header or session
        $locale = $request->header('Accept-Language') ?? session('locale', 'en');

        // Set the locale in session for persistence
        session(['locale' => $locale]);
        app()->setLocale($locale);

        return route('login');
    }
}
