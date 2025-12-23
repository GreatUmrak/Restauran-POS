<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    public function run()
    {
        $tables = [
            ['name' => 'Стол 1', 'number' => 1, 'capacity' => 4],
            ['name' => 'Стол 2', 'number' => 2, 'capacity' => 6],
            ['name' => 'Стол 3', 'number' => 3, 'capacity' => 2],
            ['name' => 'Стол 4', 'number' => 4, 'capacity' => 8],
            ['name' => 'Барная стойка 1', 'number' => 5, 'capacity' => 1],
            ['name' => 'Барная стойка 2', 'number' => 6, 'capacity' => 1],
        ];
        
        foreach ($tables as $table) {
            Table::create($table);
        }
    }
}