<?php

namespace App\Services;

use App\Models\Sale;
use Carbon\Carbon;

class SalesReportService
{
    public function generateReport($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        return Sale::with(['items.product'])
            ->whereBetween('sale_date', [$start, $end])
            ->orderBy('sale_date', 'desc')
            ->get();
    }

    public function getDefaultDateRange()
    {
        return [
            'start_date' => now()->subDays(30)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d')
        ];
    }
}