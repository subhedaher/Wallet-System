<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permission::create(['name' => 'Create-Admin', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Read-Admins', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Update-Admin', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Delete-Admin', 'guard_name' => 'admin']);

        // Permission::create(['name' => 'Create-Payment-Category', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Read-Payment-Categories', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Update-Payment-Category', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Delete-Payment-Category', 'guard_name' => 'admin']);

        // Permission::create(['name' => 'Create-Payment-Provider', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Read-Payment-Providers', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Update-Payment-Provider', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Delete-Payment-Provider', 'guard_name' => 'admin']);

        // Permission::create(['name' => 'Create-Shipping-Point', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Read-Shipping-Points', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Update-Shipping-Point', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Delete-Shipping-Point', 'guard_name' => 'admin']);

        // Permission::create(['name' => 'Create-Role', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Read-Roles', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Update-Role', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Delete-Role', 'guard_name' => 'admin']);

        // Permission::create(['name' => 'Read-Permissions', 'guard_name' => 'admin']);

        // Permission::create(['name' => 'Restore-Shipping-Point', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'ForceDelete-Shipping-Point', 'guard_name' => 'admin']);

        // Permission::create(['name' => 'Restore-Admin', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'ForceDelete-Admin', 'guard_name' => 'admin']);

        // Permission::create(['name' => 'Restore-Payment-Provider', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'ForceDelete-Payment-Provider', 'guard_name' => 'admin']);

        // Permission::create(['name' => 'Restore-Payment-Category', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'ForceDelete-Payment-Category', 'guard_name' => 'admin']);

        // Permission::create(['name' => 'Read-Users', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Read-Users', 'guard_name' => 'shippingPoint']);
        // Permission::create(['name' => 'User-Shipping', 'guard_name' => 'shippingPoint']);

        // Permission::create(['name' => 'Read-Payment-Categories', 'guard_name' => 'user-api']);
        // Permission::create(['name' => 'Read-Payment-Providers', 'guard_name' => 'user-api']);

        // Permission::create(['name' => 'Transaction-Shipping-Points-Admin', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Transaction-Shipping-Points', 'guard_name' => 'shippingPoint']);

        // Permission::create(['name' => 'Transaction-Payment-Providers', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Transaction-Users', 'guard_name' => 'admin']);
        // Permission::create(['name' => 'Transaction-All', 'guard_name' => 'admin']);
    }
}