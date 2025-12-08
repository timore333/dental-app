<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">{{ __('Appointments') }}</h1>
        <button wire:click="openCreateModal" class="btn btn-primary">
            {{ __('Create Appointment') }}
        </button>
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input
            type="text"
            wire:model.live="search"
            placeholder="{{ __('Search patient...') }}"
            class="form-input"
        >
        <select wire:model.live="statusFilter" class="form-input">
            <option value="">{{ __('All Statuses') }}</option>
            <option value="scheduled">{{ __('Scheduled') }}</option>
            <option value="completed">{{ __('Completed') }}</option>
            <option value="cancelled">{{ __('Cancelled') }}</option>
        </select>
        <select wire:model.live="doctorFilter" class="form-input">
            <option value="">{{ __('All Doctors') }}</option>
            @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}">{{ $doctor->full_name ?? $doctor->name }}</option>
            @endforeach
        </select>
        <input type="date" wire:model.live="dateFilter" class="form-input">
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700 border-b">
                <tr>
                    <th class="px-6 py-3 text-left cursor-pointer" wire:click="sort('appointment_date')">
                        {{ __('Date') }}
                        @if($sortBy === 'appointment_date')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left">{{ __('Patient') }}</th>
                    <th class="px-6 py-3 text-left">{{ __('Doctor') }}</th>
                    <th class="px-6 py-3 text-left">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-left">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($appointments as $appointment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4">{{ $appointment->appointment_date->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4">{{ $appointment->patient->name }}</td>
                        <td class="px-6 py-4">{{ $appointment->doctor?->full_name ?? $appointment->doctor?->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if($appointment->status === 'scheduled') bg-blue-100 text-blue-800
                                @elseif($appointment->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            @if($appointment->status === 'scheduled')
                                <button wire:click="openEditModal({{ $appointment->id }})" class="text-blue-600 hover:underline text-sm">
                                    {{ __('Edit') }}
                                </button>
                                <button wire:click="confirmMarkCompleted({{ $appointment->id }})" class="text-green-600 hover:underline text-sm">
                                    {{ __('Complete') }}
                                </button>
                                <button wire:click="confirmCancel({{ $appointment->id }})" class="text-red-600 hover:underline text-sm">
                                    {{ __('Cancel') }}
                                </button>
                            @else
                                <a href="{{ route('appointments.show', $appointment->id) }}" class="text-blue-600 hover:underline text-sm">
                                    {{ __('View') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            {{ __('No appointments found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($appointments->total() > 0)
        <div class="mt-4">
            {{ $appointments->links() }}
        </div>
    @endif

    <!-- Create/Edit Modal -->
    @if($showCreateModal || $showEditModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full">
                <h2 class="text-xl font-bold mb-4">
                    {{ $editingAppointmentId ? __('Edit Appointment') : __('Create Appointment') }}
                </h2>

                <form wire:submit="saveAppointment" class="space-y-4">
                    <!-- Patient ID -->
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Patient ID') }}</label>
                        <input type="number" wire:model="patientId" class="form-input w-full" required>
                        @error('patientId')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Doctor -->
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Doctor') }}</label>
                        <select wire:model="doctorId" class="form-input w-full" required>
                            <option value="">{{ __('Select Doctor') }}</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->full_name ?? $doctor->name }}</option>
                            @endforeach
                        </select>
                        @error('doctorId')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Date & Time -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium mb-1">{{ __('Date') }}</label>
                            <input type="date" wire:model="appointmentDate" class="form-input w-full" required>
                            @error('appointmentDate')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">{{ __('Time') }}</label>
                            <input type="time" wire:model="appointmentTime" class="form-input w-full" required>
                            @error('appointmentTime')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Reason -->
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Reason') }}</label>
                        <textarea wire:model="reason" rows="3" class="form-input w-full"></textarea>
                        @error('reason')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2 pt-4">
                        <button type="submit" class="btn btn-primary flex-1">
                            {{ __('Save') }}
                        </button>
                        <button type="button" wire:click="closeCreateModal" class="btn btn-secondary flex-1">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
