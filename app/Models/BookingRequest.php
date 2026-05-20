<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BookingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'status',
        'notes',
        'event_date',
        'event_type',
        'event_location',
        'email_sent_at',
        'service_id',
        'investment_tier_id',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'email_sent_at' => 'datetime',
        ];
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function investmentTier(): BelongsTo
    {
        return $this->belongsTo(InvestmentTier::class);
    }

    public function crew(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'booking_request_crew')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function photographers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'booking_request_crew')
            ->where('booking_request_crew.role', 'photographer')
            ->withTimestamps();
    }

    public function videographers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'booking_request_crew')
            ->where('booking_request_crew.role', 'videographer')
            ->withTimestamps();
    }

    public function getInterestNameAttribute(): string
    {
        if ($this->service_id && $this->service) {
            return $this->service->title;
        }

        if ($this->investment_tier_id && $this->investmentTier) {
            return $this->investmentTier->name;
        }

        return $this->event_type ?: 'General Enquiry';
    }
}
