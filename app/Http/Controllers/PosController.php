<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    /**
     * Menampilkan halaman utama Point of Sale dan menangani pencarian produk.
     */
    public function index(Request $request)
    {
        $foundProduct = null;
        $errorMessage = null;

        if ($request->has('sku') && $request->sku) {
            // Temukan produk berdasarkan SKU
            $foundProduct = Product::where('sku', $request->sku)->first();

            if (!$foundProduct) {
                $errorMessage = 'Produk dengan SKU ini tidak ditemukan.';
            } elseif ($foundProduct->stok <= 0) {
                $errorMessage = 'Stok produk habis.';
                $foundProduct = null;
            } else {
                // PENTING: Muat semua relasi yang dibutuhkan untuk ditampilkan
                $foundProduct->load(['kadar', 'jenis', 'pabrik', 'priceType']);
            }
        }

        $cart = session()->get('pos_cart', []);
        $total = collect($cart)->sum('harga_final');
        $cabangs = Cabang::all();

        return view('pos.index', compact('foundProduct', 'errorMessage', 'cart', 'total', 'cabangs'));
    }

    // ... sisa fungsi lainnya (addToCart, store, dll.) tidak berubah ...
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
        $total = collect($cart)->sum('harga_final');

        $request->validate([
            'payment_method' => 'required|string',
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

            $transaction = Transaction::create([
                'invoice_number' => 'INV/' . date('Ymd') . '/' . mt_rand(1000, 9999),
                'user_id' => Auth::id(),
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
                    'product_id' => $item['id'], 'quantity' => 1, 'price' => $item['harga_final'],
                ]);
                Product::find($item['id'])->decrement('stok');
            }
            
            DB::commit();
            session()->forget('pos_cart');
            return redirect()->route('pos.invoice', $transaction->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }
    
    public function showInvoice(Transaction $transaction)
    {
        $transaction->load(['details.product', 'customer', 'user', 'cabang']);
        
        return view('pos.invoice', compact('transaction'));
    }
}
