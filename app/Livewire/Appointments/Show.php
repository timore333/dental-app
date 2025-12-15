<?php

namespace App\Livewire\Appointments;

use App\Models\Appointment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    public $appointmentId;
    public $appointment;

    public function mount($id)
    {
        $this->appointmentId = $id;
        $this->loadAppointment();
    }

    public function render()
    {
        return view('livewire.appointments.show');
    }

    private function loadAppointment()
    {
        $appointment = Appointment::with('patient', 'doctor')
            ->findOrFail($this->appointmentId);

        $isOwner = $appointment->created_by === Auth::id();
        $isAssignedDoctor = $appointment->doctor?->user_id === Auth::id();
        $isAdmin = Auth::user()->isAdmin();

        if (!($isOwner || $isAssignedDoctor || $isAdmin)) {
            throw new \Exception(__('Unauthorized access.'));
        }

        $this->appointment = $appointment;
    }

    public function closeModal()
    {
        $this->dispatch('closeViewModal');
    }
}
