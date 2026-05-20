<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'model', 'serial_number', 'sku',
        'description', 'purchase_price', 'purchase_date', 'condition',
        'status', 'specifications', 'storage_location',
        'last_maintenance', 'next_maintenance', 'notes',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'purchase_date' => 'date',
        'last_maintenance' => 'date',
        'next_maintenance' => 'date',
        'specifications' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }

    public function checkouts(): HasMany
    {
        return $this->hasMany(EquipmentCheckout::class, 'equipment_item_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(EquipmentImage::class, 'equipment_item_id');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(EquipmentImage::class, 'equipment_item_id')
            ->where('is_primary', true);
    }

    public function scopeAvailableFor($query, Carbon $start, ?Carbon $end = null): void
    {
        $end = $end ?? $start;

        $query->where('status', '!=', 'retired')
            ->whereDoesntHave('checkouts', function ($q) use ($start, $end) {
                $q->where('status', 'active')
                    ->where(function ($sq) use ($start, $end) {
                        $sq->whereBetween('expected_return_at', [$start, $end])
                            ->orWhereBetween('checked_out_at', [$start, $end])
                            ->orWhere(function ($ssq) use ($start, $end) {
                                $ssq->where('checked_out_at', '<=', $start)
                                    ->where('expected_return_at', '>=', $end);
                            });
                    });
            });
    }

    public function isAvailableFor(Carbon $date): bool
    {
        return ! $this->checkouts()
            ->where('status', 'active')
            ->where('expected_return_at', '>=', $date)
            ->where('checked_out_at', '<=', $date)
            ->exists();
    }

    public function currentCheckout(): ?EquipmentCheckout
    {
        return $this->checkouts()
            ->where('status', 'active')
            ->with(['booking', 'user'])
            ->first();
    }
}
