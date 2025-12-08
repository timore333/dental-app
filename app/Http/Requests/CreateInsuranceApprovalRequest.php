<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInsuranceApprovalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
            'insurance_request_id' => 'required|exists:insurance_requests,id',
            'approved_procedures' => 'array|nullable',
            'approved_procedures.*' => 'exists:procedures,id',
            'rejected_procedures' => 'array|nullable',
            'rejected_procedures.*' => 'exists:procedures,id',
            'approved_amount' => 'required|numeric|min:0',
            'approval_notes' => 'nullable|string|max:1000',
            'approval_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }
}
