<?php

namespace App\Livewire\Modals\Appointments;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Cloudstudio\Modal\LivewireModal;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;

class UpdateModal extends LivewireModal
{
    public Appointment $appointment;

    #[Validate]
    public $patient_id;

    #[Validate]
    public $doctor_id;

    #[Validate]
    public $start;

    #[Validate]
    public $notes;

    #[Validate]
    public $status;

    public $conflictWarning = '';
    public $isLoading = false;

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
        $this->patient_id = $appointment->patient_id;
        $this->doctor_id = $appointment->doctor_id;
        $this->start = $appointment->start->format('Y-m-d\TH:i');
        $this->notes = $appointment->notes;
        $this->status = $appointment->status;
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public static function modalFlyout(): bool
    {
        return false;
    }

    protected function rules(): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'doctor_id' => ['nullable', 'integer', 'exists:doctors,id'],
            'start' => ['required', 'date_format:Y-m-d\TH:i', 'after_or_equal:now'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'in:scheduled,completed,cancelled,no-show'],
        ];
    }

    protected function messages(): array
    {
        return [
            'patient_id.required' => __('dental.patient_required'),
            'patient_id.exists' => __('dental.patient_not_found'),
            'doctor_id.exists' => __('dental.doctor_not_found'),
            'start.required' => __('dental.appointment_date_required'),
            'start.date_format' => __('dental.appointment_date_format_invalid'),
            'start.after_or_equal' => __('dental.appointment_must_be_future'),
            'notes.max' => __('dental.notes_max_1000_chars'),
            'status.in' => __('dental.invalid_status'),
        ];
    }

    /**
     * ==================== METHODS ====================
     */

    /**
     * Update appointment
     */
    public function update(): void
    {
        $this->isLoading = true;

        try {
            $this->authorize('update', $this->appointment);

            $validated = $this->validate();

            $appointmentService = app(AppointmentService::class);
            $appointmentService->update($this->appointment, $validated);
            // Dispatch event to refresh calendar
            $this->dispatch('appointmentUpdated');

            $this->dispatch('notify', message: __('dental.appointment_updated'), type: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Get patients for select
     */
    #[Computed]
    public function patients()
    {
        return Patient::active()->orderBy('first_name')->get();
    }

    /**
     * Get doctors for select
     */
    #[Computed]
    public function doctors()
    {
        return Doctor::active()->orderBy('name')->get();
    }

    /**
     * ==================== RENDER ====================
     */

    public function render()
    {
        return view('livewire.modals.appointments.update-modal');
    }
}
