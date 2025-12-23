<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bill_id' => 'nullable|exists:bills,id',
            'patient_id' => 'required_if:bill_id,null|exists:patients,id',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'payment_method' => 'required|in:' . implode(',', config('payment.payment_methods')),
            'payment_date' => 'required|date|before_or_equal:today',
            'reference_number' => 'nullable|string|max:255|unique:payments,reference_number',
            'notes' => 'nullable|string|max:500',
            'apply_advance_credit' => 'nullable|boolean',
            'advance_credit_id' => 'nullable|exists:patient_advance_credits,id',
            'is_advance_payment' => 'nullable|boolean',
            'expiry_date' => 'nullable|date|after_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => __('validation.payment_amount_required'),
            'amount.min' => __('validation.payment_amount_min'),
            'payment_method.required' => __('validation.payment_method_required'),
            'payment_date.before_or_equal' => __('validation.payment_date_not_future'),
            'reference_number.unique' => __('validation.reference_number_exists'),
        ];
    }
}
