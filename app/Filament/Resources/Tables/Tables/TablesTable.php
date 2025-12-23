<?php

namespace App\Filament\Resources\Tables\Tables;

use App\Models\Table as TableModel;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table as FilamentTable;

class TablesTable
{
    public static function configure(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('№')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('capacity')
                    ->label('Вместимость')
                    ->formatStateUsing(fn ($state) => "{$state} чел.")
                    ->sortable(),
                    
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'free' => 'Свободен',
                        'occupied' => 'Занят',
                        'in_process' => 'В процессе',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'free' => 'success',
                        'occupied' => 'danger',
                        'in_process' => 'warning',
                        default => 'gray',
                    }),
                    
                TextColumn::make('orders_count')
                    ->label('Активные заказы')
                    ->counts(['orders' => function ($query) {
                        $query->whereNotIn('status', ['served', 'cancelled']);
                    }])
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'gray'),
                    
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'free' => 'Свободен',
                        'occupied' => 'Занят',
                        'in_process' => 'В процессе',
                    ]),
                    
                Filter::make('capacity')
                    ->form([
                        TextInput::make('min_capacity')
                            ->numeric()
                            ->placeholder('Мин. вместимость'),
                        TextInput::make('max_capacity')
                            ->numeric()
                            ->placeholder('Макс. вместимость'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['min_capacity'], fn($q, $value) => $q->where('capacity', '>=', $value))
                            ->when($data['max_capacity'], fn($q, $value) => $q->where('capacity', '<=', $value));
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('markFree')
                    ->label('Освободить')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (TableModel $record) { // Используем псевдоним
                        $record->status = 'free';
                        $record->save();
                    })
                    ->visible(fn (TableModel $record): bool => $record->status !== 'free'), // Используем псевдоним
                    
                Action::make('markOccupied')
                    ->label('Занять')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(function (TableModel $record) { // Используем псевдоним
                        $record->status = 'occupied';
                        $record->save();
                    })
                    ->visible(fn (TableModel $record): bool => $record->status !== 'occupied'), // Используем псевдоним
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('number');
    }
}