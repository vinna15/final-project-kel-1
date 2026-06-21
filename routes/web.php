<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReportController;

Route::get('/', fn() => redirect()->route('dashboard'));

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // OWNER
    Route::middleware(['role:owner'])->group(function () {
        Route::resource('branches', BranchController::class);
        Route::resource('users', UserController::class)->except(['show']);
    });

    // OWNER + MANAGER
    Route::middleware(['role:owner|manager'])->group(function () {
        Route::resource('products', ProductController::class);

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/transactions', [ReportController::class, 'transaction'])->name('transaction');
            Route::get('/stocks',       [ReportController::class, 'stock'])->name('stock');
        });
    });

    // KASIR
    Route::middleware(['role:kasir|owner'])->group(function () {
        Route::resource('transactions', TransactionController::class)
             ->only(['index', 'create', 'store', 'show']);
    });

    // Supervisor dapat melihat transaksi
    Route::middleware(['role:supervisor|manager'])->group(function () {
        Route::get('/transactions',              [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}',[TransactionController::class, 'show'])->name('transactions.show');
    });

    // GUDANG + SUPERVISOR
    Route::middleware(['role:gudang|supervisor|owner'])->group(function () {
        Route::get('/stocks',         [StockController::class, 'index'])->name('stocks.index');
        Route::get('/stocks/in',      [StockController::class, 'createIn'])->name('stocks.in');
        Route::post('/stocks/in',     [StockController::class, 'storeIn'])->name('stocks.in.store');
        Route::get('/stocks/out',     [StockController::class, 'createOut'])->name('stocks.out');
        Route::post('/stocks/out',    [StockController::class, 'storeOut'])->name('stocks.out.store');
        Route::get('/stocks/history', [StockController::class, 'history'])->name('stocks.history');
    });
});