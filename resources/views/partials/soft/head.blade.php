<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name', 'Dental Center') }}</title>

    <!-- Fonts -->

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    />

    <!-- Styles -->


       @vite(['resources/css/app.css', 'resources/js/app.js'])


        <link
          rel="stylesheet"
          href="https://demos.creative-tim.com/soft-ui-flowbite-pro/nucleo-icons.css"
        />
        <link
          rel="stylesheet"
          href="https://demos.creative-tim.com/soft-ui-flowbite-pro/nucleo-svg.css"
        />


    <link
        rel="stylesheet"
        href="https://demos.creative-tim.com/soft-ui-flowbite-pro/app.css"
    />



</head>
