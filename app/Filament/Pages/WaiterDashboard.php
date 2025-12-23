<?php

namespace App\Filament\Pages;

use App\Models\Table;
use App\Models\Order;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class WaiterDashboard extends Page
{
    protected string $view = 'filament.pages.waiter-dashboard';
    
    // Данные для фильтров
    public $activeTab = 'all'; // 'all', 'preparing', 'ready'
    public $selectedTable = null;
    
    public function mount(): void
    {
        // Только официанты и админы могут видеть эту страницу
        abort_unless(Auth::user()->hasRole('waiter') || Auth::user()->hasRole('admin'), 403);
    }
    
    // Получение статистики
    public function getStatistics()
    {
        return [
            'total_orders' => Order::where('user_id', Auth::id())
                ->whereNotIn('status', ['served', 'cancelled'])
                ->count(),
            'preparing_orders' => Order::where('user_id', Auth::id())
                ->where('status', 'preparing')
                ->count(),
            'ready_orders' => Order::where('user_id', Auth::id())
                ->where('status', 'ready')
                ->count(),
            'free_tables' => Table::where('status', 'free')->count(),
            'occupied_tables' => Table::where('status', 'occupied')->count(),
            'total_tables' => Table::count(),
        ];
    }
    
    // Получение всех столов с количеством активных заказов
    public function getTables()
    {
        return Table::withCount(['orders' => function($query) {
            $query->whereNotIn('status', ['served', 'cancelled']);
        }])->orderBy('number')->get();
    }
    
    // Получение статуса стола
    public function getTableStatus($table)
    {
        $hasOrders = $table->orders_count > 0;
        
        if ($table->status === 'occupied') {
            return $hasOrders ? 'in_process' : 'occupied';
        }
        
        return $table->status;
    }
    
    // Получение цвета статуса стола
    public function getTableStatusColor($status)
    {
        return match($status) {
            'free' => 'success',
            'occupied' => 'danger',
            'in_process' => 'warning',
            default => 'gray',
        };
    }
    
    // Получение текста статуса стола
    public function getTableStatusText($status)
    {
        return match($status) {
            'free' => 'Свободен',
            'occupied' => 'Занят',
            'in_process' => 'Заказ в процессе',
            default => $status,
        };
    }
    
public function getReadyToServeOrders()
{
    $query = Order::query()
        ->with(['table', 'items.dish'])
        ->where('user_id', Auth::id())
        ->where('status', 'ready'); // ТОЛЬКО готовые, НЕ выданные
        
    if ($this->selectedTable) {
        $query->where('table_id', $this->selectedTable);
    }
        
    return $query->latest()->get();
}

// Добавим новый метод для выданных заказов (если нужно их где-то показывать)
public function getServedOrders()
{
    $query = Order::query()
        ->with(['table', 'items.dish'])
        ->where('user_id', Auth::id())
        ->where('status', 'served');
        
    if ($this->selectedTable) {
        $query->where('table_id', $this->selectedTable);
    }
        
    return $query->latest()->get();
}
    
    // Получение заказов, которые готовятся
    public function getPreparingOrders()
    {
        $query = Order::query()
            ->with(['table', 'items.dish'])
            ->where('user_id', Auth::id())
            ->where('status', 'preparing');
            
        if ($this->selectedTable) {
            $query->where('table_id', $this->selectedTable);
        }
            
        return $query->latest()->get();
    }
    
    // Пометить заказ как выданный - БЕЗ освобождения стола
public function markServed($orderId)
{
    $order = Order::find($orderId);
    
    if ($order && $order->status === 'ready') {
        $order->update(['status' => 'served']);
        
        Notification::make()
            ->title('Заказ выдан')
            ->body('Заказ №' . $order->order_number . ' успешно выдан. Стол ' . $order->table->name . ' остался занятым до освобождения.')
            ->success()
            ->send();
            
        // Редирект на ту же страницу, чтобы обновить список
        return redirect()->route('filament.admin.pages.waiter-dashboard');
    }
}
    
    // Удалить заказ
public function deleteOrder($orderId)
{
    $order = Order::find($orderId);
    
    if ($order && $order->user_id == Auth::id() && in_array($order->status, ['created', 'preparing', 'ready', 'served'])) {
        // Удаляем заказ
        $order->delete();
        
        Notification::make()
            ->title('Заказ удален')
            ->body('Заказ №' . $order->order_number . ' успешно удален')
            ->success()
            ->send();
            
        // Редирект на ту же страницу
        return redirect()->route('filament.admin.pages.waiter-dashboard');
    } else {
        Notification::make()
            ->title('Ошибка')
            ->body('Нельзя удалить этот заказ')
            ->danger()
            ->send();
    }
}

    // ДОБАВЛЯЕМ МЕТОД ДЛЯ АВТООБНОВЛЕНИЯ
    public function refreshWaiterDashboard()
    {
        // Просто вызываем dispatch для обновления компонента
        $this->dispatch('waiter-dashboard-updated');
    }
    
    // Пометить стол как свободный (только если нет активных заказов)
    public function freeTable($tableId)
    {
        $table = Table::find($tableId);
        
        if ($table && $table->status !== 'free') {
            // Проверяем, есть ли активные заказы на этом столе
            $hasActiveOrders = Order::where('table_id', $table->id)
                ->whereNotIn('status', ['served', 'cancelled'])
                ->exists();
                
            if (!$hasActiveOrders) {
                $table->update(['status' => 'free']);
                
                Notification::make()
                    ->title('Стол освобожден')
                    ->body('Стол ' . $table->name . ' теперь свободен')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Ошибка')
                    ->body('На столе ' . $table->name . ' еще есть активные заказы. Сначала завершите все заказы.')
                    ->warning()
                    ->send();
            }
        }
    }
    
    // Навигация и доступ
    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('waiter') || Auth::user()->hasRole('admin');
    }
    
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('waiter') || Auth::user()->hasRole('admin');
    }
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-home';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Рабочая зона';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Панель официанта';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
}