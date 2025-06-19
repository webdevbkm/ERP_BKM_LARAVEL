<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Cabang;
use App\Models\PointHistory; // Pastikan model ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    /**
     * Menampilkan halaman utama Point of Sale dan menangani pencarian produk.
     */
    public function index(Request $request)
    {
        $foundProduct = null;
        $errorMessage = null;
        $user = Auth::user();
        $isAdmin = $user->hasRole('Admin');

        if ($request->has('sku') && $request->sku) {
            $sku = $request->sku;
            $productIdsWithStock = [];

            // 1. Kumpulkan ID produk yang memiliki stok
            // FINAL FIX: Menggunakan nama kolom 'quantity' yang benar sesuai database.
            $stockQuery = DB::table('product_warehouse')->where('quantity', '>', 0);

            if ($isAdmin) {
                // Admin bisa mengakses dari semua gudang
                $productIdsWithStock = $stockQuery->pluck('product_id')->unique();
            } else {
                $userWarehouseId = $user->warehouse_id;
                if (!$userWarehouseId) {
                    $errorMessage = 'Akses ditolak. User tidak memiliki gudang yang ditetapkan.';
                } else {
                    // User biasa hanya bisa dari gudang yang ditetapkan
                    $productIdsWithStock = $stockQuery->where('warehouse_id', $userWarehouseId)->pluck('product_id')->unique();
                }
            }

            // 2. Cari produk berdasarkan SKU dan daftar ID yang sudah difilter
            if (is_null($errorMessage)) {
                $foundProduct = Product::where('sku', $sku)
                    ->whereIn('id', $productIdsWithStock)
                    ->with(['kadar', 'jenis', 'pabrik', 'priceType'])
                    ->first();

                // Jika produk tidak ditemukan, berikan pesan error yang sesuai
                if (!$foundProduct) {
                    $productExists = Product::where('sku', $sku)->exists();
                    if ($productExists) {
                        $errorMessage = $isAdmin ? 'Stok produk habis di semua gudang.' : 'Stok produk habis di gudang Anda.';
                    } else {
                        $errorMessage = 'Produk dengan SKU ini tidak ditemukan.';
                    }
                }
            }
        }

        $cart = session()->get('pos_cart', []);
        // FIX: Menggunakan 'harga_jual' untuk menghitung total
        $total = collect($cart)->sum('harga_jual');
        $cabangs = Cabang::all();

        return view('pos.index', compact('foundProduct', 'errorMessage', 'cart', 'total', 'cabangs'));
    }

    public function addToCart(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $product = Product::with(['kadar', 'priceType'])->find($request->product_id);

        $cart = session()->get('pos_cart', []);
        $cart[now()->timestamp] = $product;
        session()->put('pos_cart', $cart);

        return redirect()->route('pos.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }
    
    public function removeFromCart(Request $request)
    {
        $request->validate(['cart_key' => 'required']);
        $cart = session()->get('pos_cart', []);
        
        unset($cart[$request->cart_key]);
        session()->put('pos_cart', $cart);

        return redirect()->route('pos.index');
    }

    public function clearCart()
    {
        session()->forget('pos_cart');
        return redirect()->route('pos.index')->with('success', 'Keranjang berhasil dikosongkan.');
    }

    public function store(Request $request)
    {
        $cart = session()->get('pos_cart', []);
        // FIX: Menggunakan 'harga_jual' untuk menghitung total
        $total = collect($cart)->sum('harga_jual');

        $request->validate([
            'payment_method' => 'required|string',
            // Pastikan amount_paid tidak kurang dari total belanja
            'amount_paid' => 'required|numeric|min:' . $total,
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'cabang_id' => 'required|exists:cabangs,id',
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::firstOrCreate(
                ['no_telepon' => $request->customer_phone],
                ['nama' => $request->customer_name, 'alamat' => $request->customer_address]
            );
            
            $user = Auth::user();
            $isAdmin = $user->hasRole('Admin');
            
            $transaction = Transaction::create([
                'invoice_number' => 'INV/' . date('Ymd') . '/' . mt_rand(1000, 9999),
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'cabang_id' => $request->cabang_id,
                'transaction_date' => now(),
                'total_amount' => $total,
                'amount_paid' => $request->amount_paid,
                'change' => $request->amount_paid - $total,
                'payment_method' => $request->payment_method,
            ]);

            foreach ($cart as $item) {
                $transaction->details()->create([
                    // FIX: Menggunakan 'harga_jual' sebagai harga
                    'product_id' => $item['id'], 'quantity' => 1, 'price' => $item['harga_jual'],
                ]);
                
                if ($isAdmin) {
                    $warehouseToDecrement = DB::table('product_warehouse')
                        ->where('product_id', $item['id'])
                        ->where('quantity', '>', 0)
                        ->first();
                    
                    if (!$warehouseToDecrement) {
                        throw new \Exception("Stok untuk produk {$item['sku']} tiba-tiba habis.");
                    }
                    
                    DB::table('product_warehouse')
                      ->where('id', $warehouseToDecrement->id)
                      ->decrement('quantity');
                } else {
                    $userWarehouseId = $user->warehouse_id;
                    if (!$userWarehouseId) {
                        throw new \Exception('User tidak memiliki gudang yang ditetapkan untuk transaksi.');
                    }
                    DB::table('product_warehouse')
                      ->where('product_id', $item['id'])
                      ->where('warehouse_id', $userWarehouseId)
                      ->decrement('quantity');
                }
            }
            
            if ($request->filled('customer_id') && class_exists(PointHistory::class)) {
                // Logika Poin
            }

            DB::commit();
            session()->forget('pos_cart');
            return redirect()->route('pos.invoice', $transaction->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan transaksi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }
    
    public function showInvoice(Transaction $transaction)
    {
        $transaction->load(['details.product', 'customer', 'user', 'cabang']);
        
        return view('pos.invoice', compact('transaction'));
    }
}
