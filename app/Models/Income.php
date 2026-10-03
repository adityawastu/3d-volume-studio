<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'reference_no',
        'source',
        'external_order_no',
        'income_date',
        'order_date',
        'released_at',
        'customer_name',
        'description',
        'gross_amount',
        'amount',
        'payment_method',
        'shipping_service',
        'courier',
        'notes',
    ];

    protected $casts = [
        'income_date' => 'date',
        'order_date' => 'date',
        'released_at' => 'date',
        'gross_amount' => 'decimal:2',
        'amount' => 'decimal:2',
    ];
}
