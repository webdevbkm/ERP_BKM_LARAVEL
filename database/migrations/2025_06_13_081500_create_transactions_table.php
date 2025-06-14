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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained('users'); // Kasir
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete(); // Pelanggan
            $table->foreignId('cabang_id')->constrained('cabangs'); // <-- KOLOM BARU UNTUK CABANG
            $table->date('transaction_date');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('amount_paid', 15, 2);
            $table->decimal('change', 15, 2)->default(0);
            $table->string('payment_method')->default('cash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
