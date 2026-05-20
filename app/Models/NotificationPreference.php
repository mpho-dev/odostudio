<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    use HasFactory;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'notify_new_requests',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'notify_new_requests' => 'boolean',
    ];

    /**
     * Get the user that owns this notification preference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
