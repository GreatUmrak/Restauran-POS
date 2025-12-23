<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemModifier extends Model
{
    // ИСПРАВЛЕНИЕ: Добавляем fillable поля
    protected $fillable = [
        'order_item_id',
        'modifier_id',
        'price'
    ];
    
    // Опционально: Добавляем отношения
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
    
    public function modifier()
    {
        return $this->belongsTo(Modifier::class);
    }
}