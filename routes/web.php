<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ExcelController;

Route::get('/', fn() => redirect()->route('dashboard'));

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // OWNER ONLY
    Route::middleware(['role:owner'])->group(function () {
        Route::resource('branches', BranchController::class);
        Route::resource('users', UserController::class)->except(['show']);

        // Products CRUD (owner only)
        Route::get('/products/create',         [ProductController::class, 'create'])->name('products.create');
        Route::post('/products',               [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}',      [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}',   [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // SEMUA ROLE: lihat produk 
    Route::middleware(['role:owner|manager|supervisor|kasir|gudang'])->group(function () {
        Route::get('/products',           [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    });

    // TRANSAKSI
    Route::middleware(['role:kasir|owner'])->group(function () {
        Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('/transactions',       [TransactionController::class, 'store'])->name('transactions.store');
    });

    Route::middleware(['role:owner|manager|supervisor|kasir'])->group(function () {
        Route::get('/transactions',              [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}',[TransactionController::class, 'show'])->name('transactions.show');
    });

    // STOK
    Route::middleware(['role:owner|manager|supervisor|gudang'])->group(function () {
        Route::get('/stocks',         [StockController::class, 'index'])  ->name('stocks.index');
        Route::get('/stocks/history', [StockController::class, 'history'])->name('stocks.history');
    });

    Route::middleware(['role:owner|supervisor|gudang'])->group(function () {
        Route::get('/stocks/in',   [StockController::class, 'createIn'])->name('stocks.in');
        Route::post('/stocks/in',  [StockController::class, 'storeIn']) ->name('stocks.in.store');
        Route::get('/stocks/out',  [StockController::class, 'createOut'])->name('stocks.out');
        Route::post('/stocks/out', [StockController::class, 'storeOut']) ->name('stocks.out.store');
    });

    // LAPORAN
    Route::middleware(['role:owner|manager|supervisor'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/transactions', [ReportController::class, 'transaction'])->name('transaction');
        Route::get('/stocks',       [ReportController::class, 'stock'])      ->name('stock');
    });

    // PDF 
    Route::middleware(['role:owner|manager|supervisor'])->prefix('pdf')->name('pdf.')->group(function () {
        Route::get('/transactions', [PdfController::class, 'transaction'])->name('transaction');
        Route::get('/stocks',       [PdfController::class, 'stock'])      ->name('stock');
    });

    Route::middleware(['role:owner|kasir'])->group(function () {
        Route::get('/pdf/receipt/{transaction}', [PdfController::class, 'receipt'])->name('pdf.receipt');
    });

    // EXCEL 
    Route::middleware(['role:owner|manager'])->prefix('excel')->name('excel.')->group(function () {
        Route::get('/transactions', [ExcelController::class, 'transaction'])->name('transaction');
        Route::get('/stocks',       [ExcelController::class, 'stock'])      ->name('stock');
    });

});