<?php
// app/Models/GoodsReceiptDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceiptDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function goodsReceipt() { return $this->belongsTo(GoodsReceipt::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
