@extends('layouts.app')

@section('title', 'New Sale')

@section('content')
<div class="container-fluid">
<div class="row">
<div class="card">
    <div class="card-header">New Sale</div>
    <div class="card-body">
        <form method="POST" action="{{ route('sales.store') }}">
            @csrf

            <div class="mb-4">
                <h5>Products</h5>
                <div id="product-items">
                    <div class="product-item row mb-3">
                        <div class="col-md-5">
                            <select class="form-select product-select" name="items[0][product_id]" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->sell_price }}" data-stock="{{ $product->inventory->current_stock }}">
                                    {{ $product->name }} (Stock: {{ $product->inventory->current_stock }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control quantity" name="items[0][quantity]" min="1" value="1" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control unit-price" readonly>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control total-price" readonly>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-item">X</button>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-item" class="btn btn-sm btn-secondary">Add Product</button>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="discount_amount" class="form-label">Discount</label>
                    <input type="number" step="0.01" class="form-control" id="discount_amount" name="discount_amount" value="0" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">VAT (5%)</label>
                    <input type="number" class="form-control" id="vat_amount" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Subtotal</label>
                    <input type="number" class="form-control" id="subtotal" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Total Amount</label>
                    <input type="number" class="form-control" id="total_amount" name="total_amount" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="paid_amount" class="form-label">Paid Amount</label>
                    <input type="number" step="0.01" class="form-control" id="paid_amount" name="paid_amount" value="0" min="0" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Due Amount</label>
                    <input type="number" class="form-control" id="due_amount" readonly>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Complete Sale</button>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
</div>
</div>

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = 1;
    
    // Add new product item
    document.getElementById('add-item').addEventListener('click', function() {
        const newItem = document.querySelector('.product-item').cloneNode(true);
        const newIndex = itemCount++;
        
        // Update all names and reset values
        newItem.querySelectorAll('[name]').forEach(el => {
            const name = el.getAttribute('name').replace(/\[\d+\]/, `[${newIndex}]`);
            el.setAttribute('name', name);
        });
        
        // Reset values
        newItem.querySelector('.product-select').selectedIndex = 0;
        newItem.querySelector('.quantity').value = 1;
        newItem.querySelector('.unit-price').value = '';
        newItem.querySelector('.total-price').value = '';
        
        document.getElementById('product-items').appendChild(newItem);
        setupItemEvents(newItem);
    });
    
    // Setup events for initial item
    document.querySelectorAll('.product-item').forEach(setupItemEvents);
    
    // Setup discount, paid amount events
    document.getElementById('discount_amount').addEventListener('input', calculateTotals);
    document.getElementById('paid_amount').addEventListener('input', function() {
        const total = parseFloat(document.getElementById('total_amount').value) || 0;
        const paid = parseFloat(this.value) || 0;
        document.getElementById('due_amount').value = (total - paid).toFixed(2);
    });
    
    function setupItemEvents(item) {
        const productSelect = item.querySelector('.product-select');
        const quantityInput = item.querySelector('.quantity');
        const unitPriceInput = item.querySelector('.unit-price');
        const totalPriceInput = item.querySelector('.total-price');
        const removeBtn = item.querySelector('.remove-item');
        
        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            unitPriceInput.value = price;
            calculateItemTotal(this);
            calculateTotals();
        });
        
        quantityInput.addEventListener('input', function() {
            calculateItemTotal(this);
            calculateTotals();
        });
        
        removeBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.product-item').length > 1) {
                item.remove();
                calculateTotals();
            }
        });
        
        function calculateItemTotal(input) {
            const itemRow = input.closest('.product-item');
            const quantity = parseFloat(itemRow.querySelector('.quantity').value) || 0;
            const unitPrice = parseFloat(itemRow.querySelector('.unit-price').value) || 0;
            itemRow.querySelector('.total-price').value = (quantity * unitPrice).toFixed(2);
        }
    }
    
    function calculateTotals() {
        let subtotal = 0;
        
        document.querySelectorAll('.product-item').forEach(item => {
            const total = parseFloat(item.querySelector('.total-price').value) || 0;
            subtotal += total;
        });
        
        const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
        const vat = (subtotal - discount) * 0.05;
        const total = (subtotal - discount) + vat;
        
        document.getElementById('subtotal').value = subtotal.toFixed(2);
        document.getElementById('vat_amount').value = vat.toFixed(2);
        document.getElementById('total_amount').value = total.toFixed(2);
        
        // Update due amount based on paid amount
        const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
        document.getElementById('due_amount').value = (total - paid).toFixed(2);
    }
});
</script>
@endpush
