<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'must_change_password',
        'crew_specialty',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
            'crew_specialty' => 'string',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'photographer_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'uploaded_by');
    }

    public function notificationPreference(): HasOne
    {
        return $this->hasOne(NotificationPreference::class);
    }

    public function crewBookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_crew')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function requestAssignments(): BelongsToMany
    {
        return $this->belongsToMany(BookingRequest::class, 'booking_request_crew')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function equipmentCheckouts(): HasMany
    {
        return $this->hasMany(EquipmentCheckout::class);
    }

    public function currentEquipment(): BelongsToMany
    {
        return $this->belongsToMany(EquipmentItem::class, 'equipment_checkouts')
            ->wherePivot('status', 'active')
            ->withPivot(['booking_id', 'checked_out_at', 'expected_return_at']);
    }

    public function equipmentHistory(): BelongsToMany
    {
        return $this->belongsToMany(EquipmentItem::class, 'equipment_checkouts')
            ->wherePivot('status', 'returned')
            ->withPivot(['booking_id', 'checked_out_at', 'returned_at', 'condition_out'])
            ->orderByPivot('returned_at', 'desc');
    }
}
