<?php

namespace App\Filament\Resources\Tables;

use App\Filament\Resources\Tables\Pages\CreateTable;
use App\Filament\Resources\Tables\Pages\EditTable;
use App\Filament\Resources\Tables\Pages\ListTables;
use App\Filament\Resources\Tables\Schemas\TableForm;
use App\Filament\Resources\Tables\Tables\TablesTable;
use App\Models\Table as TableModel; // Используем псевдоним
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table as FilamentTable; // Используем псевдоним
use Illuminate\Support\Facades\Auth;

class TableResource extends Resource
{
    protected static ?string $model = TableModel::class; // Используем псевдоним

    public static function form(Schema $schema): Schema
    {
        return TableForm::configure($schema);
    }

    public static function table(FilamentTable $table): FilamentTable // Используем псевдоним
    {
        return TablesTable::configure($table);
    }

    public static function getNavigationLabel(): string
{
    return 'Столы';
}

public static function getModelLabel(): string
{
    return 'стол';
}

public static function getPluralModelLabel(): string
{
    return 'Столы';
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTables::route('/'),
            'create' => CreateTable::route('/create'),
            'edit' => EditTable::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-table-cells';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Ресторан';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canViewAny(): bool
{
    // Админ и официант видят столы, повар - нет
    return Auth::user()->hasRole('admin');
}

public static function shouldRegisterNavigation(): bool
{
    return Auth::user()->hasRole('admin');
}
}