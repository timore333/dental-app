<div>
    @if(!$showModal)
        <button wire:click="openModal()" type="button" class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold rounded-lg transition">
            {{ __('New Appointment') }}
        </button>
    @else
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black bg-opacity-50"></div>
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-2xl w-full">
                    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $isEditing ? __('Edit Appointment') : __('Create Appointment') }}
                        </h2>
                        <button wire:click="closeModal()" type="button" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="save" class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="patientId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Patient') }} <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="patientId" id="patientId"
                                    class="w-full px-3 py-2 border @error('patientId') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <option value="">{{ __('Select Patient') }}</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                    @endforeach
                                </select>
                                @error('patientId')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="doctorId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Doctor') }}
                                </label>
                                <select wire:model="doctorId" id="doctorId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <option value="">{{ __('Select Doctor') }}</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="appointmentDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Date') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model="appointmentDate" id="appointmentDate"
                                    class="w-full px-3 py-2 border @error('appointmentDate') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                                @error('appointmentDate')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="appointmentTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Time') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="time" wire:model="appointmentTime" id="appointmentTime"
                                    class="w-full px-3 py-2 border @error('appointmentTime') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                                @error('appointmentTime')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Reason') }} <span class="text-red-500">*</span>
                                </label>
                                <textarea wire:model="reason" id="reason" rows="2"
                                    class="w-full px-3 py-2 border @error('reason') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                                @error('reason')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Notes') }}
                                </label>
                                <textarea wire:model="notes" id="notes" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                            </div>

                            @if($errors->has('general'))
                                <div class="md:col-span-2 p-3 bg-red-100 dark:bg-red-900 border border-red-400 text-red-800 dark:text-red-200 rounded-lg">
                                    {{ $errors->first('general') }}
                                </div>
                            @endif
                        </div>

                        <div class="flex gap-3 justify-end">
                            <button wire:click="closeModal()" type="button" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-slate-600 dark:hover:bg-slate-500 text-gray-900 dark:text-white font-semibold rounded-lg transition">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit" class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white font-semibold rounded-lg transition">
                                {{ $isEditing ? __('Update') : __('Create') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
