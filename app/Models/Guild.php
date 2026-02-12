<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guild extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'description',
        'invite_code',
        'master_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Guild $guild) {
            if (empty($guild->invite_code)) {
                $guild->invite_code = strtoupper(Str::random(8));
            }
        });
    }

    public function master()
    {
        return $this->belongsTo(User::class, 'master_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'guild_user')
            ->withPivot('role', 'joined_at')
            ->orderByDesc('level')
            ->orderByDesc('xp');
    }

    public function getTotalXp(): int
    {
        return (int) $this->members()->sum('xp');
    }

    public function getAverageLevel(): float
    {
        return round($this->members()->avg('level') ?? 0, 1);
    }

    public function getMemberCount(): int
    {
        return $this->members()->count();
    }
}
