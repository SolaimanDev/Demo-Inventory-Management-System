<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
   public function index()
    {
        $products = Product::with('inventory')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'opening_stock' => 'required|integer|min:0'
        ]);

        $product = Product::create($validated);
        
        Inventory::create([
            'product_id' => $product->id,
            'opening_stock' => $validated['opening_stock'],
            'current_stock' => $validated['opening_stock']
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0'
        ]);

        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
