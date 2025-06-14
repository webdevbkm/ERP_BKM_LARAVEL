<?php
// File: database/migrations/..._create_purchase_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('user_id')->constrained('users'); // User yang membuat
            $table->foreignId('pabrik_id')->constrained('pabriks'); // Supplier
            $table->date('po_date');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('status')->default('Pending'); // e.g., Pending, Approved, Rejected, Completed
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users'); // User yang menyetujui
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};