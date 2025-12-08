<?php

namespace App\Livewire\Charts;

use Livewire\Component;
use Carbon\Carbon;

class ProcedureChart extends Component
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

        $data = \DB::table('visits')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->join('visit_procedures', 'visits.id', '=', 'visit_procedures.visit_id')
            ->join('procedures', 'visit_procedures.procedure_id', '=', 'procedures.id')
            ->selectRaw('procedures.name, COUNT(*) as count')
            ->groupBy('procedures.id', 'procedures.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return [
            'labels' => $data->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Procedure Count',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#14b8a6', '#f97316', '#6366f1'],
                ]
            ]
        ];
    }

    public function render()
    {
        return view('livewire.charts.procedure-chart', [
            'chartData' => $this->getChartData(),
        ]);
    }
}
