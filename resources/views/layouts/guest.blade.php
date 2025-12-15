@props(['title' => ''])
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name', 'Dental Center') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <link
        rel="canonical"
        href="https://www.creative-tim.com/product/soft-ui-flowbite-pro"
    />

    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    />
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
    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="https://demos.creative-tim.com/soft-ui-flowbite-pro/apple-touch-icon.png"
    />
    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="https://demos.creative-tim.com/soft-ui-flowbite-pro/favicon-32x32.png"
    />
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="https://demos.creative-tim.com/soft-ui-flowbite-pro/favicon-16x16.png"
    />
    <link
        rel="icon"
        type="image/png"
        href="https://demos.creative-tim.com/soft-ui-flowbite-pro/favicon.ico"
    />
    <link
        rel="manifest"
        href="https://demos.creative-tim.com/soft-ui-flowbite-pro/site.webmanifest"
    />
    <link
        rel="mask-icon"
        href="https://demos.creative-tim.com/soft-ui-flowbite-pro/safari-pinned-tab.svg"
        color="#5bbad5"
    />


</head>
<body class="bg-gray-50">


<main class="bg-gray-50">
    <div class="flex flex-col justify-center items-center px-6 pt-8 mx-auto md:h-screen pt:mt-0">
        <a
            href="https://demos.creative-tim.com/soft-ui-flowbite-pro/"
            class="flex justify-center items-center mb-8 text-2xl font-semibold lg:mb-10"
        >

            <span class="self-center text-2xl font-bold whitespace-nowrap"
            >Thanaya Dental</span
            >
        </a>
        <!-- Card -->
        <div class="p-10 w-full max-w-lg bg-white rounded-2xl shadow-xl shadow-gray-300">
            <div class="space-y-8">
                <h2 class="text-2xl font-bold text-gray-900">
                    Sign in to platform
                </h2>

                <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div>
                        <label
                            for="email"
                            class="block mb-2 text-sm font-medium text-gray-900"
                        >{{ __('Email') }}</label
                        >
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                            placeholder="name@company.com"
                            required
                        />
                    </div>

                    <div>
                        <label
                            for="password"
                            class="block mb-2 text-sm font-medium text-gray-900"
                        >{{ __('Password') }}</label
                        >
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="••••••••"
                            class="border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5"
                            required
                        />
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                aria-describedby="remember"
                                class="w-5 h-5 rounded border-gray-300 focus:outline-none focus:ring-0 checked:bg-dark-900"

                            />
                        </div>

                        <div class="ml-3 text-sm">
                            <label for="remember" class="font-medium text-gray-900"
                            > {{ __('Remember me') }}</label
                            >
                        </div>
                        <a
                            href="{{ route('password.request') }}"
                            class="ml-auto text-sm text-fuchsia-600 hover:underline"
                        >{{ __('Forgot your password?') }}</a
                        >
                    </div>

                    <button
                        type="submit"
                        class="py-3 px-5 w-full text-base font-medium text-center text-white bg-gradient-to-br from-pink-500 to-voilet-500 hover:scale-[1.02] shadow-md shadow-gray-300 transition-transform rounded-lg sm:w-auto"
                    >
                        {{ __('Sign In') }}
                    </button>


                </form>


            </div>
        </div>
    </div>
</main>

<script src="https://demos.creative-tim.com/soft-ui-flowbite-pro/app.bundle.js"></script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon='{"version":"2024.11.0","token":"1b7cbb72744b40c580f8633c6b62637e","server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}'
        crossorigin="anonymous"></script>
</body>
</html>
