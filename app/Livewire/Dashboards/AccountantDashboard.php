<?php

namespace App\Livewire\Dashboards;

use App\Models\Account;
use App\Models\Bill;
use App\Models\InsuranceRequest;
use App\Models\Payment;
use App\Models\PatientAccount;
use App\Traits\hasDateRange;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Accountant Dashboard')]
class AccountantDashboard extends Component
{
    use hasDateRange;

    public function mount(): void
    {
        $this->setDateRange('30days');
    }


    /**
     * Get financial metrics
     */
    public function getFinancialMetrics(): array
    {
        $completedPayments = Payment::whereBetween('created_at', [$this->fromDate, $this->toDate]);
        $cashPayments = Payment::where('payment_method', 'cash')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate]);
        $cardPayments = Payment::where('payment_method', 'card')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate]);
        $chequePayments = Payment::where('payment_method', 'cheque')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate]);
        $bankTransferPayments = Payment::where('payment_method', 'bank_transfer')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate]);

        return [
            'total_revenue' => $completedPayments->sum('amount') ?? 0,
            'cash_received' => $cashPayments->sum('amount') ?? 0,
            'card_received' => $cardPayments->sum('amount') ?? 0,
            'cheque_received' => $chequePayments->sum('amount') ?? 0,
            'bank_transfer_received' => $bankTransferPayments->sum('amount') ?? 0,
            'outstanding_amount' => Account::sum('balance') ?? 0,
            'pending_insurance' => InsuranceRequest::where('status', 'pending')->count(),
//            'pending_insurance_amount' => InsuranceRequest::where('status', 'pending')->sum('estimated_cost') ?? 0,
            'total_payments_count' => $completedPayments->count(),
        ];
    }

    /**
     * Get chart data
     */
    public function getChartData(): array
    {
        // Revenue by payment method
        $revenueBySource = Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        // Daily revenue trend
        $dailyRevenue = Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Payment method breakdown with count
        $paymentMethods = Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get()
            ->mapWithKeys(fn($item) => [$item->payment_method => $item->total]);

        // Outstanding bills by patient
        $outstandingByPatient = DB::table('bills')
            ->join('patients', 'bills.patient_id', '=', 'patients.id')
            ->whereRaw('(bills.total_amount - bills.paid_amount) > 0')
            ->selectRaw('patients.first_name, (bills.total_amount - bills.paid_amount) as balance')
            ->orderByDesc('balance')
            ->limit(10)
            ->pluck('balance', 'first_name');


        return [
            'revenueBySource' => [
                'labels' => array_keys($revenueBySource->toArray()),
                'data' => array_values($revenueBySource->toArray()),
            ],
            'dailyRevenue' => [
                'labels' => array_keys($dailyRevenue->toArray()),
                'data' => array_values($dailyRevenue->toArray()),
            ],
            'paymentMethods' => [
                'labels' => $paymentMethods->keys()->toArray(),
                'data' => $paymentMethods->values()->toArray(),
            ],
            'outstandingByPatient' => [
                'labels' => array_keys($outstandingByPatient->toArray()),
                'data' => array_values($outstandingByPatient->toArray()),
            ],
        ];
    }

    /**
     * Get outstanding bills
     */
    public function getOutstandingBills()
    {
        return Bill::with('patient')
            ->whereRaw('(total_amount - paid_amount) > 0')
            ->orderByDesc(DB::raw('total_amount - paid_amount'))
            ->limit(10)
            ->get();

    }

    /**
     * Get recent payments
     */
    public function getRecentPayments()
    {
        return Payment::with(['bill.patient'])
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.dashboards.accountant-dashboard', [
            'metrics' => $this->getFinancialMetrics(),
            'chartData' => $this->getChartData(),
            'outstandingBills' => $this->getOutstandingBills(),
            'recentPayments' => $this->getRecentPayments(),
        ]);
    }
}
