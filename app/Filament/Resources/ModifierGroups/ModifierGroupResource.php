<?php

namespace App\Filament\Resources\ModifierGroups;

use App\Filament\Resources\ModifierGroups\Pages\CreateModifierGroup;
use App\Filament\Resources\ModifierGroups\Pages\EditModifierGroup;
use App\Filament\Resources\ModifierGroups\Pages\ListModifierGroups;
use App\Filament\Resources\ModifierGroups\Schemas\ModifierGroupForm;
use App\Filament\Resources\ModifierGroups\Tables\ModifierGroupsTable;
use App\Models\ModifierGroup;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ModifierGroupResource extends Resource
{
    protected static ?string $model = ModifierGroup::class;

    public static function form(Schema $schema): Schema
    {
        return ModifierGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ModifierGroupsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationLabel(): string
{
    return 'Группы модификаторов';
}

public static function getModelLabel(): string
{
    return 'группу модификаторов';
}

public static function getPluralModelLabel(): string
{
    return 'Группы модификаторов';
}

    public static function getPages(): array
    {
        return [
            'index' => ListModifierGroups::route('/'),
            'create' => CreateModifierGroup::route('/create'),
            'edit' => EditModifierGroup::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-list-bullet';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Меню';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    
    // Скрываем для официантов
    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole('admin');
    }
}