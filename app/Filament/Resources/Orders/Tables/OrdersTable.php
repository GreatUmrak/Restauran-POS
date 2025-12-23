<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Номер заказа')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('table.name')
                    ->label('Стол')
                    ->sortable(),
                    
                TextColumn::make('user.name')
                    ->label('Официант')
                    ->sortable(),
                    
                TextColumn::make('total_amount')
                    ->label('Сумма')
                    ->money('RUB')
                    ->sortable(),
                    
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'Создан',
                        'preparing' => 'Готовится',
                        'ready' => 'Готов',
                        'served' => 'Выдан',
                        'cancelled' => 'Отменен',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'gray',
                        'preparing' => 'warning',
                        'ready' => 'success',
                        'served' => 'info',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'created' => 'Создан',
                        'preparing' => 'Готовится',
                        'ready' => 'Готов',
                        'served' => 'Выдан',
                        'cancelled' => 'Отменен',
                    ]),
                    
                SelectFilter::make('table')
                    ->relationship('table', 'name')
                    ->label('Стол'),
            ])
            ->actions([
                ViewAction::make(),
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