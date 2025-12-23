<?php

namespace App\Filament\Resources\Modifiers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ModifierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основная информация')
                    ->schema([
                        Select::make('modifier_group_id')
                            ->label('Группа модификаторов')
                            ->relationship('modifierGroup', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                            
                        TextInput::make('name')
                            ->label('Название модификатора')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Например: Двойной эспрессо'),
                            
                        TextInput::make('price')
                            ->label('Дополнительная цена')
                            ->numeric()
                            ->default(0)
                            ->prefix('₽')
                            ->helperText('Укажите 0, если модификатор бесплатный'),
                    ])
                    ->columns(2),
            ]);
    }
}