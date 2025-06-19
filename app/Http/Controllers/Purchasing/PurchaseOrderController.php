<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Pabrik;
use App\Models\Warehouse;
use App\Models\Kadar;
use App\Models\Jenis;
use App\Models\ModelBaki;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * [BARU] Menampilkan daftar semua Purchase Order.
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
        $warehouses = Warehouse::orderBy('name')->get();
        $bakis = ModelBaki::all();
        $po_number = 'PO/' . date('Ymd') . '/' . mt_rand(1000, 9999);

        // Data master untuk dropdown di setiap baris item
        $kadars = Kadar::all();
        $jenis = Jenis::all();

        return view('purchasing.create', compact(
            'pabriks', 'warehouses', 'po_number', 'kadars', 'jenis', 'bakis'
        ));
    }

    /**
     * Menyimpan Purchase Order baru ke database dengan item-item baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|unique:purchase_orders,po_number',
            'pabrik_id' => 'required|exists:pabriks,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'baki_id' => 'nullable|exists:model_bakis,id',
            'po_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:po_date',
            'discount_percentage' => 'nullable|numeric|min:0',
            'vat_percentage' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:255',
            'items.*.jenis_id' => 'required|exists:jenis,id',
            'items.*.kadar_id' => 'required|exists:kadars,id',
            'items.*.berat' => 'required|numeric|min:0.01',
            'items.*.harga_per_gram' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Kalkulasi total
            $subTotalAmount = 0;
            foreach ($validated['items'] as $item) {
                $hargaSatuan = $item['berat'] * $item['harga_per_gram'];
                $subTotalAmount += $hargaSatuan * $item['quantity'];
            }
            $globalDiscountAmount = $subTotalAmount * (($request->discount_percentage ?? 0) / 100);
            $totalAfterDiscount = $subTotalAmount - $globalDiscountAmount;
            $vatAmount = $totalAfterDiscount * (($request->vat_percentage ?? 0) / 100);
            $finalTotal = $totalAfterDiscount + $vatAmount;

            $po = PurchaseOrder::create([
                'po_number' => $request->po_number,
                'user_id' => Auth::id(),
                'pabrik_id' => $request->pabrik_id,
                'warehouse_id' => $request->warehouse_id,
                'baki_id' => $request->baki_id,
                'po_date' => $request->po_date,
                'due_date' => $request->due_date,
                'discount_percentage' => $request->discount_percentage ?? 0,
                'vat_percentage' => $request->vat_percentage ?? 0,
                'total_amount' => $finalTotal,
                'notes' => $request->notes,
                'status' => 'Pending',
            ]);

            foreach ($validated['items'] as $item) {
                $hargaSatuan = $item['berat'] * $item['harga_per_gram'];
                $po->details()->create([
                    'nama_produk_baru' => $item['nama'],
                    'jenis_id' => $item['jenis_id'],
                    'kadar_id' => $item['kadar_id'],
                    'berat' => $item['berat'],
                    'quantity' => $item['quantity'],
                    'harga_per_gram_input' => $item['harga_per_gram'],
                    'price' => $hargaSatuan,
                ]);
            }
            
            DB::commit();
            return redirect()->route('purchasing.purchase-orders.index')->with('success', 'PO berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat PO: ' . $e->getMessage())->withInput();
        }
    }
}