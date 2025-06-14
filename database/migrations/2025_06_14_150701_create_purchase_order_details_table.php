<?php
// File: database/migrations/..._create_purchase_order_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
            $table->decimal('price', 15, 2); // Harga beli per item
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_details');
    }
};
