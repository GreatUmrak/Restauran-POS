<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class NewOrder extends Page
{
    protected string $view = 'filament.pages.new-order';
    
    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('waiter') || Auth::user()->hasRole('admin');
    }
    
    public static function shouldRegisterNavigation(): bool
    {
        return false; // Скрываем из навигации
    }
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-plus-circle';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Рабочая зона';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Создать заказ';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    
    public function mount()
    {
        // Просто перенаправляем на страницу создания заказа через ресурс
        return redirect()->route('filament.admin.resources.orders.create');
    }
}