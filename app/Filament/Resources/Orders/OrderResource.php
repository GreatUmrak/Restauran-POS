<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\CreateOrder as CreateOrderPage;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    public static function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrderPage::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-clipboard-document-list';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Заказы';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Все заказы';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    
    // ИЗМЕНЕНИЕ: ТОЛЬКО для администратора
    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole('admin');
    }
    
    // ИЗМЕНЕНИЕ: Скрываем из навигации для официантов и поваров
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('admin');
    }
    
    public static function getNavigationBadge(): ?string
    {
        if (Auth::user()->hasRole('admin')) {
            return static::getModel()::whereNotIn('status', ['served', 'cancelled'])->count();
        }
        return null;
    }
}