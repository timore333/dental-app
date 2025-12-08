@props([
    'header' => '',
    'footer' => '',
])

<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md border border-gray-200 dark:border-slate-700 overflow-hidden">
    @if($header)
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            {{ $header }}
        </div>
    @endif

    <div class="px-6 py-4">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900">
            {{ $footer }}
        </div>
    @endif
</div>
