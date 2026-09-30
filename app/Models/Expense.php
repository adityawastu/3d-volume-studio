<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_date',
        'name',
        'category',
        'quantity',
        'unit_price',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];
}
