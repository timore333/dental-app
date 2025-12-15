@props(['title' => ''])
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr"
      class="dark">


@include('partials.head')


<body class="bg-gray-50 dark:bg-gray-800">

@include('partials.nav')

<div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">

    @include('partials.sidebar')

    <div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>

    <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
        <main>

{{$slot}}
        </main>
        @include('partials.footer')



    </div>

</div>

        @livewireScripts
<script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>

<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="https://flowbite-admin-dashboard.vercel.app//app.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/datepicker.min.js"></script>

</body>
</html>
