<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class WarehouseStockController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::withCount('products')->paginate(15);
        return view('inventory.warehouse-stocks.index', compact('warehouses'));
    }

    public function show(Warehouse $warehouse)
    {
        $query = DB::table('product_warehouse')
            ->join('products', 'product_warehouse.product_id', '=', 'products.id')
            ->select('products.sku', 'products.nama', 'product_warehouse.quantity') // DIGANTI dari 'name' ke 'nama'
            ->where('product_warehouse.warehouse_id', $warehouse->id)
            ->where('product_warehouse.quantity', '>', 0);

        if (request()->has('search') && request()->get('search') != '') {
            $search = request()->get('search');
            $query->where(function($q) use ($search) {
                $q->where('products.nama', 'like', '%' . $search . '%') // DIGANTI dari 'name' ke 'nama'
                  ->orWhere('products.sku', 'like', '%' . $search . '%');
            });
        }

        $stocks = $query->paginate(20)->withQueryString();

        return view('inventory.warehouse-stocks.show', compact('warehouse', 'stocks'));
    }
}