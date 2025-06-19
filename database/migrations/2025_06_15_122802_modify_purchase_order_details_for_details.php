<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('purchase_order_details', function (Blueprint $table) {
        $table->decimal('item_discount_percentage', 5, 2)->default(0)->after('price');
        $table->decimal('ongkos', 15, 2)->default(0)->after('item_discount_percentage');
        // Tambahkan kolom lain dari gambar jika perlu (misal: g_murni, g_tukar, dll.)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_details', function (Blueprint $table) {
            //
        });
    }
};
