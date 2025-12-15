<?php

namespace App\Livewire\Patients;

use App\Models\Patient;
use App\Models\InsuranceCompany;
use App\Services\PatientService;
use Livewire\Component;
use Livewire\Attributes\Validate;

class Create extends Component
{
    // ==================== PROPERTIES ====================

    #[Validate('required|string|max:100')]
    public string $first_name = '';

    #[Validate('nullable|string|max:100')]
    public ?string $middle_name = null;

    #[Validate('required|string|max:100')]
    public string $last_name = '';

    #[Validate('required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:patients,phone')]
    public string $phone = '';

    #[Validate('nullable|email|unique:patients,email')]
    public ?string $email = null;

    #[Validate('nullable|date|before:today')]
    public ?string $date_of_birth = null;

    #[Validate('nullable|in:male,female')]
    public ?string $gender = null;

    #[Validate('nullable|string|max:500')]
    public ?string $address = null;

    #[Validate('nullable|string|max:100')]
    public ?string $city = null;

    #[Validate('nullable|string|max:100')]
    public ?string $country = null;

    #[Validate('nullable|string|max:100')]
    public ?string $job = null;

    #[Validate('required|in:normal,exacting,vip,special')]
    public string $category = 'normal';

    #[Validate('required|in:cash,insurance')]
    public string $type = 'cash';

    // Insurance fields
    #[Validate('required_if:type,insurance|exists:insurance_companies,id')]
    public ?int $insurance_company_id = null;

    #[Validate('required_if:type,insurance|string|max:50')]
    public ?string $insurance_card_number = null;

    #[Validate('required_if:type,insurance|string|max:100')]
    public ?string $insurance_policyholder = null;

    #[Validate('required_if:type,insurance|date|after:today')]
    public ?string $insurance_expiry_date = null;

    #[Validate('nullable|string|max:1000')]
    public ?string $notes = null;

    public $insuranceCompanies;

    // ==================== MOUNT ====================

    /**
     * Mount the component and load data
     */
    public function mount()
    {
        $this->insuranceCompanies = InsuranceCompany::get();
    }

    // ==================== RENDER ====================

    /**
     * Render the view
     */
    public function render()
    {
        return view('livewire.patients.create', [
            'categories' => Patient::CATEGORIES,
            'genders' => Patient::GENDERS,
            'paymentTypes' => Patient::PAYMENT_TYPES,
        ]);
    }

    // ==================== CRUD ====================

    /**
     * Create a new patient using request validation
     *
     * @param PatientService $patientService
     * @return mixed
     */
    public function save(PatientService $patientService)
    {
        \Log::info('Type: ' . $this->type);
        \Log::info('Insurance Company ID: ' . $this->insurance_company_id);
        // DEBUG: Log component state BEFORE validation
        \Log::info('=== PATIENT FORM SUBMISSION ===', [
            'type' => $this->type,
            'insurance_company_id' => $this->insurance_company_id,
            'insurance_card_number' => $this->insurance_card_number,
            'insurance_policyholder' => $this->insurance_policyholder,
            'insurance_expiry_date' => $this->insurance_expiry_date,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'all_properties' => get_object_vars($this),
        ]);

        $rules = (new \App\Http\Requests\Patients\StorePatientRequest())->rules();

        // DEBUG: Log validation rules
        \Log::info('=== VALIDATION RULES ===', $rules);

        // Validate form data using request rules
        $validated = $this->validate($rules);
dump($this->resetErrorBag());

        try {

            // Create patient using service
            $patient = $patientService->createPatient($validated);

            // Dispatch event for listeners
            $this->dispatch('patientCreated', patientId: $patient->id);

            // Flash success message using translation
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

    // ==================== LISTENERS ====================

    /**
     * Clear insurance fields when switching to cash
     */
    public function updatedType($value): void
    {
        // ✅ FIXED: Complete method
        if ($value === 'cash') {
            $this->insurance_company_id = null;
            $this->insurance_card_number = null;
            $this->insurance_policyholder = null;
            $this->insurance_expiry_date = null;

            $this->resetErrorBag([
                'insurance_company_id',
                'insurance_card_number',
                'insurance_policyholder',
                'insurance_expiry_date'
            ]);
        }
    }
}
