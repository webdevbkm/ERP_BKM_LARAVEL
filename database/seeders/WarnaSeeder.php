<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- Baris ini penting!

class WarnaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('warnas')->insert([
            ['nama' => 'Kuning'],
            ['nama' => 'Putih'],
            ['nama' => 'Rose Gold'],
        ]);
    }
}