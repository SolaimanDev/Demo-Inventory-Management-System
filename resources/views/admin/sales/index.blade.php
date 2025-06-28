@extends('layouts.app')

@section('title', 'Sales')

@section('content')
<div class="container-fluid">
<div class="row">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Sales</h1>
    <a href="{{ route('sales.create') }}" class="btn btn-primary">New Sale</a>
</div>
</div>
<div class="row">


<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Paid</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                    <td>{{ number_format($sale->total_amount, 2) }}</td>
                    <td>{{ number_format($sale->paid_amount, 2) }}</td>
                    <td>{{ number_format($sale->due_amount, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $sale->status === 'paid' ? 'success' : ($sale->status === 'partial' ? 'warning' : 'danger') }}">
                            {{ ucfirst($sale->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection