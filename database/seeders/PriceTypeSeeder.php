<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- Baris ini penting!

class PriceTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Menggunakan 'jumlah_penambahan_rupiah' sesuai perubahan migrasi
        DB::table('price_types')->insert([
            ['kode_tipe' => 'IH', 'nama_tipe' => 'IH', 'jumlah_penambahan_rupiah' => 10000],
            ['kode_tipe' => 'IJ', 'nama_tipe' => 'IJ', 'jumlah_penambahan_rupiah' => 5000],
            ['kode_tipe' => 'ONGKIR', 'nama_tipe' => 'Biaya Kirim', 'jumlah_penambahan_rupiah' => 25000],
        ]);
    }
}
