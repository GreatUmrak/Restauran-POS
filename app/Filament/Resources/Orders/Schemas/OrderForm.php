<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Table;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        $isChef = Auth::user()->hasRole('chef');
        
        $schemaComponents = [
            Section::make('Основная информация')
                ->schema([
                    TextInput::make('order_number')
                        ->label('Номер заказа')
                        ->disabled()
                        ->dehydrated(),
                        
                    Select::make('table_id')
                        ->label('Стол')
                        ->relationship('table', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->disabled($isChef), // Повар не может менять стол
                        
                    Select::make('user_id')
                        ->label('Официант')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->disabled($isChef), // Повар не может менять официанта
                        
                    TextInput::make('guests_count')
                        ->label('Количество гостей')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->disabled($isChef), // Повар не может менять количество гостей
                ])
                ->columns(2),
        ];
        
        // Секция статуса - для повара только чтение, для админа редактирование
        $statusSection = Section::make('Статус заказа')
            ->schema([
                Select::make('status')
                    ->label('Статус')
                    ->options([
                        'created' => 'Создан',
                        'preparing' => 'Готовится',
                        'ready' => 'Готов',
                        'served' => 'Выдан',
                        'cancelled' => 'Отменен',
                    ])
                    ->required()
                    ->disabled($isChef), // Повар не может менять статус здесь
                    
                TextInput::make('total_amount')
                    ->label('Общая сумма')
                    ->numeric()
                    ->prefix('₽')
                    ->required()
                    ->disabled($isChef), // Повар не может менять сумму
                    
                Textarea::make('notes')
                    ->label('Комментарии')
                    ->rows(3)
                    ->maxLength(500)
                    ->disabled($isChef), // Повар не может менять комментарии
            ])
            ->columns(2);
            
        $schemaComponents[] = $statusSection;
        
        return $schema
            ->components($schemaComponents);
    }
}