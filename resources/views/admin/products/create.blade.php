@extends('layouts.app')

@section('title', isset($product) ? 'Edit Product' : 'Add Product')

@section('content')
<div class="container-fluid">
<div class="row">
<div class="card">
    <div class="card-header">{{ isset($product) ? 'Edit' : 'Add' }} Product</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="purchase_price" class="form-label">Purchase Price</label>
                    <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price ?? '') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="sell_price" class="form-label">Sell Price</label>
                    <input type="number" step="0.01" class="form-control" id="sell_price" name="sell_price" value="{{ old('sell_price', $product->sell_price ?? '') }}" required>
                </div>
            </div>

            @if(!isset($product))
                <div class="mb-3">
                    <label for="opening_stock" class="form-label">Opening Stock</label>
                    <input type="number" class="form-control" id="opening_stock" name="opening_stock" value="{{ old('opening_stock', 0) }}" required>
                </div>
            @endif

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
</div>
</div>
@endsection