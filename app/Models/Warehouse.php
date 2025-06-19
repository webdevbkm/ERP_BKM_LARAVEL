<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi many-to-many ke Product melalui tabel pivot 'product_warehouse'.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_warehouse')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
