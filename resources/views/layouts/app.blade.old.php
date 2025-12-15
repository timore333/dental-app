<!doctype html>
<html lang="en">
@include('layouts.partials.doft.head')
<body class="bg-gray-50">

<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-THQTXJ7"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>


@include('layouts.partisals.soft.nav')
<div class="flex overflow-hidden bg-white pt-16">

    @include('layouts.partials.soft.sidebar)

    <div class="hidden fixed inset-0 z-10 bg-gray-900 opacity-50" id="sidebarBackdrop"></div>

    <div id="main-content" class="h-full w-full bg-gray-50 relative overflow-y-auto lg:ml-64">
        <main>
            {{$slot}}
        </main>
        @include('layouts.partials.soft.footer')


    </div>

</div>


<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="https://demos.creative-tim.com/soft-ui-flowbite-pro/app.bundle.js"></script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon='{"version":"2024.11.0","token":"1b7cbb72744b40c580f8633c6b62637e","server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}'
        crossorigin="anonymous"></script>
</body>
</html>
