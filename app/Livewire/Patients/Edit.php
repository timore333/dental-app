<?php

namespace App\Livewire\Patients;

use App\Http\Requests\Patients\UpdatePatientRequest;
use App\Models\Patient;
use App\Services\PatientService;
use Livewire\Component;
use Exception;

class Edit extends Component
{
    // ==================== PROPERTIES ====================

    public Patient $patient;

    public $first_name = '';
    public $middle_name = '';
    public $last_name = '';
    public $phone = '';
    public $email = '';
    public $date_of_birth = '';
    public $gender = '';
    public $address = '';
    public $city = '';
    public $country = '';
    public $job = '';
    public $category = '';
    public $type = 'cash';
    public $insurance_company_id = '';
    public $insurance_card_number = '';
    public $insurance_policyholder = '';
    public $insurance_expiry_date = '';
    public $notes = '';
    public $is_active = true;

    // ==================== VALIDATION RULES ====================

    protected $rules = [
        'first_name' => 'required|string|max:100',
        'middle_name' => 'nullable|string|max:100',
        'last_name' => 'required|string|max:100',
        'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'email' => 'nullable|email',
        'date_of_birth' => 'nullable|date|before:today',
        'gender' => 'nullable|in:male,female,other',
        'address' => 'nullable|string|max:500',
        'city' => 'nullable|string|max:100',
        'country' => 'nullable|string|max:100',
        'job' => 'nullable|string|max:100',
        'category' => 'required|in:normal,exacting,vip,special',
        'type' => 'required|in:cash,insurance',
        'insurance_company_id' => 'nullable|exists:insurance_companies,id',
        'insurance_card_number' => 'nullable|string|max:50',
        'insurance_policyholder' => 'nullable|string|max:100',
        'insurance_expiry_date' => 'nullable|date|after:today',
        'notes' => 'nullable|string|max:1000',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'first_name.required' => 'First name is required',
        'last_name.required' => 'Last name is required',
        'phone.required' => 'Phone is required',
        'date_of_birth.before' => 'Date of birth must be in the past',
        'category.required' => 'Category is required',
        'type.required' => 'Payment type is required',
    ];

    // ==================== LIFECYCLE ====================

    public function mount(Patient $patient)
    {
        $this->patient = $patient;
        $this->first_name = $patient->first_name;
        $this->middle_name = $patient->middle_name;
        $this->last_name = $patient->last_name;
        $this->phone = $patient->phone;
        $this->email = $patient->email;
        $this->date_of_birth = $patient->date_of_birth?->format('Y-m-d');
        $this->gender = $patient->gender;
        $this->address = $patient->address;
        $this->city = $patient->city;
        $this->country = $patient->country;
        $this->job = $patient->job;
        $this->category = $patient->category;
        $this->type = $patient->type;
        $this->insurance_company_id = $patient->insurance_company_id;
        $this->insurance_card_number = $patient->insurance_card_number;
        $this->insurance_policyholder = $patient->insurance_policyholder;
        $this->insurance_expiry_date = $patient->insurance_expiry_date?->format('Y-m-d');
        $this->notes = $patient->notes;
        $this->is_active = $patient->is_active;
    }

    public function render()
    {
        return view('livewire.patients.edit', [
            'categories' => Patient::CATEGORIES,
            'genders' => Patient::GENDERS,
            'paymentTypes' => Patient::PAYMENT_TYPES,
        ]);
    }

    // ==================== CRUD ====================

    public function save(PatientService $service)
    {
        $this->validate();

        try {
            // Update patient using service
            $service->updatePatient($this->patient, $this->getFormData());

            // Dispatch event for listeners
            $this->dispatch('patientUpdated');

            session()->flash('message', 'Patient updated successfully!');
            return redirect()->route('patients.index');
        } catch (Exception $e) {
            $this->addError('general', $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('patients.index');
    }

    // ==================== HELPERS ====================

    private function getFormData(): array
    {
        return [
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'date_of_birth' => $this->date_of_birth ?: null,
            'gender' => $this->gender ?: null,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'job' => $this->job,
            'category' => $this->category,
            'type' => $this->type,
            'insurance_company_id' => $this->insurance_company_id ?: null,
            'insurance_card_number' => $this->insurance_card_number,
            'insurance_policyholder' => $this->insurance_policyholder,
            'insurance_expiry_date' => $this->insurance_expiry_date ?: null,
            'notes' => $this->notes,
            'is_active' => (bool) $this->is_active,
        ];
    }
}
