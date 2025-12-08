@props([
    'items' => [],
])

<nav class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 mb-6" aria-label="Breadcrumb">
    @foreach($items as $item)
        @if($loop->last)
            <span class="text-gray-900 dark:text-white font-medium">{{ $item['label'] }}</span>
        @else
            <a href="{{ $item['url'] }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                {{ $item['label'] }}
            </a>
            <span>/</span>
        @endif
    @endforeach
</nav>
