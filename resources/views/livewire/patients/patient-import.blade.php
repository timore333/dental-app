<div class="p-6 max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('messages.patient.import') }}
            </h2>
            <a href="{{ route('patients.index') }}" class="text-blue-600 hover:text-blue-800">
                {{ __('messages.back') }}
            </a>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-6">
            <div class="p-4 bg-blue-50 dark:bg-blue-900 rounded-md">
                <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">
                    {{ __('messages.patient.import_instructions') }}
                </h3>
                <ul class="list-disc list-inside text-blue-800 dark:text-blue-200 space-y-1 text-sm">
                    <li>{{ __('messages.patient.download_template') }}</li>
                    <li>{{ __('messages.patient.fill_data') }}</li>
                    <li>{{ __('messages.patient.required_fields') }}: name, phone</li>
                    <li>{{ __('messages.patient.upload_file') }}</li>
                </ul>

                <button wire:click="downloadTemplate"
                        class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                    {{ __('messages.patient.download_template') }}
                </button>
            </div>
        </div>

        <form wire:submit.prevent="import">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.patient.select_file') }}
                </label>
                <input type="file"
                       wire:model="file"
                       accept=".xlsx,.xls,.csv"
                       class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                @error('file')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <div wire:loading wire:target="file" class="text-sm text-gray-600 mt-2">
                    {{ __('messages.uploading') }}...
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('patients.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-md">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="import"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md disabled:opacity-50">
                    <span wire:loading.remove wire:target="import">{{ __('messages.import') }}</span>
                    <span wire:loading wire:target="import">{{ __('messages.importing') }}...</span>
                </button>
            </div>
        </form>
    </div>
</div>

