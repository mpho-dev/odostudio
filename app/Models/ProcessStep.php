<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessStep extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'step_number',
        'title',
        'description',
        'display_order',
    ];

    protected $casts = [
        'step_number' => 'integer',
        'display_order' => 'integer',
    ];
}
