<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    use HasFactory;
    
    protected $table = 'jenis'; // Tentukan nama tabel jika tidak mengikuti konvensi

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    /**
     * Mendefinisikan relasi "satu ke banyak" dengan Product.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'jeni_id');
    }
}
