<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 
        'kadar_id', 
        'jeni_id', 
        'warna_id', 
        'pabrik_id', 
        'model_baki_id',
        'price_type_id', 
        'kokot_id', 
        'cabang_id', 
        'nama',
        'harga_dasar_batu', 
        'ongkos_per_item', 
        'berat', 
        'panjang',
        'klp', 
        'kd', 
        'stok', 
        'keterangan', 
        'foto_produk_path', 
        'qr_code_path'
    ];

    /**
     * Accessor untuk menghitung harga final secara otomatis.
     * Ini adalah "kolom virtual" yang bisa dipanggil dengan $product->harga_final
     */
    protected function hargaFinal(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                // Harga emas murni = (harga per gram dari kadar) * berat
                $hargaEmas = ($this->kadar->harga_per_gram ?? 0) * $this->berat;

                // Penambahan dari Tipe Harga (bisa negatif/diskon)
                $penambahanHarga = $this->priceType->jumlah_penambahan_rupiah ?? 0;

                // Harga Final = Harga Emas + Ongkos + Harga Batu + Penambahan
                $total = $hargaEmas + $this->ongkos_per_item + $this->harga_dasar_batu + $penambahanHarga;
                
                return $total;
            },
        );
    }


    // --- RELASI KE MASTER DATA ---
    public function kadar()
    {
        return $this->belongsTo(Kadar::class);
    }

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'jeni_id');
    }

    public function warna()
    {
        return $this->belongsTo(Warna::class);
    }
    
    public function modelBaki()
    {
        return $this->belongsTo(ModelBaki::class);
    }

    public function pabrik()
    {
        return $this->belongsTo(Pabrik::class);
    }

    public function priceType()
    {
        return $this->belongsTo(PriceType::class);
    }

    public function kokot()
    {
        return $this->belongsTo(Kokot::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}
