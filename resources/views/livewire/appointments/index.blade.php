<div class="w-full">
    <!-- Header Section -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Title -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('Manage all clinic appointments') }}
                </h3>
            </div>

            <!-- New Appointment Button -->
            <button wire:click="openCreateModal" class="btn btn-primary btn-sm">
                <span>+</span>
                {{ __('New Appointment') }}
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/50 border-b border-gray-200 dark:border-slate-700">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search -->
            <div>
                <input type="text"
                       wire:model.live="search"
                       placeholder="{{ __('Search patient...') }}"
                       class="form-control form-control-sm">
            </div>

            <!-- Status Filter -->
            <div>
                <select wire:model.live="statusFilter" class="form-control form-control-sm">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="scheduled">{{ __('Scheduled') }}</option>
                    <option value="completed">{{ __('Completed') }}</option>
                    <option value="cancelled">{{ __('Cancelled') }}</option>
                    <option value="no-show">{{ __('No Show') }}</option>
                </select>
            </div>

            <!-- Doctor Filter -->
            <div>
                <select wire:model.live="doctorFilter" class="form-control form-control-sm">
                    <option value="">{{ __('All Doctors') }}</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">
                            {{ $doctor->first_name }} {{ $doctor->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date Filter -->
            <div>
                <input type="date"
                       wire:model.live="dateFilter"
                       class="form-control form-control-sm">
            </div>

            <!-- Reset Button -->
            <div class="flex">
                <button wire:click="resetFilters" class="btn btn-secondary btn-sm w-full">
                    {{ __('Reset') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Table Section with Horizontal Scroll -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-100 dark:bg-slate-700 border-b border-gray-200 dark:border-slate-600">
                    <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">
                        <button wire:click="sort('appointment_date')" class="hover:text-primary">
                            {{ __('Date') }}
                            @if($sortBy === 'appointment_date')
                                {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">
                        {{ __('Patient') }}
                    </th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">
                        {{ __('Doctor') }}
                    </th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">
                        {{ __('Reason') }}
                    </th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-white">
                        {{ __('Status') }}
                    </th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-900 dark:text-white">
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                @forelse($appointments as $appointment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                            {{ $appointment->appointment_date->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                            {{ $appointment->patient->name }}
                        </td>
                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                            {{ $appointment->doctor?->first_name ?? '' }} {{ $appointment->doctor?->last_name ?? '' }}
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300 truncate">
                            {{ Str::limit($appointment->reason, 30) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($appointment->status === 'scheduled') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($appointment->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @endif">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <!-- View Button -->
                            <button wire:click="openViewModal({{ $appointment->id }})"
                                    class="text-primary hover:text-primary-dark inline-block mr-2"
                                    title="{{ __('View') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>

                            <!-- Edit Button (only for scheduled) -->
                            @if($appointment->status === 'scheduled')
                                <button wire:click="openEditModal({{ $appointment->id }})"
                                        class="text-warning hover:text-warning-dark inline-block mr-2"
                                        title="{{ __('Edit') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                            @endif

                            <!-- Mark Completed Button -->
                            <button wire:click="confirmMarkCompleted({{ $appointment->id }})"
                                    @if($appointment->status !== 'scheduled') disabled @endif
                                    class="text-success hover:text-success-dark inline-block mr-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                    title="{{ __('Mark Completed') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>

                            <!-- Cancel Button -->
                            <button wire:click="confirmCancel({{ $appointment->id }})"
                                    @if($appointment->status !== 'scheduled') disabled @endif
                                    class="text-error hover:text-error-dark inline-block mr-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                    title="{{ __('Cancel') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>

                            <!-- Mark No-Show Button -->
                            <button wire:click="confirmMarkNoShow({{ $appointment->id }})"
                                    @if($appointment->status !== 'scheduled') disabled @endif
                                    class="text-warning hover:text-warning-dark inline-block disabled:opacity-50 disabled:cursor-not-allowed"
                                    title="{{ __('Mark No-Show') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-12a9 9 0 110 18 9 9 0 010-18z"></path>
                                </svg>
                            </button>

                            <!-- Delete Button (only for scheduled) -->
                            @if($appointment->status === 'scheduled')
                                <button wire:click="confirmDelete({{ $appointment->id }})"
                                        class="text-error hover:text-error-dark inline-block ml-2"
                                        title="{{ __('Delete') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            {{ __('No appointments found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($appointments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50">
            {{ $appointments->links() }}
        </div>
    @endif

<!-- ============================================================ -->
<!-- CREATE MODAL -->
<!-- ============================================================ -->
@if($showCreateModal)
 @include('appointments.create-new-appointment-modal')
@endif
    <!-- ============================================================ -->
    <!-- VIEW MODAL (Show Details) -->
    <!-- ============================================================ -->
    @if($showViewModal && $selectedAppointmentId)
        @php
            $viewAppointment = \App\Models\Appointment::with('patient', 'doctor')->find($selectedAppointmentId);
        @endphp

        @if($viewAppointment)
            <div class="fixed inset-0 z-40 overflow-y-auto">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                         wire:click="closeViewModal"></div>

                    <!-- Modal -->
                    <div class="relative inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <!-- Close Button -->
                        <button wire:click="closeViewModal"
                                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <!-- Header -->
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ __('Appointment Details') }}
                            </h3>
                        </div>

                        <!-- Content -->
                        <div class="px-6 py-4 space-y-3">
                            <div>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ __('Patient') }}:</span>
                                <span class="text-gray-700 dark:text-gray-300">{{ $viewAppointment->patient->name }}</span>
                            </div>

                            <div>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ __('Doctor') }}:</span>
                                <span class="text-gray-700 dark:text-gray-300">
                                    {{ $viewAppointment->doctor?->first_name ?? '-' }} {{ $viewAppointment->doctor?->last_name ?? '' }}
                                </span>
                            </div>

                            <div>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ __('Date & Time') }}:</span>
                                <span class="text-gray-700 dark:text-gray-300">
                                    {{ $viewAppointment->appointment_date->format('M d, Y H:i') }}
                                </span>
                            </div>

                            <div>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ __('Status') }}:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                    @if($viewAppointment->status === 'scheduled') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($viewAppointment->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($viewAppointment->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @endif">
                                    {{ ucfirst($viewAppointment->status) }}
                                </span>
                            </div>

                            <div>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ __('Reason') }}:</span>
                                <span class="text-gray-700 dark:text-gray-300">{{ $viewAppointment->reason }}</span>
                            </div>

                            @if($viewAppointment->notes)
                                <div>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ __('Notes') }}:</span>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $viewAppointment->notes }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 flex justify-end">
                            <button wire:click="closeViewModal" class="btn btn-secondary">
                                {{ __('Close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- ============================================================ -->
    <!-- CONFIRMATION DIALOG -->
    <!-- ============================================================ -->
    @if($showConfirmDialog)
        <div class="fixed inset-0 z-40 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                     wire:click="$set('showConfirmDialog', false)"></div>

                <!-- Modal -->
                <div class="relative inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
                    <!-- Icon -->
                    <div class="px-6 pt-6 flex justify-center">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-12a9 9 0 110 18 9 9 0 010-18z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-4 text-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            @if($confirmAction === 'delete')
                                {{ __('Delete Appointment?') }}
                            @elseif($confirmAction === 'markCompleted')
                                {{ __('Mark as Completed?') }}
                            @elseif($confirmAction === 'cancel')
                                {{ __('Cancel Appointment?') }}
                            @elseif($confirmAction === 'noShow')
                                {{ __('Mark as No-Show?') }}
                            @endif
                        </h3>
                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                            @if($confirmAction === 'delete')
                                {{ __('This will permanently delete the appointment. This cannot be undone.') }}
                            @elseif($confirmAction === 'markCompleted')
                                {{ __('Mark this appointment as completed?') }}
                            @elseif($confirmAction === 'cancel')
                                {{ __('Cancel this scheduled appointment?') }}
                            @elseif($confirmAction === 'noShow')
                                {{ __('Mark this appointment as no-show?') }}
                            @endif
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 flex gap-3 justify-end">
                        <button wire:click="$set('showConfirmDialog', false)" class="btn btn-secondary">
                            {{ __('Cancel') }}
                        </button>
                        <button wire:click="executeAction" class="btn btn-error">
                            {{ __('Confirm') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ============================================================ -->
<!-- EDIT MODAL -->
<!-- ============================================================ -->
@if($showEditModal && $editingAppointmentId)
    @include('appointments.edit-appointment-modal')
@endif

</div>
