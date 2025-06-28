<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getAllProductsWithInventory()
    {
        return Product::with('inventory')->get();
    }
}