<?php

namespace App\Traits;

use Carbon\Carbon;

trait hasDateRange
{

    public string $dateRange = '30days';
    public Carbon $fromDate;
    public Carbon $toDate;

    /**
     * Set the date range for metrics
     */
    public function setDateRange(string $range = '30days'): void
    {
        $this->dateRange = $range;
        $today = Carbon::now();

        match ($range) {
            '7days' => [
                $this->fromDate = $today->copy()->subDays(7)->startOfDay(),
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
     * Format chart data from query results
     */
    protected function formatChartData(array $data): array
    {
        return [
            'labels' => array_keys($data),
            'data' => array_values($data),
        ];
    }
}
