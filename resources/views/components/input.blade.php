@props(['type' => 'text', 'label' => null, 'error' => null, 'hint' => null])

<div class="form-group">
    @if ($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
            @if ($attributes->has('required'))
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <input type="{{ $type }}"
           {{ $attributes->merge(['class' => 'w-full px-3 py-2 border rounded-lg
                  bg-white dark:bg-gray-800
                  border-gray-300 dark:border-gray-600
                  text-gray-900 dark:text-white
                  placeholder-gray-500 dark:placeholder-gray-400
                  focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400
                  focus:border-transparent
                  transition-colors duration-200
                  ' . ($error ? 'border-red-500 dark:border-red-500' : '')]) }} />

    @if ($error)
        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $error }}</p>
    @endif

    @if ($hint)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>
