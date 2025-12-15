<?php

namespace App\Http\Requests\Patients;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $patientId = $this->route('patient')?->id ?? $this->patient?->id;

        return [
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:patients,phone,{$patientId}",
            'email' => "nullable|email|unique:patients,email,{$patientId}",
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
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required',
            'first_name.string' => 'First name must be a text',
            'first_name.max' => 'First name cannot exceed 100 characters',

            'last_name.required' => 'Last name is required',
            'last_name.string' => 'Last name must be a text',
            'last_name.max' => 'Last name cannot exceed 100 characters',

            'phone.required' => 'Phone number is required',
            'phone.regex' => 'Phone number format is invalid',
            'phone.min' => 'Phone number must be at least 10 digits',
            'phone.unique' => 'This phone number is already registered',

            'email.email' => 'Email format is invalid',
            'email.unique' => 'This email is already registered',

            'date_of_birth.date' => 'Date of birth must be a valid date',
            'date_of_birth.before' => 'Date of birth must be in the past',

            'gender.in' => 'Gender must be male, female, or other',

            'category.required' => 'Category is required',
            'category.in' => 'Category must be normal, exacting, vip, or special',

            'type.required' => 'Payment type is required',
            'type.in' => 'Payment type must be cash or insurance',

            'insurance_company_id.exists' => 'Selected insurance company does not exist',
            'insurance_expiry_date.after' => 'Insurance expiry date must be in the future',

            'address.max' => 'Address cannot exceed 500 characters',
            'notes.max' => 'Notes cannot exceed 1000 characters',
        ];
    }
}
