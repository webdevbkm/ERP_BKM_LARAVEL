<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Kadar;
use App\Models\Jenis;
use App\Models\Warna;
use App\Models\Pabrik;
use App\Models\Kokot;
use App\Models\Cabang;
use App\Models\ModelBaki;
use App\Models\PriceType;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk dengan fungsionalitas filter.
     */
    public function index(Request $request)
    {
        // Ambil semua data master untuk dropdown filter
        $allJenis = Jenis::orderBy('nama')->get();
        $allKadars = Kadar::orderBy('nama')->get();
        $allPriceTypes = PriceType::orderBy('nama_tipe')->get();
        $allPabriks = Pabrik::orderBy('nama')->get();
        $allCabangs = Cabang::orderBy('nama')->get();

        // Tentukan rentang berat min/max untuk slider
        $maxWeight = Product::max('berat');

        // Mulai query builder dan muat relasi yang akan ditampilkan di tabel
        $query = Product::with(['kadar', 'jenis', 'warna', 'cabang', 'pabrik']);

        // Terapkan filter jika ada
        if ($request->filled('jenis_id')) {
            $query->where('jeni_id', $request->jenis_id);
        }
        if ($request->filled('kadar_id')) {
            $query->where('kadar_id', $request->kadar_id);
        }
        if ($request->filled('price_type_id')) {
            $query->where('price_type_id', $request->price_type_id);
        }
        if ($request->filled('pabrik_id')) {
            $query->where('pabrik_id', $request->pabrik_id);
        }
        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }
        if ($request->filled(['min_berat', 'max_berat'])) {
            $query->whereBetween('berat', [$request->min_berat, $request->max_berat]);
        }

        // Ambil hasil setelah difilter dan paginasi
        $products = $query->latest()->paginate(15)->withQueryString();

        // Kirim semua data yang diperlukan ke view
        return view('products.index', compact('products', 'allJenis', 'allKadars', 'allPriceTypes', 'allPabriks', 'allCabangs', 'maxWeight'));
    }

    // ... sisa fungsi lainnya (create, store, show) tetap sama ...
    public function create()
    {
        $data = [
            'kadars'     => Kadar::all(),
            'jenis'      => Jenis::all(),
            'warnas'     => Warna::all(),
            'pabriks'    => Pabrik::all(),
            'kokots'     => Kokot::all(),
            'cabangs'    => Cabang::all(),
            'modelBakis' => ModelBaki::all(),
            'priceTypes' => PriceType::all(),
        ];
        
        return view('products.create', $data);
    }
    
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
            'stok' => 'required|integer|min:0',
            'harga_dasar_batu' => 'nullable|numeric|min:0',
            'ongkos_per_item' => 'nullable|numeric|min:0',
            'pabrik_id' => 'nullable|exists:pabriks,id',
            'cabang_id' => 'required|exists:cabangs,id',
            'kokot_id' => 'nullable|exists:kokots,id',
            'klp' => 'nullable|string|max:255',
            'kd' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $sku = date('Ymd') . '-' . strtoupper(Str::random(4));
        
        $fotoPath = null;
        if ($request->hasFile('foto_produk')) {
            $fotoPath = $request->file('foto_produk')->store('products', 'public');
        }
        
        $qrCodePath = 'qrcodes/' . $sku . '.svg';
        Storage::disk('public')->put($qrCodePath, QrCode::size(200)->generate($sku));

        $product = Product::create(array_merge(
            $validatedData,
            [
                'sku' => $sku,
                'foto_produk_path' => $fotoPath,
                'qr_code_path' => $qrCodePath,
            ]
        ));

        return redirect()->route('products.show', $product)->with('success', 'Produk berhasil ditambahkan!');
    }
    
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}
