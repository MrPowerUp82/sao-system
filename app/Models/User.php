<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Services\XpService;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'xp',
        'player_name',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public function transactions()
    {
        return $this->hasMany(GeneralizedTransition::class);
    }

    public function financialGoals()
    {
        return $this->hasMany(FinancialGoal::class);
    }

    public function getXpProgress(): array
    {
        return XpService::xpToNextLevel($this->xp ?? 0);
    }

    public function getDisplayName(): string
    {
        return $this->player_name ?? $this->name;
    }

    public function guilds()
    {
        return $this->belongsToMany(Guild::class, 'guild_user')
            ->withPivot('role', 'joined_at');
    }
}
