<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_order_details', function (Blueprint $table) {
            // Kita tidak lagi menggunakan product_id di sini, karena produk belum ada.
            // Jika kolom product_id sudah ada, kita buat nullable atau hapus.
            if (Schema::hasColumn('purchase_order_details', 'product_id')) {
                // Opsi 1: Buat nullable (aman)
                $table->foreignId('product_id')->nullable()->change();
            }

            // Tambahkan kolom untuk mendefinisikan produk baru
            if (!Schema::hasColumn('purchase_order_details', 'nama_produk_baru')) {
                $table->string('nama_produk_baru')->after('id');
            }
            if (!Schema::hasColumn('purchase_order_details', 'berat')) {
                $table->decimal('berat', 10, 3)->after('nama_produk_baru');
            }
            if (!Schema::hasColumn('purchase_order_details', 'kadar_id')) {
                $table->foreignId('kadar_id')->constrained('kadars')->after('berat');
            }
            if (!Schema::hasColumn('purchase_order_details', 'jenis_id')) {
                $table->foreignId('jenis_id')->constrained('jenis')->after('kadar_id');
            }
            if (!Schema::hasColumn('purchase_order_details', 'harga_per_gram_input')) {
                // Harga per gram yang diinput manual untuk kadar ini
                $table->unsignedBigInteger('harga_per_gram_input')->after('price');
            }
        });
    }
};