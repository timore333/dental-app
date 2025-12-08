<?php

namespace App\Livewire\Dashboards;

use Livewire\Component;
use App\Models\Payment;
use App\Models\PatientAccount;
use App\Models\InsuranceRequest;
use Carbon\Carbon;

class AccountantDashboard extends Component
{
    public $fromDate;
    public $toDate;
    public $dateRange = '30days';

    public function mount()
    {
        $this->setDateRange('30days');
    }

    public function setDateRange($range)
    {
        $this->dateRange = $range;

        $today = Carbon::now();
        match($range) {
            '7days' => [
                $this->fromDate => $today->copy()->subDays(7)->startOfDay(),
                $this->toDate => $today->endOfDay(),
            ],
            '30days' => [
                $this->fromDate => $today->copy()->subDays(30)->startOfDay(),
                $this->toDate => $today->endOfDay(),
            ],
            '90days' => [
                $this->fromDate => $today->copy()->subDays(90)->startOfDay(),
                $this->toDate => $today->endOfDay(),
            ],
            'yearly' => [
                $this->fromDate => $today->copy()->startOfYear(),
                $this->toDate => $today->endOfYear(),
            ],
            default => [
                $this->fromDate => $today->copy()->subDays(30)->startOfDay(),
                $this->toDate => $today->endOfDay(),
            ]
        };
    }

    public function getFinancialMetrics()
    {
        $completedPayments = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate]);

        $cashPayments = Payment::where('status', 'completed')
            ->where('payment_method', 'cash')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate]);

        $insurancePayments = Payment::where('status', 'completed')
            ->where('payment_method', 'insurance')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate]);

        return [
            'total_revenue' => $completedPayments->sum('amount'),
            'cash_received' => $cashPayments->sum('amount'),
            'insurance_amount' => $insurancePayments->sum('amount'),
            'outstanding_amount' => PatientAccount::sum('balance'),
            'pending_insurance' => InsuranceRequest::where('status', 'pending')->count(),
            'pending_insurance_amount' => InsuranceRequest::where('status', 'pending')->sum('estimated_cost'),
        ];
    }

    public function getChartData()
    {
        // Revenue by Source
        $revenueBySource = \DB::table('payments')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        // Monthly Revenue Trend
        $monthlyRevenue = \DB::table('payments')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Payment Method Breakdown
        $paymentMethods = \DB::table('payments')
            ->where('status', 'completed')
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

    public function getOutstandingBills()
    {
        return \DB::table('patient_accounts')
            ->join('patients', 'patient_accounts.patient_id', '=', 'patients.id')
            ->where('patient_accounts.balance', '>', 0)
            ->selectRaw('patients.name, patient_accounts.balance, patient_accounts.updated_at')
            ->orderByDesc('balance')
            ->limit(10)
            ->get();
    }

    public function getRecentPayments()
    {
        return Payment::where('status', 'completed')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->with('appointment.patient')
            ->latest()
            ->limit(10)
            ->get();
    }

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
