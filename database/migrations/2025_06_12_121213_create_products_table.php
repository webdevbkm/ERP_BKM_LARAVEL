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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->comment('Kode SKU, dibuat otomatis');
            
            // Foreign Keys to Master Tables
            $table->foreignId('kadar_id')->constrained('kadars')->cascadeOnDelete();
            $table->foreignId('jeni_id')->constrained('jenis')->cascadeOnDelete();
            $table->foreignId('warna_id')->constrained('warnas')->cascadeOnDelete();
            $table->foreignId('model_baki_id')->constrained('model_bakis')->cascadeOnDelete(); // Ini yang diperbaiki
            $table->foreignId('pabrik_id')->nullable()->constrained('pabriks')->nullOnDelete();
            $table->foreignId('price_type_id')->nullable()->constrained('price_types')->nullOnDelete();
            $table->foreignId('kokot_id')->nullable()->constrained('kokots')->nullOnDelete();
            $table->foreignId('cabang_id')->constrained('cabangs')->cascadeOnDelete();

            // Product Specific Fields
            $table->string('nama');
            $table->decimal('harga_dasar_batu', 15, 2)->default(0);
            $table->decimal('ongkos_per_item', 15, 2)->default(0);
            $table->decimal('berat', 10, 3)->comment('Berat dalam gram, 3 angka presisi');
            $table->decimal('panjang', 8, 2)->nullable();
            $table->string('klp')->nullable();
            $table->string('kd')->nullable();
            $table->integer('stok')->default(0);
            $table->text('keterangan')->nullable();
            
            // File Paths
            $table->string('foto_produk_path')->nullable();
            $table->string('qr_code_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
