

@props(['label' => null, 'options' => [], 'error' => null, 'hint' => null])

<div class="form-group" @click.away="open = false">
    @if ($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
            @if ($attributes->has('required'))
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div x-data="{ open: false }" class="absolute">
        <select {{ $attributes->merge(['class' => 'w-full px-3 py-2 border rounded-lg
                   bg-white dark:bg-gray-800
                   border-gray-300 dark:border-gray-600
                   text-gray-900 dark:text-white
                   focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400
                   focus:border-transparent
                   transition-colors duration-200
                   appearance-none
                   ' . ($error ? 'border-red-500 dark:border-red-500' : '')]) }}>
            <option value="" @click="selected = 'value'; open = false">{{ __('Select an option') }}</option>
            @foreach ($options as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>

        <!-- Custom dropdown arrow -->
{{--        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2--}}
{{--                    text-gray-700 dark:text-gray-300">--}}
{{--            <i class="fas fa-chevron-down"></i>--}}
{{--        </div>--}}
    </div>

    @if ($error)
        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $error }}</p>
    @endif

    @if ($hint)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>
