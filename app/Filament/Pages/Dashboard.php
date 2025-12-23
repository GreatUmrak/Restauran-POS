<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // Убираем свойство $view полностью для Filament 3.x
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-home';
    }
    
    public function getTitle(): string
    {
        return 'Панель управления';
    }
    
    public function getHeading(): string
    {
        return 'Добро пожаловать, ' . auth()->user()->name . '!';
    }
}