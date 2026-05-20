<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'icon', 'sort_order'];

    public function items(): HasMany
    {
        return $this->hasMany(EquipmentItem::class, 'category_id');
    }

    public function availableItems(): HasMany
    {
        return $this->hasMany(EquipmentItem::class, 'category_id')
            ->where('status', 'available');
    }
}
