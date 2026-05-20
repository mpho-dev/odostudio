<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'file_name',
        'title',
        'description',
        'uploaded_by',
        'media_type',
        'project_id',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_media')
            ->withPivot('order')
            ->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'media_tags')->withTimestamps();
    }

    protected function casts(): array
    {
        return [];
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('file_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });

        $query->when($filters['media_type'] ?? null, function ($query, $type) {
            $query->where('media_type', $type);
        });

        $query->when($filters['collection'] ?? null, function ($query, $collectionId) {
            $query->whereHas('collections', function ($q) use ($collectionId) {
                $q->where('collections.id', $collectionId);
            });
        });

        $query->when($filters['tag'] ?? null, function ($query, $tagId) {
            $query->whereHas('tags', function ($q) use ($tagId) {
                $q->where('tags.id', $tagId);
            });
        });

        $query->when($filters['project'] ?? null, function ($query, $projectId) {
            $query->where('project_id', $projectId);
        });
    }
}
