<?php

namespace App\Livewire\Patients;

use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // ==================== PROPERTIES ====================

    public $search = '';
    public $filterCategory = 'all';
    public $filterCity = '';
    public $filterJob = '';
    public $filterGender = 'all';
    public $filterType = 'all';
    public $filterFromDate = '';
    public $filterToDate = '';

    public $sortField = 'first_name';
    public $sortDirection = 'desc';
    public $perPage = 100;

    public $selectedPatient = null;
    public $showViewModal = false;
    public $showDeleteConfirm = false;
    public $patientId = null;

    // ==================== LISTENERS ====================

    protected $listeners = [
        'patientCreated' => 'onPatientCreated',
        'patientUpdated' => 'onPatientUpdated',
    ];

    // ==================== LIFECYCLE ====================

    public function mount()
    {
        // Initialize filters from session or defaults
        $this->search = session('patient_search', '');
        $this->filterCategory = session('patient_category', 'all');
    }

    public function render()
    {
        return view('livewire.patients.index', [
            'patients' => $this->getFilteredPatients(),
            'categories' => Patient::CATEGORIES,
            'genders' => Patient::GENDERS,
            'paymentTypes' => Patient::PAYMENT_TYPES,
        ]);
    }

    // ==================== QUERY ====================

    private function getFilteredPatients()
    {
        return Patient::active()
            ->search($this->search)
            ->byCategory($this->filterCategory)
            ->byCity($this->filterCity)
            ->byJob($this->filterJob)
            ->byGender($this->filterGender)
            ->byType($this->filterType)
            ->byBirthDateRange($this->filterFromDate, $this->filterToDate)
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    // ==================== VIEW MODAL ====================

    public function openViewModal(Patient $patient)
    {
        $this->selectedPatient = $patient;
        $this->showViewModal = true;
        $this->dispatch('modal-opened');
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedPatient = null;
    }

    // ==================== DELETE CONFIRMATION ====================

    public function openDeleteConfirm(Patient $patient)
    {
        $this->patientId = $patient->id;
        $this->selectedPatient = $patient;
        $this->showDeleteConfirm = true;
    }

    public function closeDeleteConfirm()
    {
        $this->showDeleteConfirm = false;
        $this->patientId = null;
        $this->selectedPatient = null;
    }

    public function deletePatient()
    {
        try {
            $patient = Patient::findOrFail($this->patientId);
            $patientName = $patient->full_name;
            $patient->delete();

            session()->flash('message', "Patient '{$patientName}' deleted successfully!");
            $this->closeDeleteConfirm();
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting patient: ' . $e->getMessage());
        }
    }

    // ==================== FILTER METHODS ====================

    public function updatedSearch()
    {
        session()->put('patient_search', $this->search);
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        session()->put('patient_category', $this->filterCategory);
        $this->resetPage();
    }

    public function updatedFilterCity()
    {
        $this->resetPage();
    }

    public function updatedFilterJob()
    {
        $this->resetPage();
    }

    public function updatedFilterGender()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterFromDate()
    {
        $this->resetPage();
    }

    public function updatedFilterToDate()
    {
        $this->resetPage();
    }

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterCategory = 'all';
        $this->filterCity = '';
        $this->filterJob = '';
        $this->filterGender = 'all';
        $this->filterType = 'all';
        $this->filterFromDate = '';
        $this->filterToDate = '';
        session()->forget(['patient_search', 'patient_category']);
        $this->resetPage();
    }

    // ==================== LISTENERS ====================

    public function onPatientCreated()
    {
        $this->resetFilters();
        session()->flash('message', 'Patient created successfully!');
    }

    public function onPatientUpdated()
    {
        $this->resetFilters();
        session()->flash('message', 'Patient updated successfully!');
    }
}
