<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'nullable|in:' . implode(',', config('payment.payment_methods')),
            'payment_date' => 'nullable|date|before_or_equal:today',
            'reference_number' => 'nullable|string|max:255|unique:payments,reference_number,' . $this->route('payment')->id,
        ];
    }
}
