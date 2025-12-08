@props([
    'label' => '',
    'checked' => false,
])

<div class="flex items-center">
    @if($label)
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-3">
            {{ $label }}
        </label>
    @endif

    <button
        type="button"
        {{ $attributes->merge([
            'class' => 'relative inline-flex h-6 w-11 items-center rounded-full transition-colors ' .
                ($checked ? 'bg-blue-600' : 'bg-gray-300 dark:bg-gray-600'),
            'role' => 'switch',
            'aria-checked' => $checked ? 'true' : 'false',
        ]) }}
    >
        <span class="{{ $checked ? 'translate-x-6' : 'translate-x-1' }} inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
    </button>
</div>
