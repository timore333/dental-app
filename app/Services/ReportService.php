<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Appointment;
use App\Models\Visit;
use App\Models\InsuranceRequest;
use Carbon\Carbon;

class ReportService
{
    /**
     * Get financial report data
     */
    public function getFinancialReport($fromDate, $toDate, $type = 'summary')
    {
        $dateFrom = Carbon::parse($fromDate)->startOfDay();
        $dateTo = Carbon::parse($toDate)->endOfDay();

        return match($type) {
            'summary' => $this->getFinancialSummary($dateFrom, $dateTo),
            'detailed' => $this->getFinancialDetailed($dateFrom, $dateTo),
            'by-procedure' => $this->getFinancialByProcedure($dateFrom, $dateTo),
            'by-insurance' => $this->getFinancialByInsurance($dateFrom, $dateTo),
            default => $this->getFinancialSummary($dateFrom, $dateTo)
        };
    }

    private function getFinancialSummary($fromDate, $toDate)
    {
        return Payment::where('status', 'completed')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as amount, payment_method as type, "completed" as status')
            ->groupBy('date', 'payment_method')
            ->orderBy('date', 'desc')
            ->get()
            ->map(fn($item) => [
                'date' => $item->date,
                'description' => 'Payment (' . ucfirst($item->type) . ')',
                'amount' => $item->amount,
                'type' => $item->type,
                'status' => $item->status,
            ]);
    }

    private function getFinancialDetailed($fromDate, $toDate)
    {
        return Payment::where('status', 'completed')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->with('appointment.patient', 'appointment.doctor')
            ->get()
            ->map(fn($payment) => [
                'date' => $payment->created_at->format('Y-m-d'),
                'description' => 'Payment from ' . ($payment->appointment?->patient->name ?? 'Unknown'),
                'amount' => $payment->amount,
                'type' => $payment->payment_method,
                'status' => $payment->status,
            ]);
    }

    private function getFinancialByProcedure($fromDate, $toDate)
    {
        return \DB::table('payments')
            ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
            ->join('visits', 'appointments.id', '=', 'visits.appointment_id')
            ->join('visit_procedures', 'visits.id', '=', 'visit_procedures.visit_id')
            ->join('procedures', 'visit_procedures.procedure_id', '=', 'procedures.id')
            ->where('payments.status', 'completed')
            ->whereBetween('payments.created_at', [$fromDate, $toDate])
            ->selectRaw('procedures.name as description, SUM(payments.amount) as amount, payments.payment_method as type, "completed" as status, DATE(payments.created_at) as date')
            ->groupBy('procedures.id', 'procedures.name', 'payments.payment_method')
            ->orderByDesc('amount')
            ->get()
            ->map(fn($item) => (array) $item);
    }

    private function getFinancialByInsurance($fromDate, $toDate)
    {
        return \DB::table('payments')
            ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
            ->join('insurance_requests', 'appointments.id', '=', 'insurance_requests.appointment_id')
            ->join('insurance_companies', 'insurance_requests.insurance_company_id', '=', 'insurance_companies.id')
            ->where('payments.status', 'completed')
            ->where('payments.payment_method', 'insurance')
            ->whereBetween('payments.created_at', [$fromDate, $toDate])
            ->selectRaw('insurance_companies.name as description, SUM(payments.amount) as amount, "insurance" as type, "completed" as status, DATE(payments.created_at) as date')
            ->groupBy('insurance_companies.id', 'insurance_companies.name')
            ->orderByDesc('amount')
            ->get()
            ->map(fn($item) => (array) $item);
    }

    /**
     * Get patient report data
     */
    public function getPatientReport($fromDate, $toDate, $type = 'demographics')
    {
        $dateFrom = Carbon::parse($fromDate)->startOfDay();
        $dateTo = Carbon::parse($toDate)->endOfDay();

        return match($type) {
            'demographics' => $this->getPatientDemographics($dateFrom, $dateTo),
            'activity' => $this->getPatientActivity($dateFrom, $dateTo),
            'financial' => $this->getPatientFinancial($dateFrom, $dateTo),
            default => $this->getPatientDemographics($dateFrom, $dateTo)
        };
    }

    private function getPatientDemographics($fromDate, $toDate)
    {
        return \DB::table('patients')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('age, gender, COUNT(*) as count')
            ->groupBy('age', 'gender')
            ->get();
    }

    private function getPatientActivity($fromDate, $toDate)
    {
        return \DB::table('patients')
            ->leftJoin('appointments', 'patients.id', '=', 'appointments.patient_id')
            ->whereBetween('patients.created_at', [$fromDate, $toDate])
            ->selectRaw('patients.name, COUNT(DISTINCT appointments.id) as appointment_count')
            ->groupBy('patients.id', 'patients.name')
            ->get();
    }

    private function getPatientFinancial($fromDate, $toDate)
    {
        return \DB::table('patients')
            ->leftJoin('payments', 'patients.id', '=', 'payments.patient_id')
            ->whereBetween('patients.created_at', [$fromDate, $toDate])
            ->selectRaw('patients.name, SUM(COALESCE(payments.amount, 0)) as total_spent')
            ->groupBy('patients.id', 'patients.name')
            ->orderByDesc('total_spent')
            ->get();
    }

    /**
     * Get insurance report data
     */
    public function getInsuranceReport($fromDate, $toDate, $type = 'summary')
    {
        $dateFrom = Carbon::parse($fromDate)->startOfDay();
        $dateTo = Carbon::parse($toDate)->endOfDay();

        return match($type) {
            'summary' => $this->getInsuranceSummary($dateFrom, $dateTo),
            'requests' => $this->getInsuranceRequests($dateFrom, $dateTo),
            'performance' => $this->getInsurancePerformance($dateFrom, $dateTo),
            default => $this->getInsuranceSummary($dateFrom, $dateTo)
        };
    }

    private function getInsuranceSummary($fromDate, $toDate)
    {
        return \DB::table('insurance_requests')
            ->join('insurance_companies', 'insurance_requests.insurance_company_id', '=', 'insurance_companies.id')
            ->whereBetween('insurance_requests.created_at', [$fromDate, $toDate])
            ->selectRaw('insurance_companies.name, COUNT(*) as total_requests, SUM(estimated_cost) as total_amount')
            ->groupBy('insurance_companies.id', 'insurance_companies.name')
            ->get();
    }

    private function getInsuranceRequests($fromDate, $toDate)
    {
        return InsuranceRequest::whereBetween('created_at', [$fromDate, $toDate])
            ->with('appointment.patient', 'insuranceCompany')
            ->get();
    }

    private function getInsurancePerformance($fromDate, $toDate)
    {
        return \DB::table('insurance_requests')
            ->join('insurance_companies', 'insurance_requests.insurance_company_id', '=', 'insurance_companies.id')
            ->whereBetween('insurance_requests.created_at', [$fromDate, $toDate])
            ->selectRaw('
                insurance_companies.name,
                COUNT(*) as total_requests,
                SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_count,
                ROUND(SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) as approval_rate
            ')
            ->groupBy('insurance_companies.id', 'insurance_companies.name')
            ->get();
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics($doctorId = null, $fromDate = null, $toDate = null)
    {
        $query = \DB::table('appointments')
            ->where('status', 'completed');

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        if ($fromDate && $toDate) {
            $dateFrom = Carbon::parse($fromDate)->startOfDay();
            $dateTo = Carbon::parse($toDate)->endOfDay();
            $query->whereBetween('appointment_date', [$dateFrom, $dateTo]);
        }

        return $query->selectRaw('
            doctor_id,
            COUNT(*) as total_appointments,
            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_count
        ')
        ->groupBy('doctor_id')
        ->get();
    }

    /**
     * Get dashboard metrics
     */
    public function getDashboardMetrics($role, $fromDate = null, $toDate = null)
    {
        $dateFrom = $fromDate ? Carbon::parse($fromDate)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $dateTo = $toDate ? Carbon::parse($toDate)->endOfDay() : Carbon::now()->endOfDay();

        return match($role) {
            'admin' => $this->getAdminMetrics($dateFrom, $dateTo),
            'doctor' => $this->getDoctorMetrics($dateFrom, $dateTo),
            'receptionist' => $this->getReceptionistMetrics($dateFrom, $dateTo),
            'accountant' => $this->getAccountantMetrics($dateFrom, $dateTo),
            default => []
        };
    }

    private function getAdminMetrics($fromDate, $toDate)
    {
        return [
            'total_patients' => \App\Models\Patient::count(),
            'total_appointments' => Appointment::whereBetween('appointment_date', [$fromDate, $toDate])->count(),
            'total_revenue' => Payment::where('status', 'completed')->whereBetween('created_at', [$fromDate, $toDate])->sum('amount'),
            'pending_insurance' => InsuranceRequest::where('status', 'pending')->count(),
        ];
    }

    private function getDoctorMetrics($fromDate, $toDate)
    {
        $doctorId = auth()->id();
        return [
            'today_appointments' => Appointment::where('doctor_id', $doctorId)->whereBetween('appointment_date', [$fromDate, $toDate])->count(),
            'completed_visits' => Visit::where('doctor_id', $doctorId)->whereBetween('created_at', [$fromDate, $toDate])->count(),
        ];
    }

    private function getReceptionistMetrics($fromDate, $toDate)
    {
        return [
            'today_appointments' => Appointment::whereBetween('appointment_date', [$fromDate, $toDate])->count(),
            'pending_insurance' => InsuranceRequest::where('status', 'pending')->count(),
        ];
    }

    private function getAccountantMetrics($fromDate, $toDate)
    {
        return [
            'total_revenue' => Payment::where('status', 'completed')->whereBetween('created_at', [$fromDate, $toDate])->sum('amount'),
            'outstanding_amount' => \App\Models\PatientAccount::sum('balance'),
        ];
    }
}
