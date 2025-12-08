@props([
    'type' => 'info',
    'dismissible' => false,
])

@php
    $bgColor = match($type) {
        'success' => 'bg-green-50 dark:bg-green-900/20',
        'error' => 'bg-red-50 dark:bg-red-900/20',
        'warning' => 'bg-yellow-50 dark:bg-yellow-900/20',
        'info' => 'bg-blue-50 dark:bg-blue-900/20',
        default => 'bg-blue-50 dark:bg-blue-900/20',
    };

    $textColor = match($type) {
        'success' => 'text-green-800 dark:text-green-300',
        'error' => 'text-red-800 dark:text-red-300',
        'warning' => 'text-yellow-800 dark:text-yellow-300',
        'info' => 'text-blue-800 dark:text-blue-300',
        default => 'text-blue-800 dark:text-blue-300',
    };

    $borderColor = match($type) {
        'success' => 'border-green-200 dark:border-green-800',
        'error' => 'border-red-200 dark:border-red-800',
        'warning' => 'border-yellow-200 dark:border-yellow-800',
        'info' => 'border-blue-200 dark:border-blue-800',
        default => 'border-blue-200 dark:border-blue-800',
    };
@endphp

<div class="mb-4 p-4 rounded-lg border {{ $bgColor }} {{ $borderColor }} {{ $textColor }}" role="alert">
    <div class="flex justify-between items-start">
        <div>
            {{ $slot }}
        </div>
        @if($dismissible)
            <button type="button" class="text-current opacity-70 hover:opacity-100" onclick="this.parentElement.parentElement.remove()">
                <span class="text-xl">&times;</span>
            </button>
        @endif
    </div>
</div>

