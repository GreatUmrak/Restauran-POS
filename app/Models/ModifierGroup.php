<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModifierGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'required'
    ];

    protected $casts = [
        'required' => 'boolean',
    ];

    public function modifiers()
    {
        return $this->hasMany(Modifier::class);
    }

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_modifier_group');
    }
}