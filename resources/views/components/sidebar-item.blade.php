@props([
    'route' => '',
    'icon' => '',
    'label' => '',
])

@php
    $isActive = request()->routeIs($route);
@endphp

<li>
    <a
        href="{{ route($route) }}"
        {{ $attributes->merge([
            'class' => 'flex items-center px-6 py-3 transition-colors ' .
                ($isActive
                    ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-l-4 border-blue-600'
                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 border-l-4 border-transparent'),
        ]) }}
    >
        @if($icon)
            <span class="mr-3">{{ $icon }}</span>
        @endif
        <span>{{ $label }}</span>
    </a>
</li>
