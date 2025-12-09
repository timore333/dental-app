<?php

namespace App\Livewire\Dashboards;

use App\Models\InsuranceRequest;
use App\Models\PatientAccount;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AccountantDashboard extends Component
{
    public string $dateRange = '30days';
    public ?\DateTime $fromDate = null;
    public ?\DateTime $toDate = null;

    public function mount(): void
    {
        $this->setDateRange('30days');
    }

    /**
     * Set the date range for metrics
     */
    public function setDateRange(string $range): void
    {
        $this->dateRange = $range;
        $today = Carbon::now();

        match($range) {
            '7days' => [
                $this->fromDate = $today->copy()->subDays(7)->startOfDay(),
                $this->toDate = $today->endOfDay(),
            ],
            '30days' => [
                $this->fromDate = $today->copy()->subDays(30)->startOfDay(),
                $this->toDate = $today->endOfDay(),
            ],
            '90days' => [
                $this->fromDate = $today->copy()->subDays(90)->startOfDay(),
                $this->toDate = $today->endOfDay(),
            ],
            'yearly' => [
                $this->fromDate = $today->copy()->startOfYear(),
                $this->toDate = $today->endOfYear(),
            ],
            default => [
                $this->fromDate = $today->copy()->subDays(30)->startOfDay(),
                $this->toDate = $today->endOfDay(),
            ],
        };
    }

    /**
     * Get financial metrics
     */
    public function getFinancialMetrics(): array
    {
        // ✅ FIXED: Query payments directly (they represent actual completed payments)
        $completedPayments = Payment::whereBetween('created_at', [$this->fromDate, $this->toDate]);

        $cashPayments = Payment::where('payment_method', 'cash')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate]);

        $insurancePayments = Payment::where('payment_method', 'insurance')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate]);

        return [
            'total_revenue' => $completedPayments->sum('amount') ?? 0,
            'cash_received' => $cashPayments->sum('amount') ?? 0,
            'insurance_amount' => $insurancePayments->sum('amount') ?? 0,
            'outstanding_amount' => PatientAccount::sum('balance') ?? 0,
            'pending_insurance' => InsuranceRequest::where('status', 'pending')->count(),
            'pending_insurance_amount' => InsuranceRequest::where('status', 'pending')->sum('estimated_cost') ?? 0,
        ];
    }

    /**
     * Get chart data
     */
    public function getChartData(): array
    {
        // ✅ FIXED: Revenue by payment method (no status filter)
        $revenueBySource = DB::table('payments')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        // ✅ FIXED: Monthly revenue trend
        $monthlyRevenue = DB::table('payments')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // ✅ FIXED: Payment method breakdown
        $paymentMethods = DB::table('payments')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get()
            ->mapWithKeys(fn($item) => [$item->payment_method => $item->total]);

        return [
            'revenueBySource' => [
                'labels' => array_keys($revenueBySource->toArray()),
                'data' => array_values($revenueBySource->toArray()),
            ],
            'monthlyRevenue' => [
                'labels' => array_keys($monthlyRevenue->toArray()),
                'data' => array_values($monthlyRevenue->toArray()),
            ],
            'paymentMethods' => [
                'labels' => $paymentMethods->keys()->toArray(),
                'data' => $paymentMethods->values()->toArray(),
            ],
        ];
    }

    /**
     * Get outstanding bills
     */
    public function getOutstandingBills()
    {
        return DB::table('patient_accounts')
            ->join('patients', 'patient_accounts.patient_id', '=', 'patients.id')
            ->where('patient_accounts.balance', '>', 0)
            ->selectRaw('patients.name, patient_accounts.balance, patient_accounts.updated_at')
            ->orderByDesc('balance')
            ->limit(10)
            ->get();
    }

    /**
     * Get recent payments
     */
    public function getRecentPayments()
    {
        return Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])
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
