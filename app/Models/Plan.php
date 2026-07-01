<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stripe_price_id',
        'features',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'features' => 'array',
        'is_active' => 'boolean',
    ];
}
