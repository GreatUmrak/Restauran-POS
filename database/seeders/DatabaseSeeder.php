<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Сначала создаем роли и разрешения
        $this->call(PermissionSeeder::class);
        
        // Затем остальные данные
        $this->call([
            CategorySeeder::class,
            TableSeeder::class,
            UserSeeder::class,
            DishSeeder::class,
            ModifierSeeder::class,
        ]);
    }
}