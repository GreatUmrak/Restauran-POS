<?php

namespace App\Filament\Resources\Dishes;

use App\Filament\Resources\Dishes\Pages\CreateDish;
use App\Filament\Resources\Dishes\Pages\EditDish;
use App\Filament\Resources\Dishes\Pages\ListDishes;
use App\Filament\Resources\Dishes\Schemas\DishForm;
use App\Filament\Resources\Dishes\Tables\DishesTable;
use App\Models\Dish;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DishResource extends Resource
{
    protected static ?string $model = Dish::class;

    public static function form(Schema $schema): Schema
    {
        return DishForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DishesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationLabel(): string
{
    return 'Блюда';
}

public static function getModelLabel(): string
{
    return 'блюдо';
}

public static function getPluralModelLabel(): string
{
    return 'Блюда';
}

    public static function getPages(): array
    {
        return [
            'index' => ListDishes::route('/'),
            'create' => CreateDish::route('/create'),
            'edit' => EditDish::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-beaker';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Меню';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    // Скрываем для официантов
    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole('admin');
    }
}