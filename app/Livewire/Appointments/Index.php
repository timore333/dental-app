<?php

namespace App\Livewire\Appointments;

use App\Models\Appointment;
use App\Models\Doctor;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    // Modal visibility states
    public $showCreateModal = false;
    public $showViewModal = false;
    public $showEditModal = false;
    public $showConfirmDialog = false;

    // Form data (shared for create/edit)
    public $patientId = '';
    public $doctorId = '';
    public $appointmentDate = '';
    public $appointmentTime = '';
    public $reason = '';
    public $notes = '';
    public $isEditing = false;
    public $editingAppointmentId = null;

    // Filters
    public $search = '';
    public $statusFilter = '';
    public $doctorFilter = '';
    public $dateFilter = '';

    // Sorting
    public $sortBy = 'appointment_date';
    public $sortDirection = 'desc';

    // Pagination
    public $perPage = 10;

    // Modal data
    public $selectedAppointmentId = null;
    public $confirmAppointmentId = null;
    public $confirmAction = '';


    protected $listeners = ['$refresh'];

    protected $rules = [
        'statusFilter' => 'nullable|string',
        'doctorFilter' => 'nullable|integer',
        'dateFilter' => 'nullable|date',
        'search' => 'nullable|string|max:100',
    ];

    public function mount()
    {
        $this->loadInitialData();
    }


    #[Computed]
    public function filteredAppointments()
    {
        return $this->getFilteredAppointments();
    }

    private function getFilteredAppointments()
    {
        $query = Appointment::with('patient', 'doctor')
            ->where(function ($q) {
                $q->where('created_by', Auth::id())
                    ->orWhereHas('doctor', function ($doc) {
                        $doc->where('user_id', Auth::id());
                    });
            });

        if (!empty($this->search)) {
            $query->whereHas('patient', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        if (!empty($this->doctorFilter)) {
            $query->where('doctor_id', $this->doctorFilter);
        }

        if (!empty($this->dateFilter)) {
            $query->whereDate('appointment_date', $this->dateFilter);
        }

        return $query
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
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

    public function openViewModal($appointmentId)
    {
        $appointment = Appointment::with('patient', 'doctor')->findOrFail($appointmentId);
        $this->authorizeAppointmentAccess($appointment);

        $this->selectedAppointmentId = $appointmentId;
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedAppointmentId = null;
    }

    public function openEditModal($appointmentId)
    {
        try {
            $appointment = Appointment::findOrFail($appointmentId);
            $this->authorizeAppointmentAccess($appointment);

            if ($appointment->status !== 'scheduled') {
                $this->dispatch('notify', type: 'error', message: __('Can only edit scheduled appointments.'));
                return;
            }

            // Load appointment data into form
            $this->editingAppointmentId = $appointment->id;
            $this->patientId = $appointment->patient_id;
            $this->doctorId = $appointment->doctor_id;
            $this->appointmentDate = $appointment->appointment_date->format('Y-m-d');
            $this->appointmentTime = $appointment->appointment_date->format('H:i');
            $this->reason = $appointment->reason;
            $this->notes = $appointment->notes ?? '';
            $this->isEditing = true;

            // Show modal
            $this->showEditModal = true;
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: __('Error: ' . $e->getMessage()));
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingAppointmentId = null;
        $this->isEditing = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->patientId = '';
        $this->doctorId = '';
        $this->appointmentDate = '';
        $this->appointmentTime = '';
        $this->reason = '';
        $this->notes = '';
        $this->isEditing = false;
        $this->editingAppointmentId = null;
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

    public function confirmMarkNoShow($appointmentId)
    {
        $this->confirmAppointmentId = $appointmentId;
        $this->confirmAction = 'noShow';
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
                'noShow' => $this->markNoShow($appointment),
                default => null,
            };

            $this->showConfirmDialog = false;
            $this->confirmAppointmentId = null;
            $this->confirmAction = '';

            $this->dispatch('refreshAppointments');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: __('Error: ' . $e->getMessage()));
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
        event(new \App\Events\AppointmentCompleted($appointment));
        $this->dispatch('notify', type: 'success', message: __('Appointment marked as completed. Visit created.'));
    }

    private function markCancelled($appointment)
    {
        $appointment->update(['status' => 'cancelled']);
        $this->dispatch('notify', type: 'success', message: __('Appointment cancelled.'));
    }

    private function markNoShow($appointment)
    {
        $appointment->update(['status' => 'no-show']);
        $this->dispatch('notify', type: 'success', message: __('Appointment marked as no-show.'));
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

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->doctorFilter = '';
        $this->dateFilter = '';
        $this->sortBy = 'appointment_date';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    private function loadInitialData()
    {
        // Initialize if needed
    }

    public function render()
    {
        return view('livewire.appointments.index', [
            'appointments' => $this->getFilteredAppointments(),
            'doctors' => Doctor::orderBy('name')->get(),
            'statuses' => [
                'scheduled' => __('Scheduled'),
                'completed' => __('Completed'),
                'cancelled' => __('Cancelled'),
                'no-show' => __('No Show'),
            ],
        ]);
    }
}
