<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\BackupController;
use App\Http\Controllers\Dashboard\CashierController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\LogStockController;
use App\Http\Controllers\Dashboard\PembelianObatController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\StockController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\UserSettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route: Guest (Belum Login)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Route: Authenticated (Sudah Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Profile - semua user bisa
    Route::get('/profile', [UserSettingController::class, 'index'])->name('profile');
    Route::put('/profile', [UserSettingController::class, 'update'])->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Admin'])->group(function () {

        // Dashboard Admin
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Management User
        Route::get('/user', [UserController::class, 'index'])->name('user');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

        // Category
        Route::get('/category', [CategoryController::class, 'index'])->name('category');
        Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
        Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

        // Product
        Route::get('/product', [ProductController::class, 'index'])->name('product');
        Route::post('/product', [ProductController::class, 'store'])->name('product.store');
        Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
        Route::get('/product/print-expired', [ProductController::class, 'printExpired'])->name('product.print-expired');

        // Stock
        Route::get('/stock', [StockController::class, 'index'])->name('stock');
        Route::put('/stock/{id}', [StockController::class, 'updateStock'])->name('stock.update');
        Route::get('/log-stock/{id}', [LogStockController::class, 'index'])->name('log-stock');

        // Pembelian obat (riwayat stok masuk)
        Route::get('/pembelian', [PembelianObatController::class, 'index'])->name('pembelian');

        // Backup & Restore
        Route::get('/backup', [BackupController::class, 'index'])->name('backup');
        Route::post('/backup', [BackupController::class, 'store'])->name('backup.store');
        Route::get('/download/{id}', [BackupController::class, 'download'])->name('backup.download');
        Route::match(['post', 'put'], 'backup/restore', [BackupController::class, 'restore'])->name('backup.restore');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/settings/receipt-pdf', [SettingsController::class, 'generateReceiptPdf'])->name('settings.receipt-pdf');
        Route::get('/settings/receipt-view', [SettingsController::class, 'receiptView'])->name('settings.receipt-view');

        // Report
        Route::get('/report', [ReportController::class, 'index'])->name('report');
        Route::get('/report/sales', [ReportController::class, 'reportSales'])->name('report.sales');
        Route::get('/report/pdf', [ReportController::class, 'exportPdf'])->name('report.pdf');
        Route::get('/report/excel', [ReportController::class, 'exportExcel'])->name('report.excel');
        Route::get('/transaction-items/{transaction}', [ReportController::class, 'getTransactionItems']);
        Route::get('/report/excel-sales', [ReportController::class, 'exportExcelSales'])->name('report.excel.sales');
        Route::get('/report/pdf-sales', [ReportController::class, 'exportPdfSales'])->name('report.pdf.sales');
    });

    /*
    |--------------------------------------------------------------------------
    | CASHIER ROUTES (Admin dapat mengakses untuk supervisi)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Cashier', 'specific.cashier'])->group(function () {
        Route::get('/cashier', [CashierController::class, 'index'])->name('cashier');
        Route::get('/cashier/stock-available', [CashierController::class, 'stockAvailable'])->name('cashier.stock-available');
        Route::get('/cashier/sold-products', [CashierController::class, 'soldProducts'])->name('cashier.sold-products');
        Route::get('/cashier/search', [CashierController::class, 'search'])->name('cashier.search');
        Route::get('/cashier/filterByCategory', [CashierController::class, 'filterByCategory']);
        Route::get('/cashier/receipt/{invoiceCode}', [CashierController::class, 'receipt'])->name('cashier.receipt');
        Route::post('/cashier/checkout', [CashierController::class, 'checkout'])->name('cashier.checkout');
        Route::post('/cashier/non-cash/complete', [CashierController::class, 'completeNonCashPayment'])->name('cashier.non-cash.complete');
    });
});
