<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'modifier_group_id', 'name', 'price'
    ];

    public function modifierGroup()
    {
        return $this->belongsTo(ModifierGroup::class);
    }

    public function orderItemModifiers()
    {
        return $this->hasMany(OrderItemModifier::class);
    }
    
    public function getFormattedPriceAttribute()
    {
        if ($this->price > 0) {
            return '+' . number_format($this->price, 2, ',', ' ') . ' ₽';
        }
        return 'Бесплатно';
    }
}