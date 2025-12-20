<?php

namespace App\Livewire\Patients;

use App\Models\Patient;
use App\Models\InsuranceCompany;
use App\Services\PatientService;
use Livewire\Component;
use Livewire\Attributes\Validate;

class Create extends Component
{
    #[Validate('required|string|max:100')]
    public $first_name = '';

    #[Validate('nullable|string|max:100')]
    public $middle_name = '';

    #[Validate('required|string|max:100')]
    public $last_name = '';

    #[Validate('required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:patients,phone')]
    public $phone = '';

    #[Validate('nullable|email|unique:patients,email')]
    public $email = '';

    #[Validate('nullable|date|before:today')]
    public $date_of_birth = '';

    #[Validate('in:male,female,other')]
    public $gender = 'male';

    #[Validate('nullable|string|max:500')]
    public $address = '';

    #[Validate('nullable|string|max:100')]
    public $city = '';

    #[Validate('nullable|string|max:100')]
    public $country = '';

    #[Validate('nullable|string|max:100')]
    public $job = '';

    #[Validate('required|in:normal,exacting,vip,special')]
    public $category = 'normal';

    #[Validate('required|in:cash,insurance')]
    public $type = 'cash';

    // Insurance fields - make them nullable
    #[Validate('nullable|exists:insurance_companies,id')]
    public $insurance_company_id = null;

    #[Validate('nullable|string|max:50')]
    public $insurance_card_number =null;

    #[Validate('nullable|string|max:100')]
    public $insurance_policyholder =null;

    #[Validate('nullable|date|after:today')]
    public $insurance_expiry_date =null;

    #[Validate('nullable|string|max:1000')]
    public $notes = '';

    public $insuranceCompanies = [];

    public function mount()
    {
        $this->insuranceCompanies = InsuranceCompany::get();
    }

    public function render()
    {
        return view('livewire.patients.create', [
            'categories' => Patient::CATEGORIES,
            'genders' => Patient::GENDERS,
            'paymentTypes' => Patient::PAYMENT_TYPES,
        ]);
    }

    /**
     * Handle type change - clear insurance fields when switching to cash
     */
    #[On('typeChanged')]
    public function updatedType($value): void
    {
        if ($value === 'cash') {
            // Clear all insurance fields
            $this->insurance_company_id = null;
            $this->insurance_card_number = '';
            $this->insurance_policyholder = '';
            $this->insurance_expiry_date = '';

            // Clear validation errors for insurance fields
            $this->resetErrorBag([
                'insurance_company_id',
                'insurance_card_number',
                'insurance_policyholder',
                'insurance_expiry_date',
            ]);
        }
    }

    /**
     * Create a new patient using request validation
     */
    public function save(PatientService $patientService)
    {
        // Use the Form Request for validation
        $rules = (new \App\Http\Requests\Patients\StorePatientRequest())->rules();

        // Validate all data
        $validated = $this->validate($rules);


        try {
            // Create patient using service
            $patient = $patientService->createPatient($validated);

            // Dispatch event for listeners
            $this->dispatch('patientCreated', patientId: $patient->id);

            // Flash success message
            session()->flash('message', __('messages.patient.created'));
            session()->flash('type', 'success');

            // Redirect to patient list
            return redirect()->route('patients.index');
        } catch (\Exception $e) {

            // Flash error message
            session()->flash('message', 'Failed to create patient: ' . $e->getMessage());
            session()->flash('type', 'error');

            // Log the error
            \Log::error('Failed to create patient in Livewire Create component', [
                'error' => $e->getMessage(),
                'data' => $validated,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Cancel creation and redirect back
     */
    public function cancel()
    {
        return redirect()->route('patients.index');
    }
}
