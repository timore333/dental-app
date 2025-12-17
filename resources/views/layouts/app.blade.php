@props(['title' => ''])
    <!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
      class="">

@include('partials.soft.head')


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
