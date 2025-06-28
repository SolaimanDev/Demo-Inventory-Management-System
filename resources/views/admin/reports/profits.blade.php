@extends('layouts.app')

@section('title', 'Profit Report')

@section('content')
<div class="container-fluid">
<div class="row">


<div class="card">
    <div class="card-header">Profit Report</div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.profit') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ old('start_date', $start_date ?? $default_start) }}" required>
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ old('end_date', $end_date ?? $default_end) }}" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Generate</button>
                    <a href="{{ route('reports.profit') }}" class="btn btn-secondary ms-2">Reset</a>
                </div>
            </div>
        </form>

        @if(isset($report))
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Profit Summary</h5>
                        <table class="table">
                            <tr>
                                <th>Sales Revenue</th>
                                <td class="text-end">{{ number_format($report['sales_revenue'], 2) }}</td>
                            </tr>
                            <tr>
                                <th>Cost of Goods Sold</th>
                                <td class="text-end">{{ number_format($report['cost_of_goods_sold'], 2) }}</td>
                            </tr>
                            <tr class="table-primary">
                                <th>Gross Profit</th>
                                <td class="text-end">{{ number_format($report['gross_profit'], 2) }}</td>
                            </tr>
                            <tr>
                                <th>Expenses</th>
                                <td class="text-end">{{ number_format($report['expenses'], 2) }}</td>
                            </tr>
                            <tr class="table-success">
                                <th>Net Profit</th>
                                <td class="text-end">{{ number_format($report['net_profit'], 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Profit Breakdown</h5>
                        <canvas id="profitChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
</div>
</div>

@push('scripts')
@if(isset($report))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('profitChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Sales Revenue', 'COGS', 'Gross Profit', 'Expenses', 'Net Profit'],
            datasets: [{
                label: 'Amount',
                data: [
                    {{ $report['sales_revenue'] }},
                    {{ -$report['cost_of_goods_sold'] }},
                    {{ $report['gross_profit'] }},
                    {{ -$report['expenses'] }},
                    {{ $report['net_profit'] }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(255, 159, 64, 0.5)',
                    'rgba(153, 102, 255, 0.5)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'USD'
                                }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endif
@endpush
@endsection