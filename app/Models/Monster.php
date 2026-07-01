<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'floor_min',
        'floor_max',
        'specific_floor',
        'description',
        'image_url',
        'icon',
        'xp_reward',
    ];

    const CATEGORIES = [
        'common' => 'Comum',
        'intermediate' => 'Intermediário',
        'elite' => 'Elite',
        'master' => 'Mestre',
        'boss' => 'Chefe de Andar',
    ];

    const CATEGORY_COLORS = [
        'common' => '#8a8a9a',
        'intermediate' => '#4CAF50',
        'elite' => '#3498db',
        'master' => '#9b59b6',
        'boss' => '#ff4757',
    ];

    public function getCategoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getCategoryColor(): string
    {
        return self::CATEGORY_COLORS[$this->category] ?? '#8a8a9a';
    }
}
