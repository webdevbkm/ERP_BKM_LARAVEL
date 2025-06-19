<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Cabang;
use App\Models\Jenis;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan semua data ringkasan.
     */
    public function index()
    {
        // 1. Ringkasan penjualan per cabang (Tidak berubah)
        $salesSummaryPerBranch = Cabang::withSum('transactions', 'total_amount')
            ->orderBy('nama')
            ->get();

        // 2. **FIX:** Menghitung nilai aset menggunakan total stok dari semua gudang
        $assetSummaryPerJenis = Jenis::with(['products.kadar', 'products.priceType', 'products.warehouses'])
            ->get()
            ->map(function($jenis) {
                $totalAssetValue = $jenis->products->sum(function($product) {
                    // Menggunakan accessor 'total_stock' yang sudah kita buat di Model Product
                    return $product->harga_final * $product->total_stock;
                });
                $jenis->total_asset_value = $totalAssetValue;
                return $jenis;
            });

        // 3. **FIX:** Menghitung total stok per jenis dari relasi multi-gudang
        $stockSummary = Jenis::with('products.warehouses')->get()->map(function ($jenis) {
            // Jumlahkan 'total_stock' dari setiap produk di dalam jenis ini
            $totalStock = $jenis->products->sum(function ($product) {
                return $product->total_stock;
            });
            // Buat properti baru untuk menampung hasil kalkulasi
            $jenis->products_sum_stok = $totalStock;
            return $jenis;
        })->sortBy('nama');
        
        // 4. Hitung jumlah pengguna (Tidak berubah)
        $totalUsers = User::count();
        
        // Kirim semua data yang diperlukan ke view
        return view('dashboard', compact(
            'salesSummaryPerBranch',
            'assetSummaryPerJenis',
            'stockSummary',
            'totalUsers'
        ));
    }
}
