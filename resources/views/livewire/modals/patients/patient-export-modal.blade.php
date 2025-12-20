<div class="space-y-4 max-h-96 overflow-y-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold">{{ __('import_export.export_title') }}</h2>
        <button @click="show = false" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Error Messages -->
    @foreach ($errorMessages as $error)
        <div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ $error }}
        </div>
    @endforeach

    <!-- Format Selection -->
    <div>
        <label class="block text-sm font-medium mb-2">{{ __('import_export.format') }}</label>
        <select wire:model.live="exportFormat" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="excel">{{ __('import_export.format_excel') }}</option>
            <option value="pdf">{{ __('import_export.format_pdf') }}</option>
            <option value="sql">{{ __('import_export.format_sql') }}</option>
        </select>
    </div>

    <!-- Template Selection -->
    <div>
        <label class="block text-sm font-medium mb-2">{{ __('import_export.template') }}</label>
        <select wire:model="exportTemplate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            @foreach ($templates as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <!-- Field Selection (except SQL) -->
    @if ($exportFormat !== 'sql')
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-medium">{{ __('import_export.select_fields') }}</label>
                <div class="space-x-1">
                    <button wire:click="selectAllFields" class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                        {{ __('common.select_all') }}
                    </button>
                    <button wire:click="clearFields" class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                        {{ __('common.clear') }}
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 p-2 border border-gray-300 rounded-lg max-h-48 overflow-y-auto bg-gray-50">
                @foreach ($exportableFields as $field => $label)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox"
                               wire:model.live="selectedFields"
                               value="{{ $field }}"
                               class="rounded"
                        />
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="border-t pt-4 space-y-3">
        <h3 class="font-semibold text-sm">{{ __('import_export.filters') }}</h3>

        <div class="grid grid-cols-2 gap-3">
            <!-- Category -->
            <div>
                <label class="block text-xs font-medium mb-1">{{ __('patient.category') }}</label>
                <select wire:model="filterCategory" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                    <option value="all">{{ __('common.all') }}</option>
                    <option value="normal">{{ __('enums.category.normal') }}</option>
                    <option value="exacting">{{ __('enums.category.exacting') }}</option>
                    <option value="vip">{{ __('enums.category.vip') }}</option>
                    <option value="special">{{ __('enums.category.special') }}</option>
                </select>
            </div>

            <!-- Type -->
            <div>
                <label class="block text-xs font-medium mb-1">{{ __('patient.type') }}</label>
                <select wire:model="filterType" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                    <option value="all">{{ __('common.all') }}</option>
                    <option value="cash">{{ __('enums.type.cash') }}</option>
                    <option value="insurance">{{ __('enums.type.insurance') }}</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-xs font-medium mb-1">{{ __('patient.status') }}</label>
                <select wire:model="filterStatus" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
                    <option value="all">{{ __('common.all') }}</option>
                    <option value="active">{{ __('common.active') }}</option>
                    <option value="inactive">{{ __('common.inactive') }}</option>
                </select>
            </div>

            <!-- From Date -->
            <div>
                <label class="block text-xs font-medium mb-1">{{ __('import_export.from_date') }}</label>
                <input type="date" wire:model="filterFromDate" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
            </div>

            <!-- To Date -->
            <div>
                <label class="block text-xs font-medium mb-1">{{ __('import_export.to_date') }}</label>
                <input type="date" wire:model="filterToDate" class="w-full px-2 py-1 text-sm border border-gray-300 rounded">
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-2 pt-4 border-t">
        <button @click="show = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            {{ __('common.close') }}
        </button>
        <button wire:click="export"
                wire:loading.attr="disabled"
                class="flex-1 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 disabled:opacity-50 transition">
            <span wire:loading.remove>{{ __('import_export.export_now') }}</span>
            <span wire:loading>
                <i class="fas fa-spinner fa-spin mr-2"></i>
                {{ __('common.processing') }}
            </span>
        </button>
    </div>
</div>
