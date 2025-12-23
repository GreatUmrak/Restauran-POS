<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Dish;

class DishSeeder extends Seeder
{
    public function run()
    {
        // Получаем категории
        $appetizers = Category::where('name', 'Закуски')->first();
        $salads = Category::where('name', 'Салаты')->first();
        $main = Category::where('name', 'Основные блюда')->first();
        $drinks = Category::where('name', 'Напитки')->first();
        $desserts = Category::where('name', 'Десерты')->first();

        // Создаем блюда
        $dishes = [
            [
                'category_id' => $appetizers->id,
                'name' => 'Брускетта с томатами',
                'description' => 'Хрустящий хлеб с помидорами и базиликом',
                'price' => 350.00,
                'preparation_time' => 10,
            ],
            [
                'category_id' => $salads->id,
                'name' => 'Цезарь с курицей',
                'description' => 'Классический салат Цезарь',
                'price' => 450.00,
                'preparation_time' => 15,
            ],
            [
                'category_id' => $main->id,
                'name' => 'Стейк Рибай',
                'description' => 'Стейк из мраморной говядины',
                'price' => 1200.00,
                'preparation_time' => 25,
            ],
            [
                'category_id' => $drinks->id,
                'name' => 'Кола',
                'description' => 'Напиток Coca-Cola 0.5л',
                'price' => 150.00,
                'preparation_time' => 2,
            ],
            [
                'category_id' => $desserts->id,
                'name' => 'Тирамису',
                'description' => 'Итальянский десерт',
                'price' => 400.00,
                'preparation_time' => 5,
            ],
        ];

        foreach ($dishes as $dish) {
            Dish::create($dish);
        }
    }
}