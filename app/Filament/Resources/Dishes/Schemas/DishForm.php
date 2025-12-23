<?php

namespace App\Filament\Resources\Dishes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DishForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основная информация')
                    ->schema([
                        TextInput::make('name')
                            ->label('Название блюда')
                            ->required()
                            ->maxLength(255),
                            
                        Select::make('category_id')
                            ->label('Категория')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                            
                        Textarea::make('description')
                            ->label('Описание')
                            ->rows(3)
                            ->maxLength(500),
                            
                        TextInput::make('price')
                            ->label('Цена')
                            ->numeric()
                            ->prefix('₽')
                            ->required(),
                            
                        TextInput::make('preparation_time')
                            ->label('Время приготовления (мин)')
                            ->numeric()
                            ->suffix('минут'),
                    ])
                    ->columns(2),
                    
                Section::make('Дополнительно')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Изображение')
                            ->image()
                            ->directory('dishes')
                            ->maxSize(2048),
                            
                        Toggle::make('is_active')
                            ->label('Активно')
                            ->default(true),
                    ])
                    ->columns(2),
                    
                Section::make('Модификаторы')
                    ->schema([
                        Select::make('modifierGroups')
                            ->label('Группы модификаторов')
                            ->relationship('modifierGroups', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }
}