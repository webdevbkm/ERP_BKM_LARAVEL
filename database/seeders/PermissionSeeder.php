<?php
// File: database/seeders/PermissionSeeder.php (BARU)
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permission
        $permission = Permission::firstOrCreate(['name' => 'approve purchase orders']);

        // Berikan permission ke peran Admin dan CEO
        Role::findByName('Admin')->givePermissionTo($permission);
        Role::findByName('CEO')->givePermissionTo($permission);
    }
}