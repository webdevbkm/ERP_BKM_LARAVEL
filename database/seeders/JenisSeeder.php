<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- Baris ini penting!

class JenisSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis')->insert([
            ['nama' => 'Cincin'],
            ['nama' => 'Kalung'],
            ['nama' => 'Gelang'],
            ['nama' => 'Anting'],
            ['nama' => 'Logam Mulia'],
        ]);
    }
}