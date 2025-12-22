<form wire:submit.prevent="save" class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 mt-9">{{ __('Code') }}</label>
        <input
            wire:model="code"
            type="text"
            placeholder="e.g., CONS-001"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        @error('code') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Name') }}</label>
        <input
            wire:model="name"
            type="text"
            placeholder="e.g., Teeth Cleaning"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Category') }}</label>
        <select
            wire:model="category"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
            <option value="">{{ __('Select Category') }}</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}">{{ __($cat) }}</option>
            @endforeach
        </select>
        @error('category') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Default Price (EGP)') }}</label>
        <input
            wire:model="default_price"
            type="number"
            step="0.01"
            min="0"
            placeholder="0.00"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        @error('default_price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Description') }}</label>
        <textarea
            wire:model="description"
            placeholder="Enter procedure description..."
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
        ></textarea>
        @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="flex items-center">
        <input
            wire:model="is_active"
            type="checkbox"
            id="is_active"
            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
        >
        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Active') }}</label>
    </div>

    <div class="flex gap-3 pt-4">
        <button
            type="submit"
            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors"
        >
            {{ __('Create Procedure') }}
        </button>
        <button
            type="button"
            @click="$wire.dispatch('close')"
            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
        >
            {{ __('Cancel') }}
        </button>
    </div>
</form>
