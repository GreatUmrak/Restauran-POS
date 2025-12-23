<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Сначала находим роли
        $adminRole = Role::where('name', 'admin')->first();
        $waiterRole = Role::where('name', 'waiter')->first();
        $chefRole = Role::where('name', 'chef')->first();

        // Обновляем или создаем администратора
        $admin = User::updateOrCreate(
            ['email' => 'admin@restaurant.local'],
            [
                'name' => 'Администратор',
                'password' => Hash::make('password123'), // Изменяем пароль
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // Обновляем или создаем официанта
        $waiter = User::updateOrCreate(
            ['email' => 'waiter@restaurant.local'],
            [
                'name' => 'Официант',
                'password' => Hash::make('password123'), // Изменяем пароль
            ]
        );
        if (!$waiter->hasRole('waiter')) {
            $waiter->assignRole($waiterRole);
        }

        // Обновляем или создаем шефа
        $chef = User::updateOrCreate(
            ['email' => 'chef@restaurant.local'],
            [
                'name' => 'Шеф Повар',
                'password' => Hash::make('password123'), // Изменяем пароль
            ]
        );
        if (!$chef->hasRole('chef')) {
            $chef->assignRole($chefRole);
        }
        
        echo "Пользователи обновлены!\n";
        echo "Администратор: admin@restaurant.local / password123\n";
        echo "Официант: waiter@restaurant.local / password123\n";
        echo "Шеф: chef@restaurant.local / password123\n";
    }
}