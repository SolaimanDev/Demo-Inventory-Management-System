<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\AccountingEntry;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\SalesReportService;
use App\Services\ProfitReportService;

class ReportController extends Controller
{
    protected $salesReportService;
    protected $profitReportService;

    public function __construct(
        SalesReportService $salesReportService,
        ProfitReportService $profitReportService
    ) {
        $this->salesReportService = $salesReportService;
        $this->profitReportService = $profitReportService;
    }

    public function salesReport(Request $request)
    {
        // Get default dates if not provided
        $defaultDates = $this->salesReportService->getDefaultDateRange();
        
        // Validate if dates are provided, otherwise use defaults
        if ($request->has(['start_date', 'end_date'])) {
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);
            
            $startDate = $validated['start_date'];
            $endDate = $validated['end_date'];
        } else {
            $startDate = $defaultDates['start_date'];
            $endDate = $defaultDates['end_date'];
        }
        
        try {
            $sales = $this->salesReportService->generateReport($startDate, $endDate);
            
            return view('admin.reports.sales', [
                'sales' => $sales,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'default_start' => $defaultDates['start_date'],
                'default_end' => $defaultDates['end_date']
            ]);

        } catch (\Exception $e) {
            Log::error("Sales Report Error: " . $e->getMessage());
            return back()->with('error', 'Could not generate sales report: ' . $e->getMessage());
        }
    }
    
    public function profitReport(Request $request)
    {
        // Get default dates if not provided
        $defaultDates = $this->profitReportService->getDefaultDateRange();
        
        // Validate if dates are provided, otherwise use defaults
        if ($request->has(['start_date', 'end_date'])) {
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);
            
            $startDate = $validated['start_date'];
            $endDate = $validated['end_date'];
        } else {
            $startDate = $defaultDates['start_date'];
            $endDate = $defaultDates['end_date'];
        }
        
        try {
            $report = $this->profitReportService->generateReport($startDate, $endDate);
            
            return view('admin.reports.profits', [
                'report' => $report,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'default_start' => $defaultDates['start_date'],
                'default_end' => $defaultDates['end_date']
            ]);

        } catch (\Exception $e) {
            Log::error("Profit Report Error: " . $e->getMessage());
            return back()->with('error', 'Could not generate profit report: ' . $e->getMessage());
        }
    }
}
