<?php

namespace App\Livewire\Dashboards;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Visit;
use App\Traits\hasDateRange;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Doctor Dashboard')]
class DoctorDashboard extends Component
{
    use hasDateRange;

    public function mount(): void
    {
        $this->setDateRange('30days');
    }


    /**
     * Get doctor dashboard metrics
     */
    public function getMetrics(): array
    {
        $doctor = auth()->user();

        return [
            'total_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->whereBetween('start', [$this->fromDate, $this->toDate])
                ->count(),
            'completed_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->whereBetween('start', [$this->fromDate, $this->toDate])
                ->where('status', 'completed')
                ->count(),
            'total_visits' => Visit::where('doctor_id', $doctor->id)
                ->whereBetween('created_at', [$this->fromDate, $this->toDate])
                ->count(),
            'total_earnings' => Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])
                    ->sum('amount') ?? 0,
            'pending_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'pending')
                ->count(),
            'total_payment_count' => Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])
                ->count(),
        ];
    }

    /**
     * Get chart data for dashboard
     */
    public function getChartData(): array
    {
        $doctor = auth()->user();

        // Appointment Status Chart
        $appointmentStatus = Appointment::where('doctor_id', $doctor->id)
            ->whereBetween('start', [$this->fromDate, $this->toDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Payment method distribution
        $earningsByMethod = Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        // Procedures performed by logged-in doctor
        $proceduresPerformed = DB::table('visits')
            ->where('visits.doctor_id', $doctor->id)
            ->whereBetween('visits.created_at', [$this->fromDate, $this->toDate])
            ->join('visit_procedures', 'visits.id', '=', 'visit_procedures.visit_id')
            ->join('procedures', 'visit_procedures.procedure_id', '=', 'procedures.id')
            ->selectRaw('procedures.name, COUNT(*) as count')
            ->groupBy('procedures.id', 'procedures.name')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'name');

        // Appointments Over Time
        $appointmentsOverTime = Appointment::where('doctor_id', $doctor->id)
            ->whereBetween('start', [$this->fromDate, $this->toDate])
            ->selectRaw('DATE(start) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        return [
            'appointmentStatus' => [
                'labels' => array_keys($appointmentStatus->toArray()),
                'data' => array_values($appointmentStatus->toArray()),
            ],
            'earningsByMethod' => [
                'labels' => array_keys($earningsByMethod->toArray()),
                'data' => array_values($earningsByMethod->toArray()),
            ],
            'procedures' => [
                'labels' => array_keys($proceduresPerformed->toArray()),
                'data' => array_values($proceduresPerformed->toArray()),
            ],
            'appointmentsOverTime' => [
                'labels' => array_keys($appointmentsOverTime->toArray()),
                'data' => array_values($appointmentsOverTime->toArray()),
            ],
        ];
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.dashboards.doctor-dashboard', [
            'metrics' => $this->getMetrics(),
            'chartData' => $this->getChartData(),
        ]);
    }
}
