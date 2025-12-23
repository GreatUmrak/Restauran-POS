<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'table_id', 'user_id', 'status',
        'total_amount', 'guests_count', 'notes'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            $order->order_number = 'ORD-' . date('Ymd') . '-' . str_pad(
                Order::whereDate('created_at', today())->count() + 1, 
                4, '0', STR_PAD_LEFT
            );
        });
        
        // При создании заказа меняем статус стола
        static::created(function ($order) {
            if ($order->table) {
                $order->table()->update(['status' => 'occupied']);
            }
        });

                // При создании заказа обновляем прибыль
        static::created(function ($order) {
            if ($order->table) {
                $order->table()->update(['status' => 'occupied']);
            }
            
            // ОБНОВЛЯЕМ ПРИБЫЛЬ
if (in_array($order->status, ['ready', 'served'])) {
    \App\Models\Profit::updateProfit($order);
}
        });
        
        // При обновлении заказа (например, отмена)
        static::updated(function ($order) {
            // Если заказ отменен, вычитаем из прибыли
            if ($order->isDirty('status') && $order->status === 'cancelled') {
                $profit = \App\Models\Profit::getTodayProfit();
                $profit->total_profit -= $order->total_amount;
                $profit->orders_count = max(0, $profit->orders_count - 1);
                $profit->save();
            }
            
            // Если заказ стал выполненным (served), добавляем в прибыль
            if ($order->isDirty('status') && $order->status === 'served') {
                \App\Models\Profit::updateProfit($order);
            }
        });
        
        // При обновлении заказа - УБИРАЕМ автоматическое освобождение стола
        static::updated(function ($order) {
            // Теперь стол не освобождается автоматически при отметке заказа как выданного
            // Стол освобождается только когда официант нажимает кнопку "Освободить стол"
            
            // Оставляем только изменение статуса на cancelled
            if ($order->status === 'cancelled') {
                // Проверяем, есть ли другие активные заказы на этом столе
                $hasActiveOrders = Order::where('table_id', $order->table_id)
                    ->whereNotIn('status', ['served', 'cancelled'])
                    ->where('id', '!=', $order->id)
                    ->exists();
                    
                if (!$hasActiveOrders && $order->table) {
                    $order->table()->update(['status' => 'free']);
                }
            }
        });
        
        // При удалении заказа
        static::deleting(function ($order) {
            // Проверяем, есть ли другие активные заказы на этом столе
            $hasActiveOrders = Order::where('table_id', $order->table_id)
                ->whereNotIn('status', ['served', 'cancelled'])
                ->where('id', '!=', $order->id)
                ->exists();
                
            if (!$hasActiveOrders && $order->table) {
                $order->table()->update(['status' => 'free']);
            }
        });

        // Добавить проверку существования класса Profit
static::created(function ($order) {
    if ($order->table) {
        $order->table()->update(['status' => 'occupied']);
    }
    
    // Проверяем существование класса Profit перед вызовом
    if (in_array($order->status, ['ready', 'served']) && class_exists(\App\Models\Profit::class)) {
        \App\Models\Profit::updateProfit($order);
    }
});

static::updated(function ($order) {
    // Если заказ отменен, вычитаем из прибыли
    if ($order->isDirty('status') && $order->status === 'cancelled') {
        if (class_exists(\App\Models\Profit::class)) {
            $profit = \App\Models\Profit::getTodayProfit();
            $profit->total_profit -= $order->total_amount;
            $profit->orders_count = max(0, $profit->orders_count - 1);
            $profit->save();
        }
    }
    
    // Если заказ стал выполненным (served), добавляем в прибыль
    if ($order->isDirty('status') && $order->status === 'served') {
        if (class_exists(\App\Models\Profit::class)) {
            \App\Models\Profit::updateProfit($order);
        }
    }
});
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Скоупы для фильтрации
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    // Аксессоры
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2, ',', ' ') . ' ₽';
    }
    
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'created' => 'Создан',
            'preparing' => 'Готовится',
            'ready' => 'Готов',
            'served' => 'Выдан',
            'cancelled' => 'Отменен',
            default => $this->status,
        };
    }
    
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'created' => 'gray',
            'preparing' => 'yellow',
            'ready' => 'green',
            'served' => 'blue',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}