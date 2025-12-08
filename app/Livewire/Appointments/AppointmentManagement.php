<?php

namespace App\Livewire\Appointments;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\AppointmentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;


class AppointmentManagement extends Component
{
    use WithPagination;


    public $page = 1;
    public $search = '';
    public $statusFilter = '';
    public $doctorFilter = '';
    public $dateFilter = '';
    public $perPage = 15;
    public $sortBy = 'appointment_date';
    public $sortDirection = 'desc';

    // Modal properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingAppointmentId = null;

    // Form properties
    public $patientId = '';
    public $doctorId = '';
    public $appointmentDate = '';
    public $appointmentTime = '';
    public $reason = '';
    public $notes = '';

    // Confirmation dialog
    public $showConfirmDialog = false;
    public $confirmAction = '';
    public $confirmAppointmentId = null;

    #[Computed]
    public function appointments()
    {
        $query = Appointment::query();
        // ... filters ...
        return $query
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10, ['*'], 'page', $this->page);
    }

    #[Computed]
    public function doctors()
    {
        return Doctor::orderBy('first_name')->get();
    }

    protected $listeners = ['refreshAppointments' => '$refresh'];

    protected $rules = [
        'patientId' => 'required|exists:patients,id',
        'doctorId' => 'nullable|exists:doctors,id',
        'appointmentDate' => 'required|date|after:now',
        'appointmentTime' => 'required|date_format:H:i',
        'reason' => 'required|string|max:500',
        'notes' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->loadAppointments();
    }

    public function render()
    {
        return view('livewire.appointments.appointment-management', [
            'appointments' => $this->getFilteredAppointments(),
            'doctors' => Doctor::orderBy('name')->get(),
            'statuses' => ['scheduled' => 'Scheduled', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'no-show' => 'No Show'],
        ]);
    }

    public function loadAppointments()
    {
        $query = Appointment::query();
        // ... filters ...
        $this->appointments = $query
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    private function getFilteredAppointments()
    {
        $query = Appointment::with('patient', 'doctor')
            ->where('created_by', Auth::id())
            ->orWhereHas('doctor', function ($q) {
                $q->where('user_id', Auth::id());
            });

        // Search by patient name or phone
        if (!empty($this->search)) {
            $query->whereHas('patient', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by status
        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        // Filter by doctor
        if (!empty($this->doctorFilter)) {
            $query->where('doctor_id', $this->doctorFilter);
        }

        // Filter by date
        if (!empty($this->dateFilter)) {
            $query->whereDate('appointment_date', $this->dateFilter);
        }

        return $query
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function updated($property)
{

    if (in_array($property, ['search', 'statusFilter', 'doctorFilter','dateFilter'])) {
        $this->page = 1;  // Not resetPage()
    }
}

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDoctorFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFilter()
    {
        $this->resetPage();
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'desc';
        }
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        $this->editingAppointmentId = $appointment->id;
        $this->patientId = $appointment->patient_id;
        $this->doctorId = $appointment->doctor_id;
        $this->appointmentDate = $appointment->appointment_date->format('Y-m-d');
        $this->appointmentTime = $appointment->appointment_date->format('H:i');
        $this->reason = $appointment->reason;
        $this->notes = $appointment->notes;

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function saveAppointment()
    {
        $this->validate();

        try {
            $appointmentDateTime = $this->appointmentDate . ' ' . $this->appointmentTime;

            if ($this->editingAppointmentId) {
                // Update existing appointment
                $appointment = Appointment::findOrFail($this->editingAppointmentId);

                if (!in_array($appointment->status, ['scheduled'])) {
                    $this->addError('appointmentDate', __('Cannot edit completed or cancelled appointments.'));
                    return;
                }

                $appointment->update([
                    'patient_id' => $this->patientId,
                    'doctor_id' => $this->doctorId,
                    'appointment_date' => $appointmentDateTime,
                    'reason' => $this->reason,
                    'notes' => $this->notes,
                ]);

                $this->dispatch('notify', type: 'success', message: __('Appointment updated successfully.'));
            } else {
                // Create new appointment
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

            $this->closeCreateModal();
            $this->closeEditModal();
            $this->dispatch('refreshAppointments');
        } catch (\Exception $e) {
            $this->addError('general', __('Error saving appointment: ' . $e->getMessage()));
        }
    }

    public function confirmDelete($appointmentId)
    {
        $this->confirmAppointmentId = $appointmentId;
        $this->confirmAction = 'delete';
        $this->showConfirmDialog = true;
    }

    public function confirmMarkCompleted($appointmentId)
    {
        $this->confirmAppointmentId = $appointmentId;
        $this->confirmAction = 'markCompleted';
        $this->showConfirmDialog = true;
    }

    public function confirmCancel($appointmentId)
    {
        $this->confirmAppointmentId = $appointmentId;
        $this->confirmAction = 'cancel';
        $this->showConfirmDialog = true;
    }

    public function executeAction()
    {
        try {
            $appointment = Appointment::findOrFail($this->confirmAppointmentId);

            match ($this->confirmAction) {
                'delete' => $this->deleteAppointment($appointment),
                'markCompleted' => $this->markCompleted($appointment),
                'cancel' => $this->markCancelled($appointment),
                default => null,
            };

            $this->showConfirmDialog = false;
            $this->confirmAppointmentId = null;
            $this->confirmAction = '';
            $this->dispatch('refreshAppointments');
        } catch (\Exception $e) {
            $this->addError('general', __('Error performing action: ' . $e->getMessage()));
        }
    }

    private function deleteAppointment($appointment)
    {
        if ($appointment->status !== 'scheduled') {
            throw new \Exception(__('Can only delete scheduled appointments.'));
        }

        $appointment->delete();
        $this->dispatch('notify', type: 'success', message: __('Appointment deleted successfully.'));
    }

    private function markCompleted($appointment)
    {
        if ($appointment->status !== 'scheduled') {
            throw new \Exception(__('Can only complete scheduled appointments.'));
        }

        $appointment->update(['status' => 'completed']);

        // Dispatch event to create visit
        event(new \App\Events\AppointmentCompleted($appointment));

        $this->dispatch('notify', type: 'success', message: __('Appointment marked as completed. Visit created.'));
    }

    private function markCancelled($appointment)
    {
        $appointment->update(['status' => 'cancelled']);
        $this->dispatch('notify', type: 'success', message: __('Appointment cancelled.'));
    }

    public function markNoShow($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $appointment->update(['status' => 'no-show']);
        $this->dispatch('notify', type: 'success', message: __('Appointment marked as no-show.'));
        $this->dispatch('refreshAppointments');
    }

    private function resetForm()
    {
        $this->patientId = '';
        $this->doctorId = '';
        $this->appointmentDate = '';
        $this->appointmentTime = '';
        $this->reason = '';
        $this->notes = '';
        $this->editingAppointmentId = null;
        $this->resetErrorBag();
    }
}
