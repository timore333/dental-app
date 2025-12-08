@props([
    'label' => '',
    'error' => '',
    'icon' => '',
    'type' => 'text',
])

<div class="mb-4">
    @if($label)
        <label {{ $attributes->only('for')->merge(['class' => 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2']) }}>
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 {{ session('locale') === 'ar' ? 'right' : 'left' }}-0 pl-3 pr-3 flex items-center pointer-events-none">
                <span class="text-gray-500 dark:text-gray-400">{{ $icon }}</span>
            </div>
        @endif

        <input
            type="{{ $type }}"
            {{ $attributes->merge([
                'class' => 'w-full px-4 py-2 border rounded-lg ' .
                    'bg-white dark:bg-slate-800 ' .
                    'text-gray-900 dark:text-white ' .
                    'border-gray-300 dark:border-slate-600 ' .
                    'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent ' .
                    'placeholder-gray-400 dark:placeholder-gray-500 ' .
                    ($error ? 'border-red-500 focus:ring-red-500' : ''),
                'aria-invalid' => $error ? 'true' : 'false',
                'aria-describedby' => $error ? $attributes->get('id') . '-error' : null,
            ]) }}
        />
    </div>

    @if($error)
        <p id="{{ $attributes->get('id') }}-error" class="mt-1 text-sm text-red-600 dark:text-red-400">
            {{ $error }}
        </p>
    @endif
</div>
