<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dental Clinic') }} - {{ $title ?? 'Dashboard' }}</title>



    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <link rel="stylesheet" href="{{ asset('soft-ui/css/soft-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('soft-ui/css/custom.css') }}">
    <!-- Soft UI CSS -->


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @livewireStyles
</head>
