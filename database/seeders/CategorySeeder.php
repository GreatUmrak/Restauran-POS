<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Закуски', 'type' => 'food', 'sort_order' => 1],
            ['name' => 'Салаты', 'type' => 'food', 'sort_order' => 2],
            ['name' => 'Основные блюда', 'type' => 'food', 'sort_order' => 3],
            ['name' => 'Напитки', 'type' => 'drink', 'sort_order' => 4],
            ['name' => 'Десерты', 'type' => 'food', 'sort_order' => 5],
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}