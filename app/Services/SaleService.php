<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function createSale(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Calculate sale totals
            $subtotal = 0;
            $items = [];
            
            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);
                $unitPrice = $product->sell_price;
                $totalPrice = $unitPrice * $item['quantity'];
                
                $items[] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice
                ];
                
                $subtotal += $totalPrice;
                
                // Check stock availability
                $inventory = Inventory::where('product_id', $item['product_id'])->first();
                if ($inventory->current_stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product ID: {$item['product_id']}");
                }
            }
            
            $discount = $data['discount_amount'] ?? 0;
            $vat = ($subtotal - $discount) * 0.05; // 5% VAT
            $total = ($subtotal - $discount) + $vat;
            $paid = $data['paid_amount'];
            $due = $total - $paid;
            
            // Create sale
            $sale = Sale::create([
                'sale_date' => Carbon::now(),
                'total_amount' => $total,
                'discount_amount' => $discount,
                'vat_amount' => $vat,
                'paid_amount' => $paid,
                'due_amount' => $due,
                'status' => $due > 0 ? ($paid > 0 ? 'partial' : 'unpaid') : 'paid'
            ]);
            
            // Create sale items and update inventory
            foreach ($items as $item) {
                $sale->items()->create($item);
                
                // Update inventory
                $inventory = Inventory::where('product_id', $item['product_id'])->first();
                $inventory->current_stock -= $item['quantity'];
                $inventory->save();
            }
            
            return $sale;
        });
    }

    public function getAllSales()
    {
        return Sale::with('items.product')->latest()->get();
    }

    public function getSaleWithDetails($id)
    {
        return Sale::with('items.product', 'accountingEntries.account')->findOrFail($id);
    }
}