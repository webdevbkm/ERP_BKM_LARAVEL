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
    Schema::table('customers', function (Blueprint $table) {
        // Menambahkan kolom 'telepon' setelah kolom 'nama'
        if (!Schema::hasColumn('customers', 'telepon')) {
            $table->string('telepon')->unique()->nullable()->after('nama');
        }
        
        // Menambahkan kolom 'points_balance' setelah kolom 'alamat'
        if (!Schema::hasColumn('customers', 'points_balance')) {
            $table->unsignedBigInteger('points_balance')->default(0)->after('alamat');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
