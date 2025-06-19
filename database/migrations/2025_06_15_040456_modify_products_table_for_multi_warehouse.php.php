<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Menghapus kolom 'stok' dan 'cabang_id' yang sudah tidak relevan
            $table->dropColumn('stok');
            
            // Hapus foreign key constraint sebelum drop kolom
            // Nama constraint bisa bervariasi, sesuaikan jika perlu. Format umum: 'nama_tabel_nama_kolom_foreign'
            $table->dropForeign(['cabang_id']); 
            $table->dropColumn('cabang_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Mengembalikan kolom jika migrasi di-rollback
            $table->integer('stok')->default(0)->after('kd');
            $table->foreignId('cabang_id')->nullable()->after('pabrik_id')->constrained('cabangs')->nullOnDelete();
        });
    }
};
