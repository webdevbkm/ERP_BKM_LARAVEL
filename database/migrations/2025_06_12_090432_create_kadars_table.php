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
        Schema::create('kadars', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Contoh: 999, 750, Emas Muda
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_per_gram', 15, 2)->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kadars');
    }
};
