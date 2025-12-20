@props(['title' => ''])
    <!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
      class="">

@include('partials.head')


<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

<!-- Navigation -->
@include('partials.nav')

<div class="flex">

    @include('partials.sidebar')
    {{--    <div class="hidden fixed inset-0 z-10 bg-gray-900 opacity-50" id="sidebarBackdrop"></div>--}}
    <div class="hidden fixed inset-0 z-10 bg-gray-900 opacity-50" id="sidebarBackdrop"></div>
    <div id="main-content"
         class="h-full w-full bg-gray-50 relative overflow-y-auto  {{ app()->isLocale('ar') ? 'lg:mr-16' : 'lg:ml-16' }} ">
   <x-notifications />
        <main class="ltr:ml-64 rtl:mr-64 flex-1">

            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset

        </main>
        @include('partials.footer')


    </div>

</div>

<script src="{{ asset('soft-ui/js/soft-ui.bundle.js') }}"></script>

@stack('scripts')
<!-- Livewire Modal -->
<livewire:modal/>

@livewireScripts

</body>
</html>
