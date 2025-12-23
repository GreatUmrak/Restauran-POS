<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModifierGroup;
use App\Models\Modifier;
use App\Models\Dish;

class ModifierSeeder extends Seeder
{
    public function run()
    {
        // Создаем группы модификаторов
        $groups = [
            [
                'name' => 'Дополнения к салату',
                'type' => 'multiple',
                'required' => false,
            ],
            [
                'name' => 'Степень прожарки стейка',
                'type' => 'single',
                'required' => true,
            ],
            [
                'name' => 'Дополнительные топпинги',
                'type' => 'multiple',
                'required' => false,
            ],
        ];

        foreach ($groups as $groupData) {
            $group = ModifierGroup::create($groupData);

            // Создаем модификаторы для каждой группы
            switch ($group->name) {
                case 'Дополнения к салату':
                    $modifiers = [
                        ['name' => 'Дополнительный соус', 'price' => 50.00],
                        ['name' => 'Пармезан', 'price' => 80.00],
                        ['name' => 'Гренки', 'price' => 30.00],
                    ];
                    break;
                    
                case 'Степень прожарки стейка':
                    $modifiers = [
                        ['name' => 'Rare (с кровью)', 'price' => 0],
                        ['name' => 'Medium Rare (средне с кровью)', 'price' => 0],
                        ['name' => 'Medium (средняя)', 'price' => 0],
                        ['name' => 'Medium Well (почти прожаренный)', 'price' => 0],
                        ['name' => 'Well Done (полная прожарка)', 'price' => 0],
                    ];
                    break;
                    
                case 'Дополнительные топпинги':
                    $modifiers = [
                        ['name' => 'Сырный соус', 'price' => 70.00],
                        ['name' => 'Бекон', 'price' => 100.00],
                        ['name' => 'Грибы', 'price' => 60.00],
                    ];
                    break;
                    
                default:
                    $modifiers = [];
            }

            foreach ($modifiers as $modifierData) {
                Modifier::create(array_merge($modifierData, ['modifier_group_id' => $group->id]));
            }
        }

        // Привязываем группы модификаторов к блюдам
        $caesar = Dish::where('name', 'Цезарь с курицей')->first();
        $steak = Dish::where('name', 'Стейк Рибай')->first();

        $saladGroup = ModifierGroup::where('name', 'Дополнения к салату')->first();
        $steakGroup = ModifierGroup::where('name', 'Степень прожарки стейка')->first();
        $toppingsGroup = ModifierGroup::where('name', 'Дополнительные топпинги')->first();

        if ($caesar && $saladGroup) {
            $caesar->modifierGroups()->attach($saladGroup->id);
        }

        if ($steak && $steakGroup) {
            $steak->modifierGroups()->attach($steakGroup->id);
        }

        if ($steak && $toppingsGroup) {
            $steak->modifierGroups()->attach($toppingsGroup->id);
        }
    }
}