<?php

namespace App\Http\Requests\Patients;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
        return [
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:patients,phone',
            'email' => 'nullable|email|unique:patients,email',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'job' => 'nullable|string|max:100',
            'category' => 'required|in:normal,exacting,vip,special',
            'type' => 'required|in:cash,insurance',
            'insurance_company_id' => 'required_if:type,insurance|exists:insurance_companies,id',
            'insurance_card_number' => 'required_if:type,insurance|string|max:50',
            'insurance_policyholder' => 'required_if:type,insurance|string|max:100',
            'insurance_expiry_date' => 'required_if:type,insurance|date|after:today',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => __('validation.required', ['attribute' => __('patient.first_name')]),
            'first_name.string' => __('validation.string', ['attribute' => __('patient.first_name')]),
            'first_name.max' => __('validation.max.string', ['attribute' => __('patient.first_name'), 'max' => 100]),

            'last_name.required' => __('validation.required', ['attribute' => __('patient.last_name')]),
            'last_name.string' => __('validation.string', ['attribute' => __('patient.last_name')]),
            'last_name.max' => __('validation.max.string', ['attribute' => __('patient.last_name'), 'max' => 100]),

            'phone.required' => __('validation.required', ['attribute' => __('patient.phone')]),
            'phone.regex' => __('validation.regex', ['attribute' => __('patient.phone')]),
            'phone.min' => __('validation.min.string', ['attribute' => __('patient.phone'), 'min' => 10]),
            'phone.unique' => __('validation.unique', ['attribute' => __('patient.phone')]),

            'email.email' => __('validation.email', ['attribute' => __('patient.email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('patient.email')]),

            'date_of_birth.date' => __('validation.date', ['attribute' => __('patient.date_of_birth')]),
            'date_of_birth.before' => __('validation.before', ['attribute' => __('patient.date_of_birth')]),

            'gender.in' => __('validation.in', ['attribute' => __('patient.gender')]),

            'category.required' => __('validation.required', ['attribute' => __('patient.category')]),
            'category.in' => __('validation.in', ['attribute' => __('patient.category')]),

            'type.required' => __('validation.required', ['attribute' => __('patient.type')]),
            'type.in' => __('validation.in', ['attribute' => __('patient.type')]),

            'insurance_company_id.required_if' => __('validation.required_if', ['attribute' => __('patient.insurance_company_id'), 'other' => 'Insurance']),
            'insurance_company_id.exists' => __('validation.exists', ['attribute' => __('patient.insurance_company_id')]),

            'insurance_card_number.required_if' => __('validation.required_if', ['attribute' => __('patient.insurance_card_number'), 'other' => 'Insurance']),
            'insurance_card_number.string' => __('validation.string', ['attribute' => __('patient.insurance_card_number')]),
            'insurance_card_number.max' => __('validation.max.string', ['attribute' => __('patient.insurance_card_number'), 'max' => 50]),

            'insurance_policyholder.required_if' => __('validation.required_if', ['attribute' => __('patient.insurance_policyholder'), 'other' => 'Insurance']),
            'insurance_policyholder.string' => __('validation.string', ['attribute' => __('patient.insurance_policyholder')]),
            'insurance_policyholder.max' => __('validation.max.string', ['attribute' => __('patient.insurance_policyholder'), 'max' => 100]),

            'insurance_expiry_date.required_if' => __('validation.required_if', ['attribute' => __('patient.insurance_expiry_date'), 'other' => 'Insurance']),
            'insurance_expiry_date.date' => __('validation.date', ['attribute' => __('patient.insurance_expiry_date')]),
            'insurance_expiry_date.after' => __('validation.after', ['attribute' => __('patient.insurance_expiry_date')]),

            'address.max' => __('validation.max.string', ['attribute' => __('patient.address'), 'max' => 500]),
            'city.max' => __('validation.max.string', ['attribute' => __('patient.city'), 'max' => 100]),
            'country.max' => __('validation.max.string', ['attribute' => __('patient.country'), 'max' => 100]),
            'job.max' => __('validation.max.string', ['attribute' => __('patient.job'), 'max' => 100]),
            'notes.max' => __('validation.max.string', ['attribute' => __('patient.notes'), 'max' => 1000]),
        ];
    }
}
