<?php

namespace App\Livewire\Modals\Appointments;

use App\Models\Appointment;
use Cloudstudio\Modal\LivewireModal;

class ShowModal extends LivewireModal
{
    /**
     * ==================== PROPERTIES ====================
     */

    public Appointment $appointment;

    /**
     * ==================== LIFECYCLE ====================
     */

    public function mount(Appointment $appointment)
    {
        if (!auth()->user()->can('view', $appointment)) {
            abort(403);
        }

        $this->appointment = $appointment;
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public static function modalFlyout(): bool
    {
        return false;
    }

    /**
     * ==================== METHODS ====================
     */

    /**
     * Edit appointment
     */
    public function edit(): void
    {
        if (!auth()->user()->can('update', $this->appointment)) {
            $this->dispatch('notify', message: __('Unauthorized'), type: 'error');
            return;
        }

        $this->dispatch('openModal', component: 'modals.appointments.update-modal', arguments: [
            'appointment' => $this->appointment->id,
        ]);
    }

    /**
     * Mark as completed
     */
    public function markCompleted(): void
    {
        if (!auth()->user()->can('update', $this->appointment)) {
            $this->dispatch('notify', message: __('Unauthorized'), type: 'error');
            return;
        }

        $this->appointment->markCompleted();
        event(new \App\Events\AppointmentCompleted($this->appointment));

        $this->dispatch('notify', message: __('Mark as completed'), type: 'success');
        $this->closeModal();
        $this->dispatch('appointmentUpdated');
    }

    /**
     * Mark as no-show
     */
    public function markNoShow(): void
    {
        if (!auth()->user()->can('update', $this->appointment)) {
            $this->dispatch('notify', message: __('Unauthorized'), type: 'error');
            return;
        }

        $this->appointment->markNoShow();

        $this->dispatch('notify', message: __('Appointment marked not show'), type: 'success');
        $this->closeModal();
        $this->dispatch('appointmentUpdated');
    }

    /**
     * Cancel appointment
     */
    public function cancel(): void
    {
        if (!auth()->user()->can('delete', $this->appointment)) {
            $this->dispatch('notify', message: __('Unauthorized'), type: 'error');
            return;
        }

        $this->appointment->markCancelled();
        event(new \App\Events\AppointmentCancelled($this->appointment));

        $this->dispatch('notify', message: __('Appointment cancelled'), type: 'success');
        $this->closeModal();
        $this->dispatch('appointmentUpdated');
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return __("{$this->appointment->status}");
    }

    /**
     * Get status color
     */
    public function getStatusColor(): string
    {
        return match ($this->appointment->status) {
            'scheduled' => 'text-white bg-cyan-500 text-xs font-bold uppercase mr-2 px-2.5 py-1 rounded-md',
            'completed' => 'text-white bg-gradient-to-br from-green-500 to-green-700 text-xs font-bold uppercase mr-2 px-2.5 py-1 rounded-md',
            'cancelled' => 'text-white bg-gradient-to-br from-pink-500 to-violet-500 text-xs font-bold uppercase mr-2 px-2.5 py-1 rounded-md',
            'no-show' => 'text-white bg-blue-500 text-xs font-bold uppercase mr-2 px-2.5 py-1 rounded-md',
            default => 'text-white bg-voilet-500 text-xs font-bold uppercase mr-2 px-2.5 py-1 rounded-md',
        };
    }

    /**
     * Check if appointment can be edited
     */
    public function canEdit(): bool
    {
        return $this->appointment->status === 'scheduled' && auth()->user()->can('update', $this->appointment);
    }

    /**
     * Check if appointment can be marked completed
     */
    public function canMarkCompleted(): bool
    {
        return in_array($this->appointment->status, ['scheduled']) && auth()->user()->can('update', $this->appointment);
    }

    /**
     * Check if appointment can be cancelled
     */
    public function canCancel(): bool
    {
        return in_array($this->appointment->status, ['scheduled']) && auth()->user()->can('delete', $this->appointment);
    }

    /**
     * ==================== RENDER ====================
     */

    public function render()
    {
        return view('livewire.modals.appointments.show-modal');
    }
}
