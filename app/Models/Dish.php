<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'is_active',
        'preparation_time'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function modifierGroups()
    {
        return $this->belongsToMany(ModifierGroup::class, 'dish_modifier_group');
    }
    
    // Аксессор для получения URL изображения
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/default-dish.png');
        }
        
        // Если это полный URL (например, из внешнего источника)
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        // Проверяем разные варианты хранения
        if (Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }
        
        // Если путь начинается с dishes/
        if (strpos($this->image, 'dishes/') === 0) {
            return Storage::url($this->image);
        }
        
        // Пробуем стандартный путь
        return asset('storage/' . $this->image);
    }
    
    // Аксессор для проверки наличия изображения
    public function getHasImageAttribute()
    {
        if (!$this->image) {
            return false;
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return true;
        }
        
        return Storage::disk('public')->exists($this->image) || 
               Storage::exists($this->image) || 
               file_exists(public_path('storage/' . $this->image));
    }
}