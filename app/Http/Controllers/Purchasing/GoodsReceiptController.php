<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceipt;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoodsReceiptController extends Controller
{
    /**
     * Menampilkan daftar PO yang siap diterima.
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::where('status', 'Approved')
            ->whereIn('receipt_status', ['Not Received', 'Partially Received'])
            ->latest()
            ->paginate(15);
            
        return view('purchasing.receipts.index', compact('purchaseOrders'));
    }

    /**
     * Menampilkan form untuk menerima barang dari PO.
     */
    public function create(PurchaseOrder $purchaseOrder)
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        $purchaseOrder->load('details.product');
        return view('purchasing.receipts.create', compact('purchaseOrder', 'warehouses'));
    }

    /**
     * Menyimpan data penerimaan barang dan memperbarui stok.
     */
    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'receipt_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity_received' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat catatan Goods Receipt
            $receipt = GoodsReceipt::create([
                'purchase_order_id' => $purchaseOrder->id,
                'warehouse_id' => $request->warehouse_id,
                'user_id' => Auth::id(),
                'receipt_date' => $request->receipt_date,
                'receipt_number' => 'GR/' . date('Ymd') . '/' . mt_rand(1000, 9999),
                'notes' => $request->notes,
            ]);

            // 2. Proses setiap item yang diterima
            foreach ($request->items as $itemData) {
                if ($itemData['quantity_received'] > 0) {
                    // Simpan detail penerimaan
                    $receipt->details()->create($itemData);

                    // Update stok di gudang yang dipilih
                    DB::table('product_warehouse')
                        ->updateOrInsert(
                            ['product_id' => $itemData['product_id'], 'warehouse_id' => $request->warehouse_id],
                            ['quantity' => DB::raw('quantity + ' . $itemData['quantity_received'])]
                        );
                }
            }
            
            // 3. Update status penerimaan PO (logika sederhana)
            // Untuk logika lengkap (partial receive) memerlukan penambahan kolom 'quantity_received' di PO detail.
            $purchaseOrder->receipt_status = 'Fully Received';
            $purchaseOrder->save();

            DB::commit();
            return redirect()->route('goods-receipts.index')->with('success', 'Barang berhasil diterima dan stok telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses penerimaan barang: ' . $e->getMessage())->withInput();
        }
    }
}
