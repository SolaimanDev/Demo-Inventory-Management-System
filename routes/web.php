<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Product Routes
Route::resource('products', ProductController::class)->middleware('auth');

// Sale Routes
Route::resource('sales', SaleController::class)->except(['edit', 'update', 'destroy'])->middleware('auth');
Route::post('/sales/{sale}/payments', [SaleController::class, 'storePayment'])->name('sales.payments.store')->middleware('auth');

// Report Routes
Route::get('/reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales')->middleware('auth');
Route::get('/reports/profit', [ReportController::class, 'profitReport'])->name('reports.profit')->middleware('auth');
