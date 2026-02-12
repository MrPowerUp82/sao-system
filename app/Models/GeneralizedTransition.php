<?php

namespace App\Models;

use App\Casts\JsonCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\UserScope;

class GeneralizedTransition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'total_value',
        'installment_value',
        'description',
        'type',
        'start_date',
        'end_date',
        'input',
        'fix',
        'installment_amount',
        'tags',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'tags' => JsonCast::class,
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }

    protected static function booted()
    {
        static::addGlobalScope(new UserScope());
    }
}
