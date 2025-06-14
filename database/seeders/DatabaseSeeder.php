<?php

namespace Database\Seeders;

use App\Models\User; // <-- 1. Import User
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- 2. Import Hash
use Spatie\Permission\Models\Role; // <-- 3. Import Role

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil semua seeder data master dan peran
        $this->call([
            RoleSeeder::class,
            KadarSeeder::class,
            JenisSeeder::class,
            WarnaSeeder::class,
            ModelBakiSeeder::class,
            CabangSeeder::class,
            PriceTypeSeeder::class,
        ]);

        // Buat pengguna Admin secara otomatis
        $adminUser = User::create([
            'name' => 'Robbi',
            'email' => 'robbiwiguna@bokormasgold.com',
            'password' => Hash::make('admin123'),
        ]);
        
        // Tetapkan peran 'Admin' ke pengguna tersebut
        $adminUser->assignRole('Admin');
        
        // Buat Pengguna CEO contoh
        $ceoUser = User::firstOrCreate(
            ['email' => 'ceo@example.com'],
            ['name' => 'CEO User', 'password' => Hash::make('password')]
        );
        $ceoUser->assignRole('CEO');
    }
}
