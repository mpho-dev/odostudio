<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'collection_media')
            ->withPivot('order')
            ->withTimestamps()
            ->orderByPivot('order');
    }

    protected function casts(): array
    {
        return [];
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Collection $collection): void {
            if (empty($collection->slug)) {
                $collection->slug = Str::slug($collection->name);
            }
        });
    }
}
