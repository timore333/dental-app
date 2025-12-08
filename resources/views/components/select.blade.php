@props([
    'label' => '',
    'error' => '',
    'options' => [],
])

<div class="mb-4">
    @if($label)
        <label {{ $attributes->only('for')->merge(['class' => 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2']) }}>
            {{ $label }}
        </label>
    @endif

    <select
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2 border rounded-lg ' .
                'bg-white dark:bg-slate-800 ' .
                'text-gray-900 dark:text-white ' .
                'border-gray-300 dark:border-slate-600 ' .
                'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent ' .
                ($error ? 'border-red-500 focus:ring-red-500' : ''),
            'aria-invalid' => $error ? 'true' : 'false',
        ]) }}
    >
        <option value="">{{ __('Select an option') }}</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>

    @if($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
