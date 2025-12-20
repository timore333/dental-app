<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">{{ __('patient.patients') }}</h1>
    </div>

    <!-- Messages -->
    @if (session('message'))
        <div class="p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('message') }}
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Import/Export Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <button @click="$dispatch('openModal', { component: 'modals.patient-import-modal' })"
            class="group relative overflow-hidden rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 p-4 text-white transition-all duration-300 hover:shadow-lg hover:scale-105">
            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>
            <div class="relative flex flex-col items-center gap-2">
                <i class="fas fa-upload text-2xl"></i>
                <span class="font-semibold">{{ __('import_export.import') }}</span>
                <span class="text-xs text-blue-100">{{ __('import_export.upload_excel') }}</span>
            </div>
        </button>

        <button @click="$dispatch('openModal', { component: 'modals.patient-export-modal' })"
            class="group relative overflow-hidden rounded-lg bg-gradient-to-r from-green-500 to-green-600 p-4 text-white transition-all duration-300 hover:shadow-lg hover:scale-105">
            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>
            <div class="relative flex flex-col items-center gap-2">
                <i class="fas fa-download text-2xl"></i>
                <span class="font-semibold">{{ __('import_export.export') }}</span>
                <span class="text-xs text-green-100">{{ __('import_export.download_data') }}</span>
            </div>
        </button>

        <a href="{{ route('patients.create') }}"
            class="group relative overflow-hidden rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 p-4 text-white transition-all duration-300 hover:shadow-lg hover:scale-105">
            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>
            <div class="relative flex flex-col items-center gap-2">
                <i class="fas fa-plus text-2xl"></i>
                <span class="font-semibold">{{ __('patient.add_patient') }}</span>
                <span class="text-xs text-purple-100">{{ __('patient.new_patient') }}</span>
            </div>
        </a>
    </div>

    <!-- Rest of your existing view code -->
    <!-- Filters, table, etc. -->
</div>
