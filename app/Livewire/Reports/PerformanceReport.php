<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Visit;
use Carbon\Carbon;

class PerformanceReport extends Component
{
    public $fromDate;
    public $toDate;
    public $doctorId = null;
    public $metricType = 'appointments';

    public function mount()
    {
        $this->fromDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');
    }

    public function getMetricsData()
    {
        $doctors = User::where('role', 'doctor')->get();

        if ($this->doctorId) {
            $doctors = $doctors->where('id', $this->doctorId);
        }

        return $doctors->map(function ($doctor) {
            $appointmentQuery = Appointment::where('doctor_id', $doctor->id)
                ->whereBetween('appointment_date', [$this->fromDate, $this->toDate]);

            $totalAppointments = $appointmentQuery->count();
            $completedAppointments = $appointmentQuery->where('status', 'completed')->count();
            $noShowCount = $appointmentQuery->where('status', 'no-show')->count();

            $visitCount = Visit::where('doctor_id', $doctor->id)
                ->whereBetween('created_at', [$this->fromDate, $this->toDate])
                ->count();

            $totalRevenue = \DB::table('payments')
                ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
                ->where('appointments.doctor_id', $doctor->id)
                ->whereBetween('payments.created_at', [$this->fromDate, $this->toDate])
                ->where('payments.status', 'completed')
                ->sum('payments.amount');

            return [
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->name,
                'total_appointments' => $totalAppointments,
                'completed_appointments' => $completedAppointments,
                'no_show_count' => $noShowCount,
                'no_show_rate' => $totalAppointments > 0 ? round(($noShowCount / $totalAppointments) * 100, 2) : 0,
                'visit_count' => $visitCount,
                'total_revenue' => $totalRevenue,
                'revenue_per_appointment' => $completedAppointments > 0 ? round($totalRevenue / $completedAppointments, 2) : 0,
            ];
        })->toArray();
    }

    public function exportPDF()
    {
        $data = $this->getMetricsData();
        // PDF export logic
        return response()->download('export_performance_' . date('Y-m-d') . '.pdf');
    }

    public function render()
    {
        return view('livewire.reports.performance-report', [
            'metricsData' => $this->getMetricsData(),
            'doctors' => User::where('role', 'doctor')->get(),
        ]);
    }
}
