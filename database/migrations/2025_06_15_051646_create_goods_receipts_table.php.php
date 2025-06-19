<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_goods_receipts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('goods_receipts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('purchase_order_id')->constrained('purchase_orders');
        $table->foreignId('user_id')->comment('User yang menerima barang')->constrained('users');
        $table->date('receipt_date');
        $table->text('notes')->nullable();
        $table->timestamps();
    });

    // Kita akan buat tabel detailnya juga di sini untuk mempermudah
    Schema::create('goods_receipt_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('goods_receipt_id')->constrained('goods_receipts')->onDelete('cascade');
        $table->foreignId('purchase_order_detail_id')->constrained('purchase_order_details');
        $table->foreignId('product_id')->comment('ID dari produk baru yang dibuat')->constrained('products');
        $table->integer('quantity_received');
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('goods_receipt_details');
        Schema::dropIfExists('goods_receipts');
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('receipt_status');
        });
    }
};

