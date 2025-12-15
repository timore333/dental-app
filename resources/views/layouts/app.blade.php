@props(['title' => ''])
    <!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
      class="">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dental Clinic') }} - {{ $title ?? 'Dashboard' }}</title>

    <!-- Soft UI CSS -->
    <link rel="stylesheet" href="{{ asset('soft-ui/css/soft-ui.css') }}">

    <!-- Custom Overrides -->
    {{--    <link rel="stylesheet" href="{{ asset('css/theme-overrides.css') }}">--}}

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @livewireStyles
</head>


<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

<!-- Navigation -->
@include('partials.soft.nav')

<div class="flex">

    @include('partials.soft.sidebar')
    {{--    <div class="hidden fixed inset-0 z-10 bg-gray-900 opacity-50" id="sidebarBackdrop"></div>--}}
    <div class="hidden fixed inset-0 z-10 bg-gray-900 opacity-50" id="sidebarBackdrop"></div>
    <div id="main-content"
         class="h-full w-full bg-gray-50 relative overflow-y-auto  {{ app()->isLocale('ar') ? 'lg:mr-64' : 'lg:ml-64' }} ">

        <main class="ltr:ml-64 rtl:mr-64 flex-1">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
            {{--@include('partials.soft.original-dashboard-content')--}}

        </main>
        @include('partials.soft.footer')


    </div>

</div>


<script src="{{ asset('soft-ui/js/soft-ui.bundle.js') }}"></script>

@livewireScripts

</body>
</html>
