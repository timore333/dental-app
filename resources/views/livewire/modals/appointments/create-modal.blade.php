<div class="p-6">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            {{ __('dental.create_appointment') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            {{ __('dental.schedule_new_appointment') }}
        </p>
    </div>

    <form wire:submit.prevent="create" class="space-y-4">
        <!-- Patient Selection -->
        <div class="col-span-6 sm:col-span-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('Patient') }} <span class="text-red-500">*</span>
            </label>
            <select
                wire:model="patient_id"
                class="border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full p-2.5 @error('patient_id') border-red-500 @enderror"
            >
                @foreach ($this->patients as $patient)
                    <option value="{{ $patient->id }}">{{ $patient->getName() }}  </option>
                @endforeach
            </select>
            @error('patient_id')
                <p class="text-sm text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Doctor Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('Doctor') }}
            </label>
            <select
                wire:model="doctor_id"
                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 @error('doctor_id') border-red-500 @enderror"
            >
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
                {{ __('Appointment date time') }} <span class="text-red-500">*</span>
            </label>
            <input
                type="datetime-local"
                wire:model="start"
                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg
                 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500
                  @error('start') border-red-500 @enderror"
            />
            @error('start')
                <p class="text-sm text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Reason -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('Reason') }} <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                wire:model="reason"
                placeholder="{{ __('Regular checkup') }}"
                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 @error('reason') border-red-500 @enderror"
            />
            @error('reason')
                <p class="text-sm text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Notes -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('Notes') }}
            </label>
            <textarea
                wire:model="notes"
                rows="3"
                placeholder="{{ __('Additional notes') }}"
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
                {{ __('Cancel') }}
            </button>
            <button
                type="submit"
                               class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2"
            >
{{__('Create appointment')}}
            </button>
        </div>
    </form>
</div>
