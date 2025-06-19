<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Mendefinisikan bahwa satu cabang bisa memiliki banyak transaksi.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
