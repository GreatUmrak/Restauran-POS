<?php

use Illuminate\Support\Facades\Route;
use App\Models\Dish;
use App\Models\ModifierGroup;

Route::get('/test-relations', function () {
    $caesar = Dish::where('name', 'Цезарь с курицей')->first();
    $steak = Dish::where('name', 'Стейк Рибай')->first();
    
    echo "=== Салат Цезарь ===<br>";
    foreach ($caesar->modifierGroups as $group) {
        echo "Группа: " . $group->name . "<br>";
        foreach ($group->modifiers as $modifier) {
            echo " - " . $modifier->name . " (" . $modifier->formatted_price . ")<br>";
        }
    }
    
    echo "<br>=== Стейк Рибай ===<br>";
    foreach ($steak->modifierGroups as $group) {
        echo "Группа: " . $group->name . "<br>";
        foreach ($group->modifiers as $modifier) {
            echo " - " . $modifier->name . " (" . $modifier->formatted_price . ")<br>";
        }
    }
    
    return "Проверка завершена";
});

Route::get('/test-styles', \App\Livewire\WaiterDashboardTest::class);