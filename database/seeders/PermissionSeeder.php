<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Создаем или находим роли
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $waiterRole = Role::firstOrCreate(['name' => 'waiter']);
        $chefRole = Role::firstOrCreate(['name' => 'chef']);

        // Создаем базовые разрешения (опционально)
        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_menu',
            'manage_tables',
            'create_orders',
            'manage_orders',
            'view_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Назначаем все разрешения админу (если еще не назначены)
        $adminRole->givePermissionTo(Permission::all());
    }
}