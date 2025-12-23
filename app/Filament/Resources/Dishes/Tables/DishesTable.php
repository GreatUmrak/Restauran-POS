<?php

namespace App\Filament\Resources\Dishes\Tables;

use App\Models\Dish;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DishesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Изображение')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-dish.png'))
                    ->getStateUsing(fn ($record) => $record->image_url),
                    
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('category.name')
                    ->label('Категория')
                    ->sortable()
                    ->badge(),
                    
                TextColumn::make('price')
                    ->label('Цена')
                    ->money('RUB')
                    ->sortable(),
                    
                TextColumn::make('preparation_time')
                    ->label('Время')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} мин" : '-')
                    ->sortable(),
                    
                IconColumn::make('is_active')
                    ->label('Активно')
                    ->boolean(),
                    
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Категория'),
                    
                TernaryFilter::make('is_active')
                    ->label('Активность'),
                    
                Filter::make('price_range')
                    ->form([
                        TextInput::make('min_price')
                            ->numeric()
                            ->placeholder('Мин. цена'),
                        TextInput::make('max_price')
                            ->numeric()
                            ->placeholder('Макс. цена'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['min_price'], fn($q, $value) => $q->where('price', '>=', $value))
                            ->when($data['max_price'], fn($q, $value) => $q->where('price', '<=', $value));
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}