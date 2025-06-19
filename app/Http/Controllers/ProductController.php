<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Kadar;
use App\Models\Jenis;
use App\Models\Warna;
use App\Models\Pabrik;
use App\Models\Kokot;
use App\Models\Warehouse; // Import Warehouse Model
use App\Models\ModelBaki;
use App\Models\PriceType;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Import DB Facade
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk dengan fungsionalitas filter.
     */
    public function index(Request $request)
    {
        // Ambil data master untuk filter, hapus cabang
        $allJenis = Jenis::orderBy('nama')->get();
        $allKadars = Kadar::orderBy('nama')->get();
        $allPriceTypes = PriceType::orderBy('nama_tipe')->get();
        $allPabriks = Pabrik::orderBy('nama')->get();
        $maxWeight = Product::max('berat');

        // Muat relasi yang akan ditampilkan
        $query = Product::with(['kadar', 'jenis', 'warehouses']); // Muat relasi warehouses

        // Terapkan filter yang ada
        if ($request->filled('jenis_id')) $query->where('jeni_id', $request->jenis_id);
        if ($request->filled('kadar_id')) $query->where('kadar_id', $request->kadar_id);
        if ($request->filled('price_type_id')) $query->where('price_type_id', $request->price_type_id);
        if ($request->filled('pabrik_id')) $query->where('pabrik_id', $request->pabrik_id);
        if ($request->filled(['min_berat', 'max_berat'])) $query->whereBetween('berat', [$request->min_berat, $request->max_berat]);

        $products = $query->latest()->paginate(15)->withQueryString();

        // Kirim data yang diperlukan ke view, hapus allCabangs
        return view('products.index', compact('products', 'allJenis', 'allKadars', 'allPriceTypes', 'allPabriks', 'maxWeight'));
    }

    /**
     * Menampilkan form untuk membuat produk baru.
     */
    public function create()
    {
        $data = [
            'kadars'     => Kadar::all(),
            'jenis'      => Jenis::all(),
            'warnas'     => Warna::all(),
            'pabriks'    => Pabrik::all(),
            'kokots'     => Kokot::all(),
            'modelBakis' => ModelBaki::all(),
            'priceTypes' => PriceType::all(),
            'warehouses' => Warehouse::where('is_active', true)->get(), // Tambahkan data gudang aktif
        ];
        
        return view('products.create', $data);
    }
    
    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'model_baki_id' => 'required|exists:model_bakis,id',
            'price_type_id' => 'nullable|exists:price_types,id',
            'kadar_id' => 'required|exists:kadars,id',
            'jeni_id' => 'required|exists:jenis,id',
            'warna_id' => 'required|exists:warnas,id',
            'berat' => 'required|numeric|min:0',
            'panjang' => 'nullable|numeric|min:0',
            'harga_dasar_batu' => 'nullable|numeric|min:0',
            'ongkos_per_item' => 'nullable|numeric|min:0',
            'pabrik_id' => 'nullable|exists:pabriks,id',
            'kokot_id' => 'nullable|exists:kokots,id',
            'klp' => 'nullable|string|max:255',
            'kd' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'stocks' => 'nullable|array', // Validasi untuk stok
            'stocks.*' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $sku = date('Ymd') . '-' . strtoupper(Str::random(4));
            
            $fotoPath = null;
            if ($request->hasFile('foto_produk')) {
                $fotoPath = $request->file('foto_produk')->store('products', 'public');
            }
            
            $qrCodePath = 'qrcodes/' . $sku . '.svg';
            Storage::disk('public')->put($qrCodePath, QrCode::size(200)->generate($sku));

            // Hapus 'stok' dan 'cabang_id' dari data yang akan disimpan
            unset($validatedData['stocks']);

            $product = Product::create(array_merge(
                $validatedData,
                [
                    'sku' => $sku,
                    'foto_produk_path' => $fotoPath,
                    'qr_code_path' => $qrCodePath,
                ]
            ));

            // Simpan data stok ke tabel pivot
            if ($request->has('stocks')) {
                $stocksData = [];
                foreach ($request->stocks as $warehouseId => $quantity) {
                    if (!is_null($quantity) && $quantity > 0) {
                        $stocksData[$warehouseId] = ['quantity' => $quantity];
                    }
                }
                if (!empty($stocksData)) {
                    $product->warehouses()->sync($stocksData);
                }
            }

            DB::commit();
            return redirect()->route('products.show', $product)->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat produk: ' . $e->getMessage())->withInput();
        }
    }
    
    public function search(Request $request)
{
    $term = $request->input('q');
    $products = Product::where('nama', 'LIKE', '%'.$term.'%')
                        ->orWhere('sku', 'LIKE', '%'.$term.'%')
                        ->limit(20)->get();

    $formatted_products = [];
    foreach ($products as $product) {
        $formatted_products[] = ['id' => $product->id, 'text' => $product->nama . ' (SKU: ' . $product->sku . ')'];
    }

    return response()->json($formatted_products);
}

    /**
     * Menampilkan detail produk.
     */
    public function show(Product $product)
    {
        // Muat relasi warehouses untuk ditampilkan di detail
        $product->load('warehouses');
        return view('products.show', compact('product'));
    }
}
