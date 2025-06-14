<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Cabang;
use App\Models\Jenis;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan semua data ringkasan.
     */
    public function index()
    {
        // 1. Ringkasan penjualan per cabang
        $salesSummaryPerBranch = Cabang::withSum('transactions', 'total_amount')
            ->orderBy('nama')
            ->get();

        // 2. Ringkasan total nilai aset per jenis barang
        // Ini menghitung: SUM(stok * harga_final) untuk setiap jenis produk
        $assetSummaryPerJenis = Jenis::with(['products.kadar', 'products.priceType'])
            ->get()
            ->map(function($jenis) {
                $totalAssetValue = $jenis->products->sum(function($product) {
                    // Mengakses atribut harga_final yang sudah kita buat di Model Product
                    return $product->harga_final * $product->stok;
                });
                $jenis->total_asset_value = $totalAssetValue;
                return $jenis;
            });

        // 3. Ringkasan jumlah stok per jenis (BARU)
        $stockSummary = Jenis::withSum('products', 'stok')
            ->orderBy('nama')
            ->get();
        
        // Kirim semua data yang diperlukan ke view
        return view('dashboard', compact(
            'salesSummaryPerBranch',
            'assetSummaryPerJenis',
            'stockSummary' // Mengirim data baru
        ));
    }
}
