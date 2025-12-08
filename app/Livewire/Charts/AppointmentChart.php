<?php

namespace App\Livewire\Charts;

use Livewire\Component;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentChart extends Component
{
    public $fromDate;
    public $toDate;

    public function mount()
    {
        $this->fromDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');
    }

    public function getChartData()
    {
        $dateFrom = Carbon::parse($this->fromDate)->startOfDay();
        $dateTo = Carbon::parse($this->toDate)->endOfDay();

        $data = Appointment::whereBetween('appointment_date', [$dateFrom, $dateTo])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'labels' => array_keys($data->toArray()),
            'datasets' => [
                [
                    'label' => 'Appointments',
                    'data' => array_values($data->toArray()),
                    'backgroundColor' => ['#10b981', '#3b82f6', '#ef4444', '#f59e0b'],
                ]
            ]
        ];
    }

    public function render()
    {
        return view('livewire.charts.appointment-chart', [
            'chartData' => $this->getChartData(),
        ]);
    }
}
