<?php

namespace App\Services;

use App\Models\{Account, Appointment, Bill, InsuranceRequest, Patient, Payment, User, Visit};
use AllowDynamicProperties;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[AllowDynamicProperties] class Metrics
{
    protected Carbon $fromDate;
    protected Carbon $toDate;

    public function __construct(Carbon $fromDate = null, Carbon $toDate = null)
    {
        $this->fromDate = $fromDate ?? Carbon::now()->subDays(30)->startOfDay();
        $this->toDate = $toDate ?? Carbon::now()->endOfDay();


        $this->total_patients = Patient::count();
        $this->total_appointments = Appointment::whereBetween('start', [$this->fromDate, $this->toDate])->count();
        $this->completed_appointments = Appointment::whereBetween('start', [$this->fromDate, $this->toDate])->where('status', 'completed')->count();
        $this->total_revenue = Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])->sum('amount') ?? 0;
        $this->pending_insurance = InsuranceRequest::where('status', 'pending')->count();
        $this->active_doctors = User::doctor()->count();
        $this->total_payments_count = Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])->count();
        $this->pending_approvals = InsuranceRequest::where('status', 'pending')->count();
        
    }

    /**
     * Set custom date range
     */
    public static function setDateRange(string $range = '30days'): self
    {
        $today = Carbon::now();

        $dates = match ($range) {
            '7days' => [
                'from' => $today->copy()->subDays(7)->startOfDay(),
                'to' => $today->endOfDay(),
            ],
            '90days' => [
                'from' => $today->copy()->subDays(90)->startOfDay(),
                'to' => $today->endOfDay(),
            ],
            'yearly' => [
                'from' => $today->copy()->startOfYear(),
                'to' => $today->endOfYear(),
            ],
            default => [
                'from' => $today->copy()->subDays(30)->startOfDay(),
                'to' => $today->endOfDay(),
            ],
        };

        return new self($dates['from'], $dates['to']);
    }

    /**
     * Get admin dashboard metrics
     */
    public function getAdminMetrics(): array
    {
        return [
            'total_patients' => $this->total_patients,
            'total_appointments' => $this->total_appointments,
            'completed_appointments' => $this->completed_appointments,
            'total_revenue' => $this->total_revenue,
            'pending_insurance' => $this->pending_insurance,
            'active_doctors' => $this->active_doctors,
            'total_payments_count' => $this->total_payments_count,
            'pending_approvals' => $this->pending_approvals,
        ];
    }

    /**
     * Get doctor dashboard metrics
     */
    public function getDoctorMetrics(?int $doctorId = null): array
    {
        $doctorId = $doctorId ?? auth()->id();

        return [
            'total_appointments' => Appointment::where('doctor_id', $doctorId)
                ->whereBetween('start', [$this->fromDate, $this->toDate])
                ->count(),
            'completed_appointments' => Appointment::where('doctor_id', $doctorId)
                ->whereBetween('start', [$this->fromDate, $this->toDate])
                ->where('status', 'completed')
                ->count(),
            'total_visits' => Visit::where('doctor_id', $doctorId)
                ->whereBetween('created_at', [$this->fromDate, $this->toDate])
                ->count(),
            'total_earnings' => Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])
                    ->sum('amount') ?? 0,
            'pending_appointments' => Appointment::where('doctor_id', $doctorId)
                ->where('status', 'pending')
                ->count(),
            'total_payment_count' => Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])->count(),
        ];
    }

    /**
     * Get receptionist dashboard metrics
     */
    public function getReceptionistMetrics(): array
    {
        return [
            'total_patients' => Patient::count(),
            'appointments_today' => Appointment::whereDate('start', Carbon::today())->count(),
            'revenue_month' => Payment::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('amount'),
            'pending_payments' => Bill::where('total_amount', '>', 0)->count(),
            'total_appointments' => Appointment::whereBetween('start', [$this->fromDate, $this->toDate])->count(),
            'scheduled_appointments' => Appointment::whereDate('start', '>=', Carbon::today())
                ->where('status', 'scheduled')
                ->count(),
            'completed_appointments' => Appointment::whereBetween('start', [$this->fromDate, $this->toDate])
                ->where('status', 'completed')
                ->count(),
            'new_patients' => Patient::whereBetween('created_at', [$this->fromDate, $this->toDate])->count(),
            'pending_insurance_requests' => InsuranceRequest::where('status', 'pending')->count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'total_payments_today' => Payment::whereDate('created_at', Carbon::today())->count(),
        ];
    }

    /**
     * Get accountant dashboard metrics
     */
    public function getAccountantMetrics(): array
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
            'total_payments_count' => $completedPayments->count(),
        ];
    }

    /**
     * Get from date
     */
    public function getFromDate(): Carbon
    {
        return $this->fromDate;
    }

    /**
     * Get to date
     */
    public function getToDate(): Carbon
    {
        return $this->toDate;
    }
}
