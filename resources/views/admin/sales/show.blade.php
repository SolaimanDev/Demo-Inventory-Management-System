@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')
<div class="container-fluid">

<div class="row">
<div class="card">
    <div class="card-header">Sale Details</div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</p>

                <p><strong>Status:</strong> 
                    <span class="badge bg-{{ $sale->status === 'paid' ? 'success' : ($sale->status === 'partial' ? 'warning' : 'danger') }}">
                        {{ ucfirst($sale->status) }}
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <p><strong>Total Amount:</strong> {{ number_format($sale->total_amount, 2) }}</p>
                <p><strong>Paid Amount:</strong> {{ number_format($sale->paid_amount, 2) }}</p>
                <p><strong>Due Amount:</strong> {{ number_format($sale->due_amount, 2) }}</p>
            </div>
        </div>

        <h5>Products</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                    <td>{{ number_format($sale->total_amount - $sale->vat_amount, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>Discount:</strong></td>
                    <td>{{ number_format($sale->discount_amount, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>VAT (5%):</strong></td>
                    <td>{{ number_format($sale->vat_amount, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td>{{ number_format($sale->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        @if($sale->due_amount > 0)
        <div class="mt-4">
            <h5>Add Payment</h5>
            <form method="POST" action="{{ route('sales.payments.store', $sale->id) }}">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" 
                               max="{{ $sale->due_amount }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="payment_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Record Payment</button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        <div class="mt-4">
            <h5>Accounting Entries</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Account</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->accountingEntries as $entry)
                    <tr>
                        <td>{{ $entry->account->name }}</td>
                        <td>{{ $entry->debit_amount > 0 ? number_format($entry->debit_amount, 2) : '' }}</td>
                        <td>{{ $entry->credit_amount > 0 ? number_format($entry->credit_amount, 2) : '' }}</td>
                        <td>{{ $entry->description }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back to Sales</a>
        </div>
    </div>
</div>
</div>
</div>
@endsection