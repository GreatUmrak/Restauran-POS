<?php

namespace App\Filament\Resources\ModifierGroups\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ModifierGroupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название группы')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('type')
                    ->label('Тип')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'single' => 'Один вариант',
                        'multiple' => 'Несколько вариантов',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'single' => 'info',
                        'multiple' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                    
                IconColumn::make('required')
                    ->label('Обязательный')
                    ->boolean()
                    ->sortable(),
                    
                TextColumn::make('modifiers_count')
                    ->label('Кол-во модификаторов')
                    ->counts('modifiers'),
                    
                TextColumn::make('dishes_count')
                    ->label('Применено к блюдам')
                    ->counts('dishes'),
                    
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name', 'asc');
    }
}