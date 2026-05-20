<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_request_id',
        'investment_tier_id',
        'photographer_id',
        'event_date',
        'location',
        'rate',
        'status',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'rate' => 'decimal:2',
    ];

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(BookingRequest::class);
    }

    public function investmentTier(): BelongsTo
    {
        return $this->belongsTo(InvestmentTier::class);
    }

    public function photographer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'photographer_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function crew(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'booking_crew')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function photographers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'booking_crew')
            ->where('booking_crew.role', 'photographer')
            ->withTimestamps();
    }

    public function videographers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'booking_crew')
            ->where('booking_crew.role', 'videographer')
            ->withTimestamps();
    }

    public function equipmentCheckouts(): HasMany
    {
        return $this->hasMany(EquipmentCheckout::class);
    }

    public function checkedOutEquipment(): BelongsToMany
    {
        return $this->belongsToMany(EquipmentItem::class, 'equipment_checkouts')
            ->withPivot(['user_id', 'checked_out_at', 'expected_return_at', 'status'])
            ->withTimestamps();
    }

    public function suggestEquipment()
    {
        $tier = $this->investmentTier;

        $packages = [
            'Portrait' => ['cameras', 'lenses', 'lighting'],
            'Event' => ['cameras', 'lenses', 'lighting', 'audio'],
            'Wedding' => ['cameras', 'lenses', 'lighting', 'audio', 'support'],
            'Commercial' => ['cameras', 'lenses', 'lighting', 'audio', 'support'],
        ];

        $needed = $packages[$tier->name] ?? $packages['Portrait'];

        return EquipmentItem::availableFor($this->event_date)
            ->whereHas('category', fn ($q) => $q->whereIn('slug', $needed))
            ->get();
    }

    public function getTotalAmountAttribute()
    {
        if ($this->investmentTier) {
            return $this->investmentTier->price;
        }

        return $this->rate ?? 0;
    }

    /**
     * Cancel a booking by updating its status to 'cancelled'.
     * This does not soft-delete the booking - the record remains queryable.
     */
    public function cancel(): bool
    {
        return $this->update(['status' => 'cancelled']);
    }

    /**
     * Restore a cancelled booking back to pending status.
     */
    public function restoreFromCancellation(): bool
    {
        return $this->update(['status' => 'pending']);
    }
}
