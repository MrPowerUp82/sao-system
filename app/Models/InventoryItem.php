<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slot',
        'rarity',
        'value',
        'icon',
        'description',
        'attributes',
        'equipped',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'attributes' => 'array',
        'equipped' => 'boolean',
    ];

    const SLOT_LABELS = [
        'weapon' => '⚔️ Weapon (Cartão)',
        'armor' => '🛡️ Armor (Seguro)',
        'accessory' => '💍 Accessory (Conta)',
        'consumable' => '🧪 Consumable (Assinatura)',
        'material' => '💎 Material (Investimento)',
    ];

    const SLOT_ICONS = [
        'weapon' => '⚔️',
        'armor' => '🛡️',
        'accessory' => '💍',
        'consumable' => '🧪',
        'material' => '💎',
    ];

    const RARITY_COLORS = [
        'common' => '#8a8a9a',
        'uncommon' => '#4CAF50',
        'rare' => '#3498db',
        'epic' => '#9b59b6',
        'legendary' => '#FF9D00',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSlotLabel(): string
    {
        return self::SLOT_LABELS[$this->slot] ?? $this->slot;
    }

    public function getRarityColor(): string
    {
        return self::RARITY_COLORS[$this->rarity] ?? '#8a8a9a';
    }
}
