<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'dish_id',
        'quantity',
        'price',
        'special_instructions',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    public function modifiers()
    {
        return $this->belongsToMany(Modifier::class, 'order_item_modifiers')
            ->withPivot('price')
            ->withTimestamps();
    }
    
    // Аксессор для общей суммы с модификаторами
    public function getTotalWithModifiersAttribute()
    {
        $modifiersTotal = $this->modifiers->sum('pivot.price');
        return ($this->price + $modifiersTotal) * $this->quantity;
    }
}