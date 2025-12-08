<?php

namespace App\Livewire\Charts;

use Livewire\Component;
use Carbon\Carbon;

class RevenueChart extends Component
{
    public $fromDate;
    public $toDate;
    public $chartType = 'line';

    public function mount()
    {
        $this->fromDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');
    }

    public function getChartData()
    {
        $dateFrom = Carbon::parse($this->fromDate)->startOfDay();
        $dateTo = Carbon::parse($this->toDate)->endOfDay();

        $data = \DB::table('payments')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->toArray(),
            'datasets' => [
                [
                    'label' => 'Daily Revenue',
                    'data' => $data->pluck('total')->toArray(),
                    'borderColor' => '#06b6d4',
                    'backgroundColor' => 'rgba(6, 182, 212, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ]
            ]
        ];
    }

    public function render()
    {
        return view('livewire.charts.revenue-chart', [
            'chartData' => $this->getChartData(),
        ]);
    }
}
