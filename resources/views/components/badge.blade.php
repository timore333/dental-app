@props([
    'variant' => 'default',
])

@php
    $variantClasses = match($variant) {
        'success' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
        'error' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
        'warning' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
        'info' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
        default => 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300',
    };
@endphp

<span class="inline-block px-3 py-1 rounded-full text-sm font-medium {{ $variantClasses }}">
    {{ $slot }}
</span>
