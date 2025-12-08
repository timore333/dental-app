<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     */
    public function switch(Request $request)
    {
        $language = $request->validate([
            'language' => 'required|in:en,ar',
        ])['language'];

        session(['locale' => $language]);

        return back()->with('success', 'Language switched successfully.');
    }
}
