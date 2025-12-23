<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'sort_order',
        'is_active'
    ];

    // Отношение с блюдами
    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }
}