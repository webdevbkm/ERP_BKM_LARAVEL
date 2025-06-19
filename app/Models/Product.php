<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Hapus 'stok' dan 'cabang_id' dari fillable
    protected $fillable = [
        'sku', 'kadar_id', 'jeni_id', 'warna_id', 'pabrik_id', 
        'model_baki_id', 'price_type_id', 'kokot_id', 'nama',
        'harga_dasar_batu', 'ongkos_per_item', 'berat', 'panjang',
        'klp', 'kd', 'keterangan', 'foto_produk_path', 'qr_code_path'
    ];

    /**
     * Relasi many-to-many ke Warehouse melalui tabel pivot 'product_warehouse'.
     * Ini digunakan untuk mengelola stok.
     */
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse')
                    ->withPivot('quantity') // Sertakan kolom quantity dari tabel pivot
                    ->withTimestamps();
    }

    /**
     * Accessor untuk mendapatkan total stok dari semua gudang.
     * Bisa dipanggil dengan $product->total_stock
     */
    protected function totalStock(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->warehouses->sum('pivot.quantity')
        );
    }


    protected function hargaFinal(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $hargaEmas = ($this->kadar->harga_per_gram ?? 0) * $this->berat;
                $penambahanHarga = $this->priceType->jumlah_penambahan_rupiah ?? 0;
                $total = $hargaEmas + $this->ongkos_per_item + $this->harga_dasar_batu + $penambahanHarga;
                return $total;
            },
        );
    }

    // --- RELASI KE MASTER DATA ---
    public function kadar() { return $this->belongsTo(Kadar::class); }
    public function jenis() { return $this->belongsTo(Jenis::class, 'jeni_id'); }
    public function warna() { return $this->belongsTo(Warna::class); }
    public function modelBaki() { return $this->belongsTo(ModelBaki::class); }
    public function pabrik() { return $this->belongsTo(Pabrik::class); }
    public function priceType() { return $this->belongsTo(PriceType::class); }
    public function kokot() { return $this->belongsTo(Kokot::class); }
}
