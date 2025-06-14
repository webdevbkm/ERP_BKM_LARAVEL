<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Pabrik;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Menampilkan daftar semua Purchase Order.
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['user', 'pabrik'])->latest()->paginate(15);
        return view('purchasing.index', compact('purchaseOrders'));
    }

    /**
     * Menampilkan form untuk membuat Purchase Order baru.
     */
    public function create()
    {
        $pabriks = Pabrik::orderBy('nama')->get();
        $products = Product::orderBy('nama')->get();
        $po_number = 'PO/' . date('Ymd') . '/' . mt_rand(1000, 9999);
        return view('purchasing.create', compact('pabriks', 'products', 'po_number'));
    }

    /**
     * Menyimpan Purchase Order baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'po_number' => 'required|string|unique:purchase_orders,po_number',
            'pabrik_id' => 'required|exists:pabriks,id',
            'po_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }

            $po = PurchaseOrder::create([
                'po_number' => $request->po_number,
                'user_id' => Auth::id(),
                'pabrik_id' => $request->pabrik_id,
                'po_date' => $request->po_date,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'status' => 'Pending',
            ]);

            foreach ($request->items as $item) {
                $po->details()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }

            DB::commit();
            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibuat dan menunggu persetujuan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat Purchase Order: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail Purchase Order untuk otorisasi.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['user', 'pabrik', 'details.product']);
        return view('purchasing.show', compact('purchaseOrder'));
    }

    /**
     * Fungsi untuk menyetujui atau menolak PO.
     * Kita akan membuat route khusus untuk ini nanti.
     */
    public function approve(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate(['status' => 'required|in:Approved,Rejected']);

        if ($purchaseOrder->status !== 'Pending') {
            return redirect()->route('purchase-orders.show', $purchaseOrder)->with('error', 'Purchase Order ini sudah diproses.');
        }

        $purchaseOrder->update([
            'status' => $request->status,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order telah berhasil di-' . $request->status . '.');
    }
}
