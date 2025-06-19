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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom warehouse_id setelah kolom 'password'
            // Kolom ini bisa null karena tidak semua user (misal: CEO, Admin) terikat pada satu gudang
            $table->foreignId('warehouse_id')->nullable()->after('password')->constrained('warehouses')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus foreign key constraint sebelum menghapus kolom
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
        });
    }
};
