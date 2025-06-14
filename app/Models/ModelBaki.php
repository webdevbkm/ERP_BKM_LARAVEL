<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelBaki extends Model
{
    use HasFactory;

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
     * Get the products for the model baki.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
