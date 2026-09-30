<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    protected $fillable = [
        'reference_no',
        'weight',
        'hours',
        'minutes',
        'total_minutes',
        'ratio',
        'method',
        'base_price',
        'fee',
        'fixed_cost',
        'price_per_item',
        'quantity',
        'total_price',
        'status',
        'fixed_at',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'ratio' => 'decimal:4',
        'base_price' => 'decimal:2',
        'fee' => 'decimal:2',
        'fixed_cost' => 'decimal:2',
        'price_per_item' => 'decimal:2',
        'total_price' => 'decimal:2',
        'fixed_at' => 'datetime',
    ];
}
