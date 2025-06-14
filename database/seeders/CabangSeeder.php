<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cabangs')->insert([
        ['nama' => 'HO', 'alamat' => 'Araya'],
        ['nama' => 'BKM 1', 'alamat' => 'Tambak Redjo'],
    ]);
    }
}
