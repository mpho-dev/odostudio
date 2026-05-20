<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'invoice_number',
        'rate',
        'total_amount',
        'status',
        'notes',
        'pdf_path',
        'issued_at',
        'created_by',
        'paid_at',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'issued_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateInvoiceNumber(): string
    {
        $latestInvoice = self::lockForUpdate()->latest('id')->first();
        $number = $latestInvoice
            ? (int) str_replace('INV-', '', $latestInvoice->invoice_number) + 1
            : 1;

        return 'INV-'.str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
