<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'target_amount',
        'current_amount',
        'floor_number',
        'icon',
        'status',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressPercentage(): float
    {
        if ($this->target_amount <= 0)
            return 100;
        return min(100, round(($this->current_amount / $this->target_amount) * 100, 1));
    }
}
