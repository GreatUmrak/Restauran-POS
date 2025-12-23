<?php

namespace App\Filament\Resources\Tables\Schemas;

use App\Models\Table as TableModel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Информация о столе')
                    ->schema([
                        TextInput::make('name')
                            ->label('Название стола')
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('number')
                            ->label('Номер стола')
                            ->numeric()
                            ->required()
                            ->unique(TableModel::class, 'number', ignoreRecord: true),
                            
                        TextInput::make('capacity')
                            ->label('Вместимость (чел)')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                            
                        Select::make('status')
                            ->label('Статус')
                            ->options([
                                'free' => 'Свободен',
                                'occupied' => 'Занят',
                                'in_process' => 'Заказ в процессе',
                            ])
                            ->default('free')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}