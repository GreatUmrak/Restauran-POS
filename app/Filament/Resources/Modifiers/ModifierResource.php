<?php

namespace App\Filament\Resources\Modifiers;

use App\Filament\Resources\Modifiers\Pages\CreateModifier;
use App\Filament\Resources\Modifiers\Pages\EditModifier;
use App\Filament\Resources\Modifiers\Pages\ListModifiers;
use App\Filament\Resources\Modifiers\Schemas\ModifierForm;
use App\Filament\Resources\Modifiers\Tables\ModifiersTable;
use App\Models\Modifier;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ModifierResource extends Resource
{
    protected static ?string $model = Modifier::class;

    public static function form(Schema $schema): Schema
    {
        return ModifierForm::configure($schema);
    }

    public static function getNavigationLabel(): string
{
    return 'Модификаторы';
}

public static function getModelLabel(): string
{
    return 'модификатор';
}

public static function getPluralModelLabel(): string
{
    return 'Модификаторы';
}

    public static function table(Table $table): Table
    {
        return ModifiersTable::configure($table);
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
            'index' => ListModifiers::route('/'),
            'create' => CreateModifier::route('/create'),
            'edit' => EditModifier::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-plus-circle';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Меню';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 4;
    }
    
    // Делаем видимым для администраторов
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('admin');
    }
    
    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole('admin');
    }
}