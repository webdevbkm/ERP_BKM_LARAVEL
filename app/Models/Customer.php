<?php
// File: app/Models/Customer.php (BARU)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = []; // Izinkan semua field diisi

    // Satu pelanggan bisa memiliki banyak transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}