<?php

// Import semua controller yang diperlukan
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Master\KadarController;
use App\Http\Controllers\Master\JenisController;
use App\Http\Controllers\Master\WarehouseController;
use App\Http\Controllers\Purchasing\PurchaseOrderController;
use App\Http\Controllers\Purchasing\GoodsReceiptController;
use App\Http\Controllers\InventoryReportController;

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

// Rute Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// Rute Dashboard, memerlukan login
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// Grup Rute yang memerlukan Autentikasi
Route::middleware('auth')->group(function () {
    
    // Rute Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Produk
    Route::resource('products', ProductController::class)->middleware('role:Admin|Staf Gudang');

    // --- Grup Rute untuk Master Data (Hanya Admin) ---
    Route::prefix('master')->name('master.')->middleware('role:Admin')->group(function () {
        Route::resource('kadar', KadarController::class);
        Route::resource('jenis', JenisController::class);
        Route::resource('warehouses', WarehouseController::class);
        Route::resource('customers', App\Http\Controllers\Master\CustomerController::class);
    });

    // --- Grup Rute untuk Administrasi (Hanya Admin) ---
    Route::prefix('admin')->name('admin.')->middleware('role:Admin')->group(function() {
        Route::resource('users', UserController::class);
    });

    // --- Rute untuk Point of Sale (POS) ---
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index')->middleware('role:Admin|Kasir');
    Route::post('/pos/add-to-cart', [PosController::class, 'addToCart'])->name('pos.addToCart');
    Route::post('/pos/remove-from-cart', [PosController::class, 'removeFromCart'])->name('pos.removeFromCart');
    Route::post('/pos/clear-cart', [PosController::class, 'clearCart'])->name('pos.clearCart');
    Route::post('/pos/store', [PosController::class, 'store'])->name('pos.store');
    Route::get('/pos/invoice/{transaction}', [PosController::class, 'showInvoice'])->name('pos.invoice');

    // --- Rute Laporan ---
    Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions')->middleware('role:Admin');
    // **DITAMBAHKAN:** Rute untuk Laporan Stok per Gudang
    Route::get('/reports/inventory/stock-by-warehouse', [InventoryReportController::class, 'stockByWarehouse'])->name('reports.inventory.stock-by-warehouse')->middleware('role:Admin');

    // --- Grup Rute untuk Inventaris ---
    Route::prefix('inventory')->name('inventory.')->middleware('role:Admin|Staf Gudang')->group(function () {
        // Rute untuk melihat stok per gudang
        Route::get('/warehouse-stocks', [App\Http\Controllers\Inventory\WarehouseStockController::class, 'index'])->name('warehouse-stocks.index');
        Route::get('/warehouse-stocks/{warehouse}', [App\Http\Controllers\Inventory\WarehouseStockController::class, 'show'])->name('warehouse-stocks.show');

        // Rute untuk transfer stok dari rencana sebelumnya bisa ditambahkan di sini nanti
        // Route::resource('transfers', App\Http\Controllers\Inventory\StockTransferController::class);
    });

    Route::prefix('api')->middleware('auth')->name('api.')->group(function () {
    // [BARU] Rute untuk mencari pelanggan berdasarkan nomor telepon
    Route::get('/customers/search', [App\Http\Controllers\Master\CustomerController::class, 'searchByPhone'])->name('customers.search');
    
    // Rute untuk mengirim dan verifikasi OTP
    Route::post('/otp/send', [App\Http\Controllers\Api\OtpController::class, 'send'])->name('otp.send');
    Route::post('/otp/verify', [App\Http\Controllers\Api\OtpController::class, 'verify'])->name('otp.verify');
});

    // --- Grup Rute untuk Purchasing ---
    Route::prefix('purchasing')->name('purchasing.')->group(function () {
        // Purchase Order Routes
        Route::resource('orders', PurchaseOrderController::class)
             ->names('purchase-orders'); 

        // **TAMBAHKAN RUTE INI:**
    // Rute untuk menyimpan produk baru dari modal via AJAX
    Route::post('purchase-orders/store-product-ajax', [App\Http\Controllers\Purchasing\PurchaseOrderController::class, 'storeProductAjax'])
         ->name('purchase-orders.storeProductAjax');

        // Goods Receipt Routes
        Route::get('receipts', [GoodsReceiptController::class, 'index'])->name('goods-receipts.index');
        Route::get('receipts/create/{purchaseOrder}', [GoodsReceiptController::class, 'create'])->name('goods-receipts.create');
        Route::post('receipts/store/{purchaseOrder}', [GoodsReceiptController::class, 'store'])->name('goods-receipts.store');
    });

    Route::get('/products/search', [App\Http\Controllers\ProductController::class, 'search'])->name('products.search');

    // Rute Approval & AJAX di luar grup prefix untuk menjaga nama rute tetap sederhana
    Route::patch('/purchasing/orders/{purchase_order}/approve', [PurchaseOrderController::class, 'approve'])
         ->name('purchase-orders.approve')
         ->middleware('can:approve purchase orders');
         
    Route::post('/purchasing/orders/store-product', [PurchaseOrderController::class, 'storeProductAjax'])
         ->name('purchase-orders.storeProductAjax')
         ->middleware('role:Admin|Purchasing');
});

// Memuat rute autentikasi bawaan Laravel
require __DIR__.'/auth.php';
