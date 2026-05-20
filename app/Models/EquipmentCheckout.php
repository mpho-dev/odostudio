<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentCheckout extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'user_id', 'equipment_item_id',
        'checked_out_at', 'expected_return_at', 'returned_at',
        'condition_out', 'condition_in', 'checkout_notes', 'return_notes', 'status',
    ];

    protected $casts = [
        'checked_out_at' => 'datetime',
        'expected_return_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function equipmentItem(): BelongsTo
    {
        return $this->belongsTo(EquipmentItem::class, 'equipment_item_id');
    }

    public function checkIn(string $condition, ?string $notes = null): bool
    {
        $this->update([
            'status' => 'returned',
            'returned_at' => now(),
            'condition_in' => $condition,
            'return_notes' => $notes,
        ]);

        $this->equipmentItem->update(['status' => 'available']);

        return true;
    }

    public function isOverdue(): bool
    {
        return $this->status === 'active' && $this->expected_return_at < now();
    }
}
