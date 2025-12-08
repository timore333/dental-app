@props(['title' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session('locale', 'en') === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name', 'Dental Center') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-b from-blue-50 to-blue-100 dark:from-slate-900 dark:to-slate-800">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">
            <!-- Logo/Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                    {{ config('app.name', 'Dental Center') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('Professional Dental Care Management') }}</p>
            </div>

            <!-- Content Card -->
            <div class="w-full max-w-md">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-8">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                <p>&copy; {{ date('Y') }} Thnaya Dental Center. All rights reserved.</p>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
