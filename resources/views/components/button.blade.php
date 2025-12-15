// FILE: resources/views/components/button.blade.php

@props(['type' => 'primary', 'size' => 'md', 'disabled' => false])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800';

    $typeClasses = match($type) {
        'primary' => 'bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-800 text-white dark:text-white focus:ring-blue-500',
        'secondary' => 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white focus:ring-gray-500',
        'danger' => 'bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-800 text-white dark:text-white focus:ring-red-500',
        'success' => 'bg-green-600 dark:bg-green-700 hover:bg-green-700 dark:hover:bg-green-800 text-white dark:text-white focus:ring-green-500',
        default => 'bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white focus:ring-gray-500',
    };

    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
        default => 'px-4 py-2 text-base',
    };

    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';
@endphp

<button {{ $attributes->merge(['class' => "$baseClasses $typeClasses $sizeClasses $disabledClasses"]) }}
        {{ $disabled ? 'disabled' : '' }}>
    {{ $slot }}
</button>
