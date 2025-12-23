<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationLabel(): string
{
    return 'Категории блюд';
}

public static function getModelLabel(): string
{
    return 'категорию';
}

public static function getPluralModelLabel(): string
{
    return 'Категории';
}

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-tag';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Меню';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 1;
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