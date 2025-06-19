<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InventoryReportController extends Controller
{
    /**
     * Menampilkan laporan stok detail per gudang.
     */
    public function stockByWarehouse(Request $request)
    {
        // Ambil semua gudang untuk filter dropdown
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();

        // Mulai query ke tabel pivot product_warehouse
        $query = DB::table('product_warehouse')
            ->join('products', 'product_warehouse.product_id', '=', 'products.id')
            ->join('warehouses', 'product_warehouse.warehouse_id', '=', 'warehouses.id')
            ->select(
                'warehouses.name as warehouse_name',
                'products.sku',
                'products.nama as product_name',
                'products.berat',
                'product_warehouse.quantity'
            )
            ->where('product_warehouse.quantity', '>', 0); // Hanya tampilkan yang ada stok

        // Terapkan filter gudang jika dipilih
        if ($request->filled('warehouse_id')) {
            $query->where('product_warehouse.warehouse_id', $request->warehouse_id);
        }
        
        // Terapkan filter pencarian produk (berdasarkan nama atau SKU)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('products.nama', 'like', "%{$search}%")
                  ->orWhere('products.sku', 'like', "%{$search}%");
            });
        }

        // Ambil hasil dengan paginasi
        $stockDetails = $query->orderBy('warehouses.name')->orderBy('products.nama')->paginate(20)->withQueryString();

        return view('inventory.stock-by-warehouse', compact('stockDetails', 'warehouses'));
    }
}
