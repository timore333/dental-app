+   <div class="fixed inset-0 z-40 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 wire:click="closeCreateModal"></div>

            <!-- Modal -->
            <div class="relative inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-50">
                <!-- Close Button -->
                <button wire:click="closeCreateModal"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ __('Create Appointment') }}
                    </h3>
                </div>

                <!-- Content -->
                <div class="px-6 py-6 space-y-5">
                    <!-- Patient Select -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('Patient') }} <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="patientId"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:focus:ring-blue-400 transition">
                            <option value="">{{ __('Select Patient') }}</option>
                            @php
                                $patients = \App\Models\Patient::orderBy('first_name')->get();
                            @endphp
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                            @endforeach
                        </select>
                        @error('patientId') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Doctor Select -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('Doctor') }}
                        </label>
                        <select wire:model="doctorId"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:focus:ring-blue-400 transition">
                            <option value="">{{ __('Select Doctor (Optional)') }}</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">
                                    {{ $doctor->first_name }} {{ $doctor->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('Date') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               wire:model="appointmentDate"
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:focus:ring-blue-400 transition"
                               required>
                        @error('appointmentDate') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('Time') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="time"
                               wire:model="appointmentTime"
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:focus:ring-blue-400 transition"
                               required>
                        @error('appointmentTime') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Reason -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('Reason') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="reason"
                                  rows="3"
                                  placeholder="{{ __('Enter appointment reason') }}"
                                  class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:focus:ring-blue-400 transition resize-none"
                                  required></textarea>
                        @error('reason') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('Notes') }}
                        </label>
                        <textarea wire:model="notes"
                                  rows="2"
                                  placeholder="{{ __('Enter additional notes') }}"
                                  class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:focus:ring-blue-400 transition resize-none"></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 flex gap-3 justify-end">
                    <button wire:click="closeCreateModal"
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 rounded-lg transition font-medium">
                        {{ __('Cancel') }}
                    </button>
                    <button wire:click="save"
                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 rounded-lg transition font-medium">
                        {{ __('Create') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
