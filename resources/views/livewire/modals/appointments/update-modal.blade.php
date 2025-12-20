<div class="p-6">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            {{ __('dental.edit_appointment') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            {{ __('dental.update_appointment_details') }}
        </p>
    </div>

    <form wire:submit.prevent="update" class="space-y-4">
        <!-- Patient Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('dental.patient') }} <span class="text-red-500">*</span>
            </label>
            <select
                wire:model="patient_id"
                disabled
                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-900 dark:text-white opacity-75"
            >
                @foreach ($this->patients as $patient)
                    <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Doctor Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('dental.doctor') }}
            </label>
            <select
                wire:model="doctor_id"
                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 @error('doctor_id') border-red-500 @enderror"
            >
                <option value="">{{ __('dental.no_doctor_assigned') }}</option>
                @foreach ($this->doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                @endforeach
            </select>
            @error('doctor_id')
                <p class="text-sm text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Appointment Date & Time -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('dental.appointment_date_time') }} <span class="text-red-500">*</span>
            </label>
            <input
                type="datetime-local"
                wire:model="start"
                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 @error('start') border-red-500 @enderror"
            />
            @error('start')
                <p class="text-sm text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Reason -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('dental.reason') }} <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                wire:model="reason"
                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 @error('reason') border-red-500 @enderror"
            />
            @error('reason')
                <p class="text-sm text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Notes -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('dental.notes') }}
            </label>
            <textarea
                wire:model="notes"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
            ></textarea>
            @error('notes')
                <p class="text-sm text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4">
            <button
                type="button"
                @click="$wire.dispatch('closeModal')"
                class="flex-1 px-4 py-2 bg-gray-200 dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-slate-600 font-medium transition-colors"
            >
                {{ __('dental.cancel') }}
            </button>
            <button
                type="submit"
                class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2"
            >
                <span wire:loading.remove>{{ __('Update') }}</span>
                <span wire:loading>
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </form>
</div>
