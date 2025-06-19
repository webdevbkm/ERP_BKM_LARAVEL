<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Pastikan ini di-import

class KadarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kadars')->insert([
            ['nama' => '999.9 (24K)', 'harga_per_gram' => 1250000],
            ['nama' => '875 (21K)', 'harga_per_gram' => 1100000],
            ['nama' => '750 (18K)', 'harga_per_gram' => 950000],
        ]);
    }
}
