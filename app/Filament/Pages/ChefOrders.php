<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ChefOrders extends Page
{
    protected string $view = 'filament.pages.chef-orders';
    
    public $orders;
    public $statusFilter = '';
    public $tableFilter = '';
    
    public function mount(): void
    {
        // Только повара и админы могут видеть эту страницу
        abort_unless(Auth::user()->hasRole('chef') || Auth::user()->hasRole('admin'), 403);
        
        $this->loadOrders();
    }
    
    public function loadOrders()
    {
        $query = Order::query()
            ->with(['table', 'items.dish', 'items.modifiers.modifierGroup'])
            ->whereIn('status', ['created', 'preparing'])
            ->latest();
            
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        
        if ($this->tableFilter) {
            $query->where('table_id', $this->tableFilter);
        }
        
        $this->orders = $query->get();
    }
    
    public function updatedStatusFilter()
    {
        $this->loadOrders();
    }
    
    public function updatedTableFilter()
    {
        $this->loadOrders();
    }
    
    public function markPreparing($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status === 'created') {
            $order->update(['status' => 'preparing']);
            
            Notification::make()
                ->title('Начато приготовление')
                ->body('Заказ: ' . $order->order_number . ' | Стол: ' . $order->table->name)
                ->success()
                ->send();
                
            $this->loadOrders();
        }
    }

        // ДОБАВЛЯЕМ ЭТОТ МЕТОД ДЛЯ АВТООБНОВЛЕНИЯ
    public function refreshChefOrders()
    {
        $this->loadOrders();
        $this->dispatch('chef-orders-updated');
    }
    
    public function markReady($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status === 'preparing') {
            $order->update(['status' => 'ready']);
            
            Notification::make()
                ->title('Заказ готов к выдаче')
                ->body('Заказ: ' . $order->order_number . ' | Стол: ' . $order->table->name)
                ->success()
                ->send();
                
            $this->loadOrders();
        }
    }
    
    public function getTables()
    {
        return \App\Models\Table::all();
    }
    
    public function getStatusOptions()
    {
        return [
            '' => 'Все',
            'created' => 'Новые',
            'preparing' => 'Готовятся',
        ];
    }
    
    public function getStatusBadgeColor($status)
    {
        return match($status) {
            'created' => 'gray',
            'preparing' => 'warning',
            'ready' => 'success',
            default => 'gray',
        };
    }
    
    public function getStatusLabel($status)
    {
        return match($status) {
            'created' => 'Новый',
            'preparing' => 'Готовится',
            'ready' => 'Готов',
            'served' => 'Выдан',
            'cancelled' => 'Отменен',
            default => $status,
        };
    }
    
    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('chef') || Auth::user()->hasRole('admin');
    }
    
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('chef') || Auth::user()->hasRole('admin');
    }
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-fire';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Кухня';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Заказы на кухне';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
}