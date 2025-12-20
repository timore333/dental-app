<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $appointment = $this->route('appointment');

        return [
            'patient_id' => [
                'required',
                'integer',
                'exists:patients,id',
            ],
            'doctor_id' => [
                'nullable',
                'integer',
                'exists:doctors,id',
            ],
            'start' => [
                'required',
                'date_format:Y-m-d\TH:i',
                'after_or_equal:now',
                Rule::unique('appointments', 'start')
                    ->ignore($appointment->id)
                    ->where('doctor_id', $this->input('doctor_id')),
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'status' => [
                'nullable',
                Rule::in('scheduled', 'completed', 'cancelled', 'no-show'),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'patient_id.required' => __('dental.patient_required'),
            'patient_id.exists' => __('dental.patient_not_found'),
            'doctor_id.exists' => __('dental.doctor_not_found'),
            'start.required' => __('dental.appointment_date_required'),
            'start.date_format' => __('dental.appointment_date_format_invalid'),
            'start.after_or_equal' => __('dental.appointment_must_be_future'),
            'start.unique' => __('dental.time_slot_conflict'),
            'notes.max' => __('dental.notes_max_1000_chars'),
            'status.in' => __('dental.invalid_status'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert time input format if needed
        if ($this->has('start')) {
            $this->merge([
                'start' => str_replace('T', ' ', $this->input('start')),
            ]);
        }
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'patient_id' => __('dental.patient'),
            'doctor_id' => __('dental.doctor'),
            'start' => __('dental.appointment_date_time'),
            'notes' => __('dental.notes'),
            'status' => __('dental.status'),
        ];
    }
}
