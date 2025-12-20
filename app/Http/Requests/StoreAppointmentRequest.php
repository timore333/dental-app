<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'start' => 'required|date_format:Y-m-d\TH:i|after:now',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public array $messages = [
        'patient_id.required' => 'dental.validation.patient_required',
        'patient_id.exists' => 'dental.validation.patient_invalid',
        'start.required' => 'dental.validation.date_required',
        'start.after' => 'dental.validation.date_must_be_future',
        'reason.required' => 'dental.validation.reason_required',
        'reason.max' => 'dental.validation.reason_max',
        'notes.max' => 'dental.validation.notes_max',
    ];

}

?>
