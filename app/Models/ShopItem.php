<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'icon',
        'image_url',
        'rarity',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    const CATEGORY_LABELS = [
        'avatar' => '🖼️ Avatar',
        'title' => '🏷️ Título',
        'consumable' => '🧪 Consumível',
        'cosmetic' => '✨ Cosmético',
    ];

    const CATEGORY_ICONS = [
        'avatar' => '🖼️',
        'title' => '🏷️',
        'consumable' => '🧪',
        'cosmetic' => '✨',
    ];

    const RARITY_COLORS = [
        'common' => '#8a8a9a',
        'uncommon' => '#4CAF50',
        'rare' => '#3498db',
        'epic' => '#9b59b6',
        'legendary' => '#FF9D00',
    ];

    public function isAvailable(): bool
    {
        if (!$this->is_active)
            return false;
        if ($this->stock === null)
            return true;
        return $this->stock > 0;
    }

    public function getCategoryLabel(): string
    {
        return self::CATEGORY_LABELS[$this->category] ?? $this->category;
    }

    public function getRarityColor(): string
    {
        return self::RARITY_COLORS[$this->rarity] ?? '#8a8a9a';
    }
}
