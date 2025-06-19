<?php
// File: database/seeders/RoleSeeder.php (Diperbarui)
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'CEO']);
        Role::firstOrCreate(['name' => 'Purchasing']);
        Role::firstOrCreate(['name' => 'Staf Gudang']);
        Role::firstOrCreate(['name' => 'Kasir']);
    }
}