<!-- Category -->
<div class="mb-4">
    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
        Category
    </label>
    <select wire:model.live="filterCategory"
            class="border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-auto min-w-[200px] p-2.5">
        <option value="all">All Categories</option>
        @foreach ($categories as $key => $label)
            <option value="{{ $key }}">{{ __(ucfirst($label)) }}</option>
        @endforeach
    </select>
</div>


<!-- Gender -->
<div class="mb-4">
    <label for="settings-language" class="block mb-2 text-sm font-medium text-gray-900">{{__('Gender')}} </label>
    <select wire:model.live="filterGender"
            class="border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-auto min-w-[200px] p-2.5">
        @foreach ($genders as $key => $label)
            <option value="{{ $key }}">{{ __(ucfirst($label)) }}</option>
        @endforeach
    </select>
</div>

<!-- Payment Type -->
<div class="mb-4">
    <label for="settings-language" class="block mb-2 text-sm font-medium text-gray-900">{{__('Payment type')}} </label>
    <select wire:model.live="filterType"
            class="border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-auto min-w-[200px] p-2.5">
        @foreach ($paymentTypes as $key => $label)
            <option value="{{ $key }}">{{ __(ucfirst($label)) }}</option>
        @endforeach
    </select>
</div>





<!-- Reset Button -->
<div class="mt-4 flex justify-end">
    <button wire:click="resetFilters"
            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-slate-600 dark:hover:bg-slate-700 text-gray-900 dark:text-white font-semibold rounded-lg transition-colors duration-200">
        Reset Filters
    </button>
</div>
