<?php

namespace App\Filament\Resources\Modifiers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ModifiersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('modifierGroup.name')
                    ->label('Группа')
                    ->sortable(),
                    
                TextColumn::make('price')
                    ->label('Доп. цена')
                    ->money('RUB')
                    ->formatStateUsing(fn ($state) => $state > 0 ? "+{$state} ₽" : "Бесплатно")
                    ->sortable(),
                    
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
            ->defaultSort('modifier_group_id', 'asc');
    }
}