<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Account;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Services\SaleService;
use App\Models\AccountingEntry;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use App\Services\AccountingService;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{
    protected $saleService;
    protected $accountingService;
    protected $productService;

    public function __construct(
        SaleService $saleService,
        AccountingService $accountingService,
        ProductService $productService
    ) {
        $this->saleService = $saleService;
        $this->accountingService = $accountingService;
        $this->productService = $productService;
    }

    public function index()
    {
        $sales = $this->saleService->getAllSales();
        return view('admin.sales.index', compact('sales'));
    }

    public function create()
    {
        $products = $this->productService->getAllProductsWithInventory();
        return view('admin.sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'discount_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        try {
            $sale = $this->saleService->createSale($validated);
            $this->accountingService->createSaleEntries($sale);
            
            return redirect()
                ->route('sales.show', $sale->id)
                ->with('success', 'Sale created successfully');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error creating sale: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $sale = $this->saleService->getSaleWithDetails($id);
        return view('admin.sales.show', compact('sale'));
    }

    public function storePayment(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $sale->due_amount,
            'payment_date' => 'required|date'
        ]);
        
        try {
            $this->accountingService->recordPayment($sale, $validated);
            return redirect()
                ->route('sales.show', $sale->id)
                ->with('success', 'Payment recorded successfully');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error recording payment: ' . $e->getMessage());
        }
    }
}
