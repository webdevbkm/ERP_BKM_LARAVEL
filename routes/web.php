<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Master\KadarController;
use App\Http\Controllers\Master\JenisController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController; // <-- 1. Import ReportController
use App\Http\Controllers\Purchasing\PurchaseOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('products', ProductController::class)->middleware('role:Admin|Staf Gudang');
    Route::resource('master/kadar', KadarController::class)->middleware('role:Admin');
    Route::resource('master/jenis', JenisController::class)->middleware('role:Admin');
    Route::resource('admin/users', UserController::class)->middleware('role:Admin');
    
    // Route POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index')->middleware('role:Admin|Kasir');
    Route::post('/pos/add-to-cart', [PosController::class, 'addToCart'])->name('pos.addToCart');
    Route::post('/pos/remove-from-cart', [PosController::class, 'removeFromCart'])->name('pos.removeFromCart');
    Route::post('/pos/clear-cart', [PosController::class, 'clearCart'])->name('pos.clearCart');
    Route::post('/pos/store', [PosController::class, 'store'])->name('pos.store');
    Route::get('/pos/invoice/{transaction}', [PosController::class, 'showInvoice'])->name('pos.invoice');
    
    // --- ROUTE BARU UNTUK LAPORAN ---
    Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions')->middleware('role:Admin');

    // --- ROUTE BARU UNTUK PURCHASING ---
    // Akan dilindungi oleh peran 'Admin' atau 'Purchasing'
    Route::resource('purchasing/orders', PurchaseOrderController::class)
         ->names('purchase-orders') // Memberi nama unik untuk route resource
         ->middleware('role:Admin|Purchasing|CEO'); 
});

require __DIR__.'/auth.php';
