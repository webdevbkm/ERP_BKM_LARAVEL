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
    Schema::create('point_histories', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
        $table->foreignId('transaction_id')->nullable()->constrained('transactions'); // Untuk poin yang didapat
        $table->integer('points_change'); // Positif untuk poin masuk, negatif untuk keluar
        $table->string('description'); // Misal: "Poin dari transaksi #INV123" atau "Penukaran poin"
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_histories');
    }
};
