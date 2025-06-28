<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountingEntry;
use Carbon\Carbon;

class ProfitReportService
{
    public function generateReport($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get sales account
        $salesAccount = Account::where('name', 'Sales')->firstOrFail();

        // Get sales revenue
        $salesRevenue = abs(AccountingEntry::where('account_id', $salesAccount->id)
            ->whereBetween('entry_date', [$start, $end])
            ->sum('credit_amount'));

        // Calculate COGS (50% of sales revenue for simplicity)
        $cogs = $salesRevenue * 0.5;

        // Get expenses
        $expenseAccounts = Account::where('type', 'expense')->pluck('id');
        $expenses = abs(AccountingEntry::whereIn('account_id', $expenseAccounts)
            ->whereBetween('entry_date', [$start, $end])
            ->sum('debit_amount'));

        return [
            'sales_revenue' => $salesRevenue,
            'cost_of_goods_sold' => $cogs,
            'gross_profit' => $salesRevenue - $cogs,
            'expenses' => $expenses,
            'net_profit' => ($salesRevenue - $cogs) - $expenses
        ];
    }

    public function getDefaultDateRange()
    {
        return [
            'start_date' => now()->subDays(30)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d')
        ];
    }
}