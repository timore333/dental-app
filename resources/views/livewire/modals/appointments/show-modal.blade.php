<div dir="auto"
     class="p-4 h-full bg-white rounded-2xl shadow-lg shadow-gray-200 sm:p-6 col-span-2 mt-6 z-50">

    <!-- Appointment details -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold leading-none text-gray-900 text-start">
            {{ __('Appointment details') }}
        </h3>

        <span class="{{ $this->getStatusColor() }}">
            {{ $this->getStatusLabel() }}
        </span>
    </div>

    <hr>

    <!-- Appointment Information -->
    <div class="flow-root">
        <ul role="list" class="divide-y divide-gray-200">

            <!-- Patient -->
            <li class="py-3 sm:py-4">
                <div class="grid grid-cols-2 items-center gap-4">
                    <p class="text-sm font-medium text-gray-900 truncate text-start">
                        {{ __('Patient') }}
                    </p>
                    <div class="text-base font-semibold text-gray-900 text-end">
                        {{ $appointment->patient->getName() }}
                    </div>
                </div>
            </li>

            <!-- Doctor -->
            <li class="py-3 sm:py-4">
                <div class="grid grid-cols-2 items-center gap-4">
                    <p class="text-sm font-medium text-gray-900 truncate text-start">
                        {{ __('Doctor') }}
                    </p>
                    <div class="text-base font-semibold text-gray-900 text-end">
                        {{ $appointment->doctor?->name ?? __('Unassigned') }}
                    </div>
                </div>
            </li>

            <!-- Date -->
            <li class="py-3 sm:py-4">
                <div class="grid grid-cols-2 items-center gap-4">
                    <p class="text-sm font-medium text-gray-900 truncate text-start">
                        {{ __('Date') }}
                    </p>
                    <div class="text-base font-semibold text-gray-900 text-end">
                        {{ $appointment->start->format('d/m/Y H:i') }}
                    </div>
                </div>
            </li>
            <!-- Time -->
            <li class="py-3 sm:py-4">
                <div class="grid grid-cols-2 items-center gap-4">
                    <p class="text-sm font-medium text-gray-900 truncate text-start">
                        {{ __('time') }}
                    </p>
                    <div class="text-base font-semibold text-gray-900 text-end">
                        {{ timeFromDate($appointment->start,'','H:i') }} : {{ timeFromDate($appointment->end,'','H:i') }}
                    </div>
                </div>
            </li>

            <!-- Reason -->
            <li class="py-3 sm:py-4">
                <div class="grid grid-cols-2 items-center gap-4">
                    <p class="text-sm font-medium text-gray-900 truncate text-start">
                        {{ __('Reason') }}
                    </p>
                    <div class="text-base font-semibold text-gray-900 text-end">
                        {{ $appointment->reason }}
                    </div>
                </div>
            </li>

            <!-- Notes -->
            <li class="py-3 sm:py-4">
                <div class="grid grid-cols-2 items-center gap-4">
                    <p class="text-sm font-medium text-gray-900 truncate text-start">
                        {{ __('Notes') }}
                    </p>
                    <div class="text-base font-semibold text-gray-900 text-end">
                        {{ $appointment->notes }}
                    </div>
                </div>
            </li>

        </ul>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-2 items-center pt-3 border-t border-gray-200 sm:pt-6">

        <button
            wire:click="edit"
            type="button"
            class="inline-flex items-center p-2 text-sm font-medium text-gray-500 rounded-2xl hover:text-gray-900">
            {{ __('Edit appointment') }}
        </button>

        <button
            wire:click="markCompleted"
            wire:confirm="{{ __('Mark completed') }}"
            type="button"
            class="inline-flex items-center p-2 text-sm font-medium text-gray-500 rounded-2xl hover:text-gray-900">
            {{ __('Mark completed') }}
        </button>

        <button
            wire:click="markNoShow"
            wire:confirm="{{ __('Mark not show') }}"
            type="button"
            class="inline-flex items-center p-2 text-sm font-medium text-gray-500 rounded-2xl hover:text-gray-900">
            {{ __('Mark no show') }}
        </button>

        <button
            wire:click="cancel"
            wire:confirm="{{ __('Confirm cancel') }}"
            type="button"
            class="inline-flex items-center p-2 text-sm font-medium text-gray-500 rounded-2xl hover:text-gray-900">
            {{ __('Cancel appointment') }}
        </button>

    </div>
</div>
