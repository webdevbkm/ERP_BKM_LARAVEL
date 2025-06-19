<?php
// app/Models/GoodsReceipt.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class); }
    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function details() { return $this->hasMany(GoodsReceiptDetail::class); }
}
