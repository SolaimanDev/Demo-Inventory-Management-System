@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')
<div class="container-fluid">
<div class="card">
    <div class="card-header">Sales Report</div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.sales') }}" class="mb-4">
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
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('reports.sales') }}" class="btn btn-secondary ms-2">Reset</a>
                </div>
            </div>
        </form>

        @if($sales->isEmpty())
            <div class="alert alert-info">No sales found for the selected date range.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Invoice #</th>
                            <th>Total Amount</th>
                            <th>Discount</th>
                            <th>VAT</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr>
                            <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $sale->id }}</td>
                            <td>{{ number_format($sale->total_amount, 2) }}</td>
                            <td>{{ number_format($sale->discount_amount, 2) }}</td>
                            <td>{{ number_format($sale->vat_amount, 2) }}</td>
                            <td>{{ number_format($sale->paid_amount, 2) }}</td>
                            <td>{{ number_format($sale->due_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $sale->status === 'paid' ? 'success' : ($sale->status === 'partial' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
</div>
</div>
@endsection