<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-2xl w-full">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Appointment Details') }}</h2>
                <button wire:click="closeModal()" type="button" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Patient') }}</label>
                        <p class="text-gray-900 dark:text-white mt-1">{{ $appointment->patient->name }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Doctor') }}</label>
                        <p class="text-gray-900 dark:text-white mt-1">{{ $appointment->doctor?->first_name ?? 'N/A' }} {{ $appointment->doctor?->last_name ?? '' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Date & Time') }}</label>
                        <p class="text-gray-900 dark:text-white mt-1">{{ $appointment->appointment_date->format('F d, Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                        <span class="inline-flex mt-1 px-3 py-1 rounded-full text-xs font-semibold
                            @if($appointment->status === 'scheduled') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                            @elseif($appointment->status === 'completed') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                            @elseif($appointment->status === 'cancelled') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                            @else bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                            @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Reason') }}</label>
                        <p class="text-gray-900 dark:text-white mt-1">{{ $appointment->reason }}</p>
                    </div>

                    @if($appointment->notes)
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Notes') }}</label>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $appointment->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-slate-700">
                <button wire:click="closeModal()" type="button" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-slate-600 dark:hover:bg-slate-500 text-gray-900 dark:text-white font-semibold rounded-lg transition">
                    {{ __('Close') }}
                </button>
            </div>
        </div>
    </div>
</div>
