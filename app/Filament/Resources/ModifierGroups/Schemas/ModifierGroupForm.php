<?php

namespace App\Filament\Resources\ModifierGroups\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ModifierGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основная информация')
                    ->schema([
                        TextInput::make('name')
                            ->label('Название группы')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Например: Добавки к кофе'),
                            
                        Select::make('type')
                            ->label('Тип выбора')
                            ->options([
                                'single' => 'Один вариант',
                                'multiple' => 'Несколько вариантов',
                            ])
                            ->default('single')
                            ->required()
                            ->helperText('"Один вариант" - можно выбрать только один модификатор, "Несколько вариантов" - можно выбрать несколько'),
                            
                        Toggle::make('required')
                            ->label('Обязательный выбор')
                            ->helperText('Если включено, клиент должен выбрать хотя бы один вариант')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }
}