<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- Baris ini penting!

class ModelBakiSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan nama tabel benar: 'model_bakis'
        DB::table('model_bakis')->insert([
            ['nama' => 'Antam Sertifikat'],
            ['nama' => 'Model Polos'],
            ['nama' => 'Ukiran Naga'],
        ]);
    }
}
