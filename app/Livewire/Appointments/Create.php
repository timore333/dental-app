<?php

namespace App\Livewire\Appointments;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $patientId = '';
    public $doctorId = '';
    public $appointmentDate = '';
    public $appointmentTime = '';
    public $reason = '';
    public $notes = '';

    public $showModal = false;
    public $isEditing = false;
    public $editingAppointmentId = null;

    protected $listeners = [
        'showCreateModal' => 'openModal',
        'closeCreateModal' => 'closeModal',
        'editAppointment' => 'loadEditData',
    ];

    protected $rules = [
        'patientId' => 'required|exists:patients,id',
        'doctorId' => 'nullable|exists:doctors,id',
        'appointmentDate' => 'required|date|after_or_equal:today',
        'appointmentTime' => 'required|date_format:H:i',
        'reason' => 'required|string|min:3|max:500',
        'notes' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'patientId.required' => 'Patient is required.',
        'patientId.exists' => 'Selected patient does not exist.',
        'appointmentDate.required' => 'Appointment date is required.',
        'appointmentDate.after_or_equal' => 'Appointment date must be today or later.',
        'appointmentTime.required' => 'Appointment time is required.',
        'appointmentTime.date_format' => 'Appointment time must be in H:i format.',
        'reason.required' => 'Reason is required.',
        'reason.min' => 'Reason must be at least 3 characters.',
        'reason.max' => 'Reason must not exceed 500 characters.',
        'notes.max' => 'Notes must not exceed 1000 characters.',
    ];
    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.appointments.create', [
            'patients' => Patient::orderBy('first_name')->orderBy('last_name')->get(),
            'doctors' => Doctor::orderBy('name')->get()
        ]);
    }

    #[Computed]
    public function patients()
    {
        return Patient::orderBy('name')->get();
    }

    #[Computed]
    public function doctors()
    {
        return Doctor::orderBy('first_name')->orderBy('last_name')->get();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->editingAppointmentId = null;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->isEditing = false;
        $this->editingAppointmentId = null;
    }

    public function loadEditData($appointmentId)
    {
        try {
            $appointment = Appointment::findOrFail($appointmentId);
            $this->authorizeAppointmentAccess($appointment);

            if ($appointment->status !== 'scheduled') {
                throw new \Exception(__('Cannot edit completed or cancelled appointments.'));
            }

            $this->editingAppointmentId = $appointment->id;
            $this->patientId = $appointment->patient_id;
            $this->doctorId = $appointment->doctor_id;
            $this->appointmentDate = $appointment->appointment_date->format('Y-m-d');
            $this->appointmentTime = $appointment->appointment_date->format('H:i');
            $this->reason = $appointment->reason;
            $this->notes = $appointment->notes;
            $this->isEditing = true;
            $this->showModal = true;
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: __('Error: ' . $e->getMessage()));
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $appointmentDateTime = $this->appointmentDate . ' ' . $this->appointmentTime;

            if ($this->isEditing && $this->editingAppointmentId) {
                $appointment = Appointment::findOrFail($this->editingAppointmentId);
                $this->authorizeAppointmentAccess($appointment);

                $appointment->update([
                    'patient_id' => $this->patientId,
                    'doctor_id' => $this->doctorId,
                    'appointment_date' => $appointmentDateTime,
                    'reason' => $this->reason,
                    'notes' => $this->notes,
                ]);

                $this->dispatch('notify', type: 'success', message: __('Appointment updated successfully.'));
            } else {
                Appointment::create([
                    'patient_id' => $this->patientId,
                    'doctor_id' => $this->doctorId,
                    'appointment_date' => $appointmentDateTime,
                    'reason' => $this->reason,
                    'notes' => $this->notes,
                    'status' => 'scheduled',
                    'created_by' => Auth::id(),
                ]);

                $this->dispatch('notify', type: 'success', message: __('Appointment created successfully.'));
            }

            $this->closeModal();
            $this->dispatch('refreshAppointments');
        } catch (\Exception $e) {
            $this->addError('general', __('Error saving appointment: ' . $e->getMessage()));
        }
    }

    private function authorizeAppointmentAccess($appointment)
    {
        $isOwner = $appointment->created_by === Auth::id();
        $isAssignedDoctor = $appointment->doctor?->user_id === Auth::id();
        $isAdmin = Auth::user()->isAdmin();

        if (!($isOwner || $isAssignedDoctor || $isAdmin)) {
            throw new \Exception(__('Unauthorized access.'));
        }
    }

    public function resetForm()
    {
        $this->patientId = '';
        $this->doctorId = '';
        $this->appointmentDate = '';
        $this->appointmentTime = '';
        $this->reason = '';
        $this->notes = '';
        $this->resetErrorBag();
    }
}
