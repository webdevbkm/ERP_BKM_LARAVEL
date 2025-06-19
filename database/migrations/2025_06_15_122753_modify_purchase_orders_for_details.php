<?php
// database/migrations/xxxx_xx_xx_xxxxxx_modify_purchase_orders_for_details_v2.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Cek sebelum menambahkan kolom
            if (!Schema::hasColumn('purchase_orders', 'due_date')) {
                $table->date('due_date')->nullable()->after('po_date');
            }
            if (!Schema::hasColumn('purchase_orders', 'discount_percentage')) {
                $table->decimal('discount_percentage', 5, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('purchase_orders', 'vat_percentage')) {
                $table->decimal('vat_percentage', 5, 2)->default(0)->after('discount_percentage');
            }
            
            // [KUNCI PERBAIKAN] Menggunakan pabrik_id
            if (!Schema::hasColumn('purchase_orders', 'pabrik_id')) {
                $table->foreignId('pabrik_id')->nullable()->constrained('pabriks')->onDelete('set null')->after('warehouse_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_orders', 'pabrik_id')) {
                $table->dropForeign(['pabrik_id']);
                $table->dropColumn('pabrik_id');
            }
            // ... (kode rollback lainnya)
        });
    }
};