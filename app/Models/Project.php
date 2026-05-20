<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'client',
        'location',
        'description',
        'hero_media_id',
        'category',
        'is_featured',
        'order',
    ];

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function hero(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'hero_media_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_featured', true)->orderBy('order');
    }
}
