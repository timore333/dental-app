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
    <body class="font-sans antialiased bg-gray-50 dark:bg-slate-900">
        <div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-slate-900">
            <!-- Sidebar -->
            @include('partials.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Navbar -->
                @include('partials.navbar')

                <!-- Page Content -->
                <main class="flex-1 overflow-auto bg-gray-50 dark:bg-slate-900 px-4 py-6 sm:px-6 lg:px-8">
                     @yield('content')
                </main>

                <!-- Footer -->
                @include('partials.footer')
            </div>
        </div>

        @livewireScripts
    </body>
</html>
