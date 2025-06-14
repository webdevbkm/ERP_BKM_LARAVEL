<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Mengizinkan semua field untuk diisi secara massal.
     */
    protected $guarded = [];

    /**
     * Relasi ke TransactionDetail (satu transaksi punya banyak detail).
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Relasi ke User (satu transaksi dimiliki satu kasir).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Customer (satu transaksi dimiliki satu pelanggan).
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relasi ke Cabang (satu transaksi terjadi di satu cabang).
     * INI ADALAH FUNGSI YANG MEMPERBAIKI ERROR ANDA.
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}
