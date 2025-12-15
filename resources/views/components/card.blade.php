// FILE: resources/views/components/card.blade.php

@props(['title' => null, 'footer' => null])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg
            border border-gray-200 dark:border-gray-700 overflow-hidden">
    @if ($title)
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $title }}
            </h3>
        </div>
    @endif

    <div class="px-6 py-4 text-gray-900 dark:text-gray-100">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700
                    bg-gray-50 dark:bg-gray-900">
            {{ $footer }}
        </div>
    @endif
</div>
