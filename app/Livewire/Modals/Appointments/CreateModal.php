<?php

namespace App\Livewire\Modals\Appointments;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Cloudstudio\Modal\LivewireModal;
use Illuminate\Validation\ValidationException;

class CreateModal extends LivewireModal
{
    /**
     * ==================== PROPERTIES ====================
     */

    public ?string $date = null;
    public ?int $patient_id = null;
    public ?int $doctor_id = null;
    public ?string $start = null;
    public ?string $reason = '';
    public ?string $notes = '';
    public bool $isLoading = false;

    /**
     * Appointment service
     */
    private AppointmentService $appointmentService;


    /**
     * ==================== LIFECYCLE ====================
     */

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }


    public function mount(?string $date = null)
    {

        if ($date) {
            $this->start = Carbon::parse($date)->format('Y-m-d\TH:00');
        } else {
            $this->start = Carbon::now()->addHours(1)->format('Y-m-d\TH:00');
        }
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
        // Reuse rules from StoreAppointmentRequest
        return (new StoreAppointmentRequest())->rules();
    }

     protected function messages(): array
    {
        return (new StoreAppointmentRequest())->messages();
    }



    /**
     * ==================== METHODS ====================
     */

    /**
     * Create appointment
     */
    public function create(): void
    {
        $this->isLoading = true;

        // Validate appointment data
        $validated = $this->validate();
        $validated['status'] = 'scheduled';
        $validated['created_by'] = auth()->user()?->id;

        try {

            // Create appointment
           $appointment = $this->appointmentService->create($validated);

            // Dispatch event for SMS/email
            event(new \App\Events\AppointmentCreated($appointment));

            $this->dispatch('notify', message: __('dental.appointment_created'), type: 'success');
            $this->closeModal();

        } catch (ValidationException $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');

        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Get patients for select
     */
    #[\Livewire\Attributes\Computed]
    public function patients()
    {
        return Patient::active()->orderBy('first_name')->get();
    }

    /**
     * Get doctors for select
     */
    #[\Livewire\Attributes\Computed]
    public function doctors()
    {
        return Doctor::active()->orderBy('name')->get();
    }

    /**
     * ==================== RENDER ====================
     */

    public function render()
    {
        return view('livewire.modals.appointments.create-modal');
    }
}
