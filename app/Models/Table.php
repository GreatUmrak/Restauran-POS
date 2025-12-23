<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number',
        'capacity',
        'status'
    ];

    // Отношение с заказами
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}