<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold">{{ __('import_export.import_title') }}</h2>
        <button @click="show = false" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Success Message -->
    @if ($successMessage)
        <div class="p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
            <i class="fas fa-check-circle mr-2"></i>
            {{ $successMessage }}
        </div>
    @endif

    <!-- Error Messages -->
    @foreach ($errorMessages as $error)
        <div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ $error }}
        </div>
    @endforeach

    <!-- File Upload -->
    <div>
        <label class="block text-sm font-medium mb-2">{{ __('import_export.select_file') }}</label>
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition"
             @click="$refs.fileInput.click()">
            <input type="file"
                   wire:model="importFile"
                   accept=".xlsx,.xls,.csv"
                   @click.stop
                   @change="$refs.fileInput.value = ''"
                   #fileInput
                   hidden
            />
            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
            <p class="text-gray-600">{{ __('import_export.drag_drop_or_click') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ __('import_export.max_5mb') }}</p>
        </div>
        @error('importFile')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Import Mode -->
    <div>
        <label class="block text-sm font-medium mb-2">{{ __('import_export.import_mode') }}</label>
        <select wire:model="importMode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="create-new">{{ __('import_export.mode_create_new') }}</option>
            <option value="update-existing">{{ __('import_export.mode_update_existing') }}</option>
            <option value="skip-duplicates">{{ __('import_export.mode_skip_duplicates') }}</option>
        </select>
    </div>

    <!-- Skip Duplicates -->
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" wire:model="skipDuplicates" class="rounded" />
        <span class="text-sm">{{ __('import_export.skip_duplicates') }}</span>
    </label>

    <!-- Results -->
    @if ($importResults)
        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm space-y-1">
            <p><strong>{{ __('import_export.created') }}:</strong> {{ $importResults['created'] ?? 0 }}</p>
            <p><strong>{{ __('import_export.updated') }}:</strong> {{ $importResults['updated'] ?? 0 }}</p>
            <p><strong>{{ __('import_export.skipped') }}:</strong> {{ $importResults['skipped'] ?? 0 }}</p>
        </div>
    @endif

    <!-- Actions -->
    <div class="flex gap-2 pt-4">
        <button @click="show = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            {{ __('common.close') }}
        </button>
        <button wire:click="import"
                wire:loading.attr="disabled"
                :disabled="isProcessing"
                class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 disabled:opacity-50 transition">
            <span wire:loading.remove>{{ __('import_export.import_now') }}</span>
            <span wire:loading>
                <i class="fas fa-spinner fa-spin mr-2"></i>
                {{ __('common.processing') }}
            </span>
        </button>
    </div>
</div>
