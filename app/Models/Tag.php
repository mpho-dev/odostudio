<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'media_tags')->withTimestamps();
    }

    protected function casts(): array
    {
        return [];
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Tag $tag): void {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }
}
