<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Collection;

class PaymentHistoryComponent extends Component
{
    use WithPagination;

    public $billId = null;
    public $patientId = null;
    public $paymentMethod = '';
    public $searchReference = '';
    public $fromDate = '';
    public $toDate = '';
    public $sortBy = 'payment_date';
    public $sortDirection = 'desc';
    public $perPage = 15;

    protected $queryString = ['paymentMethod', 'searchReference', 'fromDate', 'toDate'];

    public function getPaymentsProperty()
    {
        $query = Payment::with('bill', 'patient')
            ->where('status', 'completed');

        if ($this->billId) {
            $query->where('bill_id', $this->billId);
        }

        if ($this->patientId) {
            $query->where('patient_id', $this->patientId);
        }

        if ($this->paymentMethod) {
            $query->where('payment_method', $this->paymentMethod);
        }

        if ($this->searchReference) {
            $query->where('reference_number', 'like', "%{$this->searchReference}%")
                  ->orWhere('receipt_number', 'like', "%{$this->searchReference}%");
        }

        if ($this->fromDate) {
            $query->whereDate('payment_date', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->whereDate('payment_date', '<=', $this->toDate);
        }

        return $query->orderBy($this->sortBy, $this->sortDirection)
                    ->paginate($this->perPage);
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function reversePayment($paymentId)
    {
        try {
            $payment = Payment::findOrFail($paymentId);
            app(\App\Services\PaymentService::class)->reversePayment($payment);

            session()->flash('message', __('messages.payment_reversed'));
            $this->dispatch('refreshPayments');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportExcel()
    {
        return app(\App\Services\PaymentExportService::class)->exportToExcel([
            'payment_method' => $this->paymentMethod,
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
        ]);
    }

    public function render()
    {
        return view('livewire.payments.payment-history-component', [
            'payments' => $this->payments,
        ]);
    }
}
