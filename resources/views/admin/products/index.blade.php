@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
</div>
</div>

<div class="row">
    <div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Purchase Price</th>
                    <th>Sell Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->purchase_price, 2) }}</td>
                    <td>{{ number_format($product->sell_price, 2) }}</td>
                    <td>{{ $product->inventory->current_stock }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
@endsection